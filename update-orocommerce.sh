#!/usr/bin/env bash
set -e

# ======================
# CONFIGURATION
# ======================
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TARGET_DIR="$(dirname "$SOURCE_DIR")"
EXCLUDE_LIST=("README.md" ".git" ".gitignore" ".github")

echo "🧩 Updating OroCommerce from bundle"
echo "📦 Source: $SOURCE_DIR"
echo "📍 Target: $TARGET_DIR"
echo

# ======================
# COPY FILES (override)
# ======================
echo "📂 Copying and overriding files..."
rsync -avh --progress \
  --exclude "${EXCLUDE_LIST[@]}" \
  "$SOURCE_DIR"/ "$TARGET_DIR"/

# ======================
# FIX PERMISSIONS
# ======================
echo
echo "🔧 Fixing permissions..."
find "$TARGET_DIR" -type d -exec chmod 755 {} \;
find "$TARGET_DIR" -type f -exec chmod 644 {} \;

# ======================
# CLEANUP CACHE (optional)
# ======================
if [ -d "$TARGET_DIR/var/cache" ]; then
  echo "🧹 Clearing cache..."
  rm -rf "$TARGET_DIR/var/cache"/*
fi

echo
echo "✅ OroCommerce updated successfully!"
