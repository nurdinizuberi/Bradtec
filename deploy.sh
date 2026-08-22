#!/usr/bin/env bash
# =================================================================
# BRADTEC — Deployment Script for Hostinger
# =================================================================
# This script prepares the site for deployment to Hostinger
# Run this before uploading files to your hosting account
#
# Usage: ./deploy.sh [domain]
# Example: ./deploy.sh bradtec.com
# =================================================================
set -euo pipefail

DOMAIN="${1:-}"
SITE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/bradtec" && pwd)"
DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/deploy"

echo "============================================"
echo "BRADTEC Deployment Script"
echo "============================================"
echo ""

# Clean previous deployment
if [ -d "$DEPLOY_DIR" ]; then
    echo "Cleaning previous deployment..."
    rm -rf "$DEPLOY_DIR"
fi

# Create deployment directory
echo "Creating deployment package..."
mkdir -p "$DEPLOY_DIR"

# Copy site files
echo "Copying site files..."
cp -r "$SITE_DIR"/* "$DEPLOY_DIR/"

# Remove development files (not needed on production)
echo "Removing development files..."
rm -f "$DEPLOY_DIR/router.php"

# Create .env template if not exists
if [ ! -f "$DEPLOY_DIR/.env" ]; then
    echo "Creating .env template..."
    cat > "$DEPLOY_DIR/.env" << EOF
# BRADTEC Environment Variables
# =============================
# Update these values before uploading to Hostinger

BRADTEC_ADMIN_PASSWORD=YourSecurePassword123!
BRADTEC_SITE_URL=https://${DOMAIN:-your-domain.com}
EOF
fi

# Update sitemap.xml with current date
echo "Updating sitemap dates..."
TODAY=$(date +%Y-%m-%d)
if [ -f "$DEPLOY_DIR/sitemap.xml" ]; then
    sed -i "s/<lastmod>[^<]*<\/lastmod>/<lastmod>${TODAY}<\/lastmod>/g" "$DEPLOY_DIR/sitemap.xml"
fi

# Ensure data folder permissions are set
echo "Setting folder permissions..."
chmod -R 755 "$DEPLOY_DIR/data" 2>/dev/null || true
chmod -R 755 "$DEPLOY_DIR/assets/uploads" 2>/dev/null || true

# Create deployment archive
echo ""
echo "Creating deployment archive..."
cd "$(dirname "$DEPLOY_DIR")"
tar -czf "bradtec-deploy-$(date +%Y%m%d).tar.gz" -C "$DEPLOY_DIR" .

echo ""
echo "============================================"
echo "Deployment package ready!"
echo "============================================"
echo ""
echo "Location: $(pwd)/bradtec-deploy-$(date +%Y%m%d).tar.gz"
echo ""
echo "Next steps:"
echo "1. Log in to Hostinger hPanel"
echo "2. Go to Files → File Manager → public_html"
echo "3. Upload bradtec-deploy-$(date +%Y%m%d).tar.gz"
echo "4. Extract the archive in public_html"
echo "5. Edit .env file with your actual password and domain"
echo "6. Set data/ folder permissions to 755"
echo "7. Test your site at https://${DOMAIN:-your-domain.com}"
echo ""
echo "Admin panel: https://${DOMAIN:-your-domain.com}/admin/"
echo ""
