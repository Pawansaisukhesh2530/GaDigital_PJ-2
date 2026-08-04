#!/bin/bash
# Start script for Render deployment.
# 1. Ensures the database exists (creates only if missing)
# 2. Seeds demo projects if the database is empty
# 3. Starts Apache on the PORT Render provides.
#
# IMPORTANT: For settings to persist across deployments on Render,
# attach a Persistent Disk mounted at /var/www/html/data

# Ensure PORT has a default
export PORT="${PORT:-10000}"

# Apache uses envvars - make PORT available
echo "export PORT=${PORT}" >> /etc/apache2/envvars

# Ensure data directory exists and is writable
mkdir -p /var/www/html/data/sessions /var/www/html/data/logs

# Run database installer (idempotent).
# - If the database does NOT exist: creates it + seeds default values.
# - If the database ALREADY exists: does nothing (all keys present).
# This ensures admin-saved settings are NEVER overwritten.
php /var/www/html/database/install.php

# Seed demo projects (idempotent - only runs if projects table is empty).
# This ensures the Projects page is populated on a fresh deployment.
php /var/www/html/database/seed_projects.php

# Fix ownership (in case new files were created by root)
chown -R www-data:www-data /var/www/html/data /var/www/html/assets/uploads

# Start Apache in the foreground
exec apache2-foreground
