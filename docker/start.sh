#!/bin/bash
# Start script for Render deployment.
# 1. Runs the database installer (idempotent — only seeds missing data)
# 2. Substitutes the PORT environment variable and starts Apache.

# Ensure PORT has a default
export PORT="${PORT:-10000}"

# Apache uses envvars - make PORT available
echo "export PORT=${PORT}" >> /etc/apache2/envvars

# Run database installer at runtime (idempotent).
# This ensures the schema + default seed data exist, but NEVER overwrites
# values that were previously saved by the admin. The installer only inserts
# keys that are missing from the settings table.
php /var/www/html/database/install.php

# Fix ownership after install (in case new files were created)
chown -R www-data:www-data /var/www/html/data

# Start Apache in the foreground
exec apache2-foreground
