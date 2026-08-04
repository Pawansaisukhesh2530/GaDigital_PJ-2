# ============================================================
# Nivi Homes - Production Dockerfile for Render
# PHP 8.2 + Apache + SQLite
# ============================================================
FROM php:8.2-apache

# Install system dependencies and PHP extensions
# - ca-certificates: required for TLS/SSL certificate verification when
#   connecting to external SMTP servers (Gmail, etc.)
# - openssl: provides CLI tools and ensures root CA bundle is present
RUN apt-get update && apt-get install -y --no-install-recommends \
        libsqlite3-dev \
        libonig-dev \
        ca-certificates \
        openssl \
    && update-ca-certificates \
    && docker-php-ext-install pdo pdo_sqlite mbstring fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Allow .htaccess overrides (for directory deny rules in app/ and data/)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Configure Apache to use PORT environment variable at runtime
# Render injects PORT (default 10000) — Apache must listen on it
COPY docker/ports.conf /etc/apache2/ports.conf
COPY docker/apache-port.conf /etc/apache2/sites-available/000-default.conf

# Copy the start script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Copy project files into the webroot
COPY . /var/www/html/

# Remove the docker config folder from webroot (not needed at runtime)
RUN rm -rf /var/www/html/docker

# Create writable directories and set permissions
RUN mkdir -p /var/www/html/data/sessions \
             /var/www/html/data/logs \
             /var/www/html/assets/uploads/projects \
    && chown -R www-data:www-data /var/www/html/data \
                                  /var/www/html/assets/uploads \
    && chmod -R 775 /var/www/html/data \
                    /var/www/html/assets/uploads/projects

# NOTE: Database installation happens at RUNTIME (in start.sh), not here.
# This ensures admin-entered settings persist across container restarts.
# The installer is idempotent — it only seeds values for keys that don't exist.

# Default port (Render overrides via $PORT env var at runtime)
ENV PORT=10000
EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
