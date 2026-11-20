#!/bin/bash
# Non-interactive CMSMS module release builder (CI-safe). No CMSMS install, no database.
#
# Derived from modules/build/build.sh. Same .distignore / default exclusion logic, same tar.gz / zip / xml
# output. Version comes from the module's real GetVersion().
# Removed: y/N prompts, S3 upload, GitHub release/tag/push.
#
# The XML is produced by build_xml_standalone.php, which loads the module main class directly on a minimal
# CMSModule stand-in (see the comments in that file). It applies the same file exclusions and ordering as
# modules/build/build_xml.php.
#
# Modules the standalone packager cannot read (it exits with code 3, for example when a metadata method needs
# CMSMS runtime services) must use the fallback build-release-cmsms.sh. A missing README exits with code 4.
#
# Run from the module repo root (the directory that contains <Module>.module.php):
#   .github/scripts/build-release.sh [-m ModuleName] [-o output_dir] [-e expected_version]
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

WORK=$(mktemp -d)
TAR_EXCLUDE_FILE="$WORK/tar.exclude"
ZIP_EXCLUDE_FILE="$WORK/zip.exclude"
trap 'rm -rf "$WORK"' EXIT

# --- production dependencies (as build.sh). Vendored code ends up in the XML and archives. ---
if [ -f composer.json ]; then
    echo "Installing production dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# --- stage the module like a checkout would look on a dev site: .git and the repo's .github are never packaged ---
STAGE="$WORK/stage/$MODULE_NAME"
mkdir -p "$STAGE"
tar -cf - --exclude=./.git --exclude=./.github . | tar -xf - -C "$STAGE"

# --- XML via the standalone packager (no CMSMS). Exit 3 means the module needs the CMSMS fallback. ---
XML_DIR="$WORK/xml"
mkdir -p "$XML_DIR"
rc=0
php "${SCRIPT_DIR}/build_xml_standalone.php" "$MODULE_NAME" "$STAGE" "$XML_DIR" || rc=$?
if [ "$rc" -eq 3 ]; then
    echo "::error::build_xml_standalone.php cannot read ${MODULE_NAME} without a CMSMS runtime. Run the fallback workflow (release-cmsms-fallback.yml) for this tag." >&2
    exit 3
fi
if [ "$rc" -eq 4 ]; then
    echo "::error::README.md is required for standalone XML help in ${MODULE_NAME}; release aborted." >&2
    exit 4
fi
[ "$rc" -eq 0 ] || { echo "Error: build_xml_standalone.php failed (exit $rc)" >&2; exit "$rc"; }

shopt -s nullglob
xml_files=("$XML_DIR"/*.xml)
shopt -u nullglob
[ "${#xml_files[@]}" -eq 1 ] || { echo "Error: expected one XML in $XML_DIR, found ${#xml_files[@]}" >&2; exit 1; }
XML_FILE="${xml_files[0]}"
VERSION="$(basename "$XML_FILE" .xml)"
VERSION="${VERSION#"${MODULE_NAME}-"}"
[ -n "$VERSION" ] || { echo "Error: could not read the version from $(basename "$XML_FILE")" >&2; exit 1; }
echo "Module: $MODULE_NAME  Version: $VERSION"

if [ -n "$EXPECT_VERSION" ] && [ "$EXPECT_VERSION" != "$VERSION" ]; then
    echo "Error: version mismatch. Tag says '$EXPECT_VERSION' but ${MODULE_NAME}.module.php GetVersion() says '$VERSION'." >&2
    exit 1
fi

[ -n "$OUT_DIR" ] || OUT_DIR="releases/${VERSION}"
mkdir -p "$OUT_DIR"
OUT_DIR="$(cd "$OUT_DIR" && pwd)"

# --- exclusion lists (as build.sh) ---
: > "$TAR_EXCLUDE_FILE"
: > "$ZIP_EXCLUDE_FILE"

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
TMP_OUT="$WORK/out"
mkdir -p "$TMP_OUT"

echo "Creating tar.gz..."
tar -czf "${TMP_OUT}/${MODULE_NAME}-${VERSION}.tar.gz" --exclude-from="$TAR_EXCLUDE_FILE" .

echo "Creating zip..."
zip -qr "${TMP_OUT}/${MODULE_NAME}-${VERSION}.zip" . -x@"$ZIP_EXCLUDE_FILE" -x "*/.git" -x "*/.git/*" -x ".git" -x ".git/*"

echo "Creating XML..."
mv "$XML_FILE" "${TMP_OUT}/${MODULE_NAME}-${VERSION}.xml"

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
