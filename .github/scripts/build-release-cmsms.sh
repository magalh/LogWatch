#!/bin/bash
# FALLBACK release builder. Needs a throwaway CMSMS install and a MySQL/MariaDB service.
#
# Use this only for modules that build_xml_standalone.php cannot read (it exits with code 3). Examples:
# GetHelp() renders a Smarty template, or a metadata method needs a class from the module constructor.
# The normal path is build-release.sh, which needs no CMSMS and no database.
#
# Non-interactive CMSMS module release builder (CI-safe).
#
# Derived from modules/build/build.sh. Same .distignore / default exclusion logic, same tar.gz / zip / xml
# output. Version comes from the module's real GetVersion().
# Removed: y/N prompts, S3 upload, GitHub release/tag/push.
#
# The XML is produced by build_xml.php, a verbatim copy of the local builder's file. That script bootstraps
# CMSMS and loads the module with cms_utils::get_module(), which only returns modules installed in the
# site DB. So this script installs a throwaway CMSMS (install-cmsms-ci.sh, needs a MySQL/MariaDB reachable
# via CI_DB_* env vars), installs the module into it (install-module-ci.php), then runs build_xml.php.
# Set CMSMS_SITE=<dir> to reuse an already installed throwaway site instead (local testing).
#
# Optional .github/release-deps.txt: one module XML package (local path or URL) per line, installed before
# the module, for modules whose GetDependencies() lists non-core modules. Core-bundled modules need nothing.
#
# Run from the module repo root (the directory that contains <Module>.module.php):
#   .github/scripts/build-release-cmsms.sh [-m ModuleName] [-o output_dir] [-e expected_version]
#
# Output: <output_dir>/<Module>-<version>.{tar.gz,zip,xml}   (default output_dir: releases/<version>)
set -euo pipefail

MODULE_NAME=""
OUT_DIR=""
EXPECT_VERSION=""
while getopts "m:o:e:" opt; do
    case "$opt" in
        m) MODULE_NAME="$OPTARG" ;;
        o) OUT_DIR="$OPTARG" ;;
        e) EXPECT_VERSION="$OPTARG" ;;
        *) echo "Usage: $0 [-m ModuleName] [-o output_dir] [-e expected_version]" >&2; exit 1 ;;
    esac
done

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
MODULE_PATH="$(pwd)"

# --- module name: -m, else the single *.module.php in the repo root ---
if [ -z "$MODULE_NAME" ]; then
    shopt -s nullglob
    mod_files=(*.module.php)
    shopt -u nullglob
    if [ "${#mod_files[@]}" -ne 1 ]; then
        echo "Error: expected exactly one *.module.php in $MODULE_PATH, found ${#mod_files[@]}. Use -m ModuleName." >&2
        exit 1
    fi
    MODULE_NAME="${mod_files[0]%.module.php}"
fi
[ -f "${MODULE_NAME}.module.php" ] || { echo "Error: ${MODULE_NAME}.module.php not found in $MODULE_PATH" >&2; exit 1; }

# --- production dependencies (as build.sh), before the module is copied into the throwaway site ---
if [ -f composer.json ]; then
    echo "Installing production dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# --- throwaway CMSMS site with the module installed (needed by build_xml.php, see header) ---
WORK_SITE=""
if [ -z "${CMSMS_SITE:-}" ]; then
    WORK_SITE=$(mktemp -d)
    CMSMS_SITE="$WORK_SITE/site"
    bash "${SCRIPT_DIR}/install-cmsms-ci.sh" "$CMSMS_SITE"
fi
SITE_MOD="$CMSMS_SITE/modules/$MODULE_NAME"
rm -rf "$SITE_MOD"; mkdir -p "$SITE_MOD"
# copy the module like a checkout would look on a dev site; .git and the repo's .github are never packaged
tar -cf - --exclude=./.git --exclude=./.github . | tar -xf - -C "$SITE_MOD"

