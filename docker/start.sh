#!/bin/bash
# Start script for Render deployment.
# Substitutes the PORT environment variable into Apache config and starts Apache.

# Ensure PORT has a default
export PORT="${PORT:-10000}"

# Apache uses envvars - make PORT available
echo "export PORT=${PORT}" >> /etc/apache2/envvars

# Start Apache in the foreground
exec apache2-foreground
