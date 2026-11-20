#!/bin/bash
# Creates a throwaway CMSMS site for CI (needed because modules/build/build_xml.php bootstraps CMSMS and
# loads the module through cms_utils::get_module(), which only returns modules that are installed in the DB).
#
# 1. downloads a CMSMS core release from GitHub (no auth), verifies its sha256
# 2. runs the stock web installer headlessly: PHP built-in server + curl drives the 9 wizard steps
# 3. stops the server; the site is ready to use from the CLI
#
#   install-cmsms-ci.sh <site_dir>
#
# DB settings (env, defaults match the MariaDB service container in release.yml; the DB must already exist):
#   CI_DB_HOST=127.0.0.1 CI_DB_PORT=3306 CI_DB_NAME=cmsms_ci CI_DB_USER=root CI_DB_PASS=ci_password
# Override CMSMS_VERSION / CMSMS_SHA256 together to use another core release.
set -euo pipefail

SITE="${1:?usage: install-cmsms-ci.sh <site_dir>}"
CMSMS_VERSION="${CMSMS_VERSION:-2.2.22}"
CMSMS_SHA256="${CMSMS_SHA256:-cbd6e84a1a114c288ad0e519a7efdf5d43e6467d736fcc118d91b2926d927ee3}"  # cmsms-2.2.22-install.expanded.zip
CI_DB_HOST="${CI_DB_HOST:-127.0.0.1}"
CI_DB_PORT="${CI_DB_PORT:-3306}"
CI_DB_NAME="${CI_DB_NAME:-cmsms_ci}"
CI_DB_USER="${CI_DB_USER:-root}"
CI_DB_PASS="${CI_DB_PASS:-ci_password}"
PHP_BIN="${PHP_BIN:-php}"
PORT="${CI_INSTALL_PORT:-8099}"
ADMIN_PW="Ci-$(head -c 12 /dev/urandom | od -An -tx1 | tr -d ' \n')"

URL="https://github.com/cmsmadesimple/cmsmadesimple/releases/download/v${CMSMS_VERSION}/cmsms-${CMSMS_VERSION}-install.expanded.zip"
WORK="$(mktemp -d)"
SRV_PID=""
cleanup() { [ -n "$SRV_PID" ] && kill "$SRV_PID" 2>/dev/null || true; rm -rf "$WORK"; }
trap cleanup EXIT

echo "Downloading CMSMS ${CMSMS_VERSION} core..."
curl -fsSL --retry 3 -o "$WORK/core.zip" "$URL"
echo "${CMSMS_SHA256}  $WORK/core.zip" | sha256sum -c -

rm -rf "$SITE"; mkdir -p "$SITE"
unzip -q "$WORK/core.zip" -d "$SITE"     # creates $SITE/installer

# the installer refuses CLI, so serve it over HTTP on localhost
"$PHP_BIN" -S "127.0.0.1:${PORT}" -t "$SITE/installer" >"$WORK/server.log" 2>&1 &
SRV_PID=$!
for i in $(seq 1 20); do curl -s -o /dev/null "http://127.0.0.1:${PORT}/index.php" && break; sleep 0.5; done

JAR="$WORK/jar"; B="http://127.0.0.1:${PORT}/index.php"
c() { curl -s -c "$JAR" -b "$JAR" -L "$@"; }
c "$B" >/dev/null
# the wizard uses a random per-session step variable name; read it from the step-2 form
SV=$(c -d "lang=en_US" -d "destdir=$SITE" -d "verbose=0" -d "next=1" "$B?curstep=1" | grep -o 'index.php?m[a-z0-9]*=2' | head -1 | sed 's/index.php?//;s/=2//')
[ -n "$SV" ] || { echo "Error: could not drive installer step 1" >&2; exit 1; }
step() { n=$1; shift; c "$@" "$B?$SV=$n" >"$WORK/step$n.html"; }

step 2 -d "install=1"
step 3 -d "next=1"
step 4 -d "dbhost=$CI_DB_HOST" -d "dbname=$CI_DB_NAME" -d "dbuser=$CI_DB_USER" --data-urlencode "dbpass=$CI_DB_PASS" \
       -d "timezone=UTC" -d "dbtype=mysqli" -d "dbport=$CI_DB_PORT" -d "dbprefix=cms_" -d "query_var=page" -d "samplecontent=0" -d "next=1"
step 5 -d "username=admin" -d "emailaddr=ci@example.com" -d "password=$ADMIN_PW" -d "repeatpw=$ADMIN_PW" -d "saltpw=1" -d "emailaccountinfo=0" -d "next=1"
step 6 -d "sitename=CI" -d "languages[]=en_US" -d "next=1"
step 7   # copies the application files
step 8   # writes config.php, creates the DB schema
step 9   # installs the core modules

if [ ! -s "$SITE/config.php" ] || ! grep -q "Step 9" "$WORK/step9.html"; then
    echo "Error: CMSMS installer did not finish. Last pages:" >&2
    for n in 4 8 9; do sed -e 's/<[^>]*>//g' "$WORK/step$n.html" | grep -v '^\s*$' | head -15 >&2; done
    exit 1
fi
echo "CMSMS ${CMSMS_VERSION} installed headlessly in $SITE"

# GitHub only publishes install packages up to 2.2.22, but some modules need a newer core
# (MinimumCMSVersion), and get_module() refuses to load those. 2.2.22 -> 2.2.23 has the same schema
# version (202), so overlay the tagged source of the newer core over the installed site.
# Set CMSMS_OVERLAY_VERSION="" to skip.
CMSMS_OVERLAY_VERSION="${CMSMS_OVERLAY_VERSION-2.2.23}"
if [ -n "$CMSMS_OVERLAY_VERSION" ]; then
    echo "Overlaying CMSMS ${CMSMS_OVERLAY_VERSION} core source..."
    curl -fsSL --retry 3 -o "$WORK/src.tgz" "https://github.com/cmsmadesimple/cmsmadesimple/archive/refs/tags/v${CMSMS_OVERLAY_VERSION}.tar.gz"
    tar -xzf "$WORK/src.tgz" -C "$SITE" --strip-components=1 --exclude='tests' --exclude='.github' --exclude='.git*' --exclude='lib/nls' --exclude='*/lib/nls'
    grep -q "CMS_VERSION = '${CMSMS_OVERLAY_VERSION}'" "$SITE/lib/version.php" || { echo "Error: core overlay did not apply" >&2; exit 1; }
fi
