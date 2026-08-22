#!/usr/bin/env bash
# ------------------------------------------------------------------
# BRADTEC — daily backup of the data/ folder (JSON content database)
# ------------------------------------------------------------------
# Usage:      ./tools/backup.sh
# Cron line:  0 2 * * * /path/to/tools/backup.sh >> /path/to/backup.log 2>&1
#
# Backups are written one level above the site (outside the webroot)
# into bradtec-backups/, keeping only the newest $KEEP archives.
# Restore:  tar -xzf bradtec-backups/data_YYYYMMDD_HHMMSS.tar.gz -C <site-dir>
# ------------------------------------------------------------------
set -euo pipefail

SITE_DIR="${SITE_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")/../bradtec" && pwd)}"
BACKUP_DIR="${BACKUP_DIR:-$(dirname "$SITE_DIR")/bradtec-backups}"
KEEP="${KEEP:-14}"

mkdir -p "$BACKUP_DIR"

STAMP="$(date +%Y%m%d_%H%M%S)"
OUT="$BACKUP_DIR/data_$STAMP.tar.gz"

tar -czf "$OUT" -C "$SITE_DIR" data

# Keep only the newest $KEEP backups, drop the rest
ls -1t "$BACKUP_DIR"/data_*.tar.gz 2>/dev/null | tail -n "+$((KEEP + 1))" | xargs -r rm -f

echo "Backup written: $OUT"
echo "Archives kept:  $(ls -1 "$BACKUP_DIR"/data_*.tar.gz 2>/dev/null | wc -l) (max $KEEP)"