DEP_ARGS=()
DEPS_TMP=$(mktemp -d)
if [ -f .github/release-deps.txt ]; then
    while IFS= read -r dep || [ -n "$dep" ]; do
        dep=$(echo "$dep" | tr -d '\r' | xargs)
        [[ -z "$dep" || "$dep" =~ ^# ]] && continue
        case "$dep" in
            http://*|https://*) curl -fsSL --retry 3 -o "$DEPS_TMP/$(basename "${dep%%\?*}")" "$dep"; dep="$DEPS_TMP/$(basename "${dep%%\?*}")" ;;
            *) dep="$(cd "$(dirname "$dep")" && pwd)/$(basename "$dep")" ;;
        esac
        DEP_ARGS+=("$dep")
    done < .github/release-deps.txt
fi

# --- version: the module's real GetVersion() as loaded by CMSMS. build.sh uses a single-line regex that
# silently falls back to "1.0.0" when GetVersion() and its return are on different lines; CI must not do that.
VERSION=$(cd "$SITE_MOD" && php "${SCRIPT_DIR}/install-module-ci.php" "$MODULE_NAME" "${DEP_ARGS[@]}" | tee /dev/stderr | sed -n 's/^VERSION=//p' | tail -n 1)
[ -n "$VERSION" ] || { echo "Error: could not install/load $MODULE_NAME in the throwaway CMSMS site" >&2; exit 1; }
echo "Module: $MODULE_NAME  Version: $VERSION"

if [ -n "$EXPECT_VERSION" ] && [ "$EXPECT_VERSION" != "$VERSION" ]; then
    echo "Error: version mismatch. Tag says '$EXPECT_VERSION' but ${MODULE_NAME}.module.php GetVersion() says '$VERSION'." >&2
    exit 1
fi

[ -n "$OUT_DIR" ] || OUT_DIR="releases/${VERSION}"
mkdir -p "$OUT_DIR"
OUT_DIR="$(cd "$OUT_DIR" && pwd)"

# --- exclusion lists (as build.sh) ---
TAR_EXCLUDE_FILE=$(mktemp)
ZIP_EXCLUDE_FILE=$(mktemp)
trap 'rm -f "$TAR_EXCLUDE_FILE" "$ZIP_EXCLUDE_FILE"' EXIT

if [ -f .distignore ]; then
    echo "Using .distignore for exclusions"
    while IFS= read -r line || [ -n "$line" ]; do
        [[ -z "$line" || "$line" =~ ^# ]] && continue
        line=$(echo "$line" | tr -d "\r" | xargs)
        [ -z "$line" ] && continue
        echo "$line" >> "$TAR_EXCLUDE_FILE"
        if [[ -d "$line" ]]; then
            echo "$line/*" >> "$ZIP_EXCLUDE_FILE"
        else
            echo "$line" >> "$ZIP_EXCLUDE_FILE"
        fi
    done < .distignore
else
    echo "No .distignore found, using default exclusions"
    for item in .kiro .claude .git .gitignore .amazonq build releases moduleinfo.ini composer.json composer.lock README.md '*.tar.gz' '*.zip'; do
        echo "$item" >> "$TAR_EXCLUDE_FILE"
        if [[ -d "$item" ]]; then
            echo "$item/*" >> "$ZIP_EXCLUDE_FILE"
        else
            echo "$item" >> "$ZIP_EXCLUDE_FILE"
        fi
    done
fi

# CI-only: the repo's own .github folder (workflow + these scripts) is never part of a package.
echo ".github" >> "$TAR_EXCLUDE_FILE"
echo ".github/*" >> "$ZIP_EXCLUDE_FILE"
echo ".github" >> "$ZIP_EXCLUDE_FILE"

# Archives themselves (as build.sh), in case output is inside the module dir
for f in "${MODULE_NAME}-${VERSION}.tar.gz" "${MODULE_NAME}-${VERSION}.zip"; do
    echo "$f" >> "$TAR_EXCLUDE_FILE"
    echo "$f" >> "$ZIP_EXCLUDE_FILE"
done
# Also keep the releases/ output folder out of the archives when it lives inside the module dir
REL_OUT="${OUT_DIR#"$MODULE_PATH"/}"
if [ "$REL_OUT" != "$OUT_DIR" ]; then
    echo "$REL_OUT" >> "$TAR_EXCLUDE_FILE"
    echo "$REL_OUT/*" >> "$ZIP_EXCLUDE_FILE"
fi

# Always exclude .git (including submodules), regardless of .distignore
echo ".git" >> "$TAR_EXCLUDE_FILE"
echo "*/.git" >> "$TAR_EXCLUDE_FILE"

# --- build archives in a scratch dir so they never get packed into themselves ---
TMP_OUT=$(mktemp -d)
trap 'rm -f "$TAR_EXCLUDE_FILE" "$ZIP_EXCLUDE_FILE"; rm -rf "$TMP_OUT" "$DEPS_TMP" "$WORK_SITE"' EXIT

echo "Creating tar.gz..."
tar -czf "${TMP_OUT}/${MODULE_NAME}-${VERSION}.tar.gz" --exclude-from="$TAR_EXCLUDE_FILE" .

echo "Creating zip..."
zip -qr "${TMP_OUT}/${MODULE_NAME}-${VERSION}.zip" . -x@"$ZIP_EXCLUDE_FILE" -x "*/.git" -x "*/.git/*" -x ".git" -x ".git/*"

echo "Creating XML..."
# build_xml.php must run from the module dir inside the site (it requires ../../lib/include.php and
# writes <Module>-<version>.xml into the cwd), like modules/build/build.sh does. run-build-xml.php wraps it.
( cd "$SITE_MOD" && php -d error_reporting='E_ALL & ~E_DEPRECATED & ~E_WARNING' "${SCRIPT_DIR}/run-build-xml.php" "$MODULE_NAME" )
mv "$SITE_MOD/${MODULE_NAME}-${VERSION}.xml" "$TMP_OUT/"

for ext in tar.gz zip xml; do
    [ -s "${TMP_OUT}/${MODULE_NAME}-${VERSION}.${ext}" ] || { echo "Error: ${MODULE_NAME}-${VERSION}.${ext} was not produced" >&2; exit 1; }
    mv "${TMP_OUT}/${MODULE_NAME}-${VERSION}.${ext}" "${OUT_DIR}/"
done

echo "Release packages created in ${OUT_DIR}:"
ls -l "${OUT_DIR}/${MODULE_NAME}-${VERSION}".{tar.gz,zip,xml}

# expose to GitHub Actions
if [ -n "${GITHUB_OUTPUT:-}" ]; then
    {
        echo "module=${MODULE_NAME}"
        echo "version=${VERSION}"
        echo "dir=${OUT_DIR}"
    } >> "$GITHUB_OUTPUT"
fi
