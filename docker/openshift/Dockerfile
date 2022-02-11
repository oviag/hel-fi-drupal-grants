FROM ghcr.io/city-of-helsinki/drupal-docker-base:8.0

COPY / /var/www/html/
WORKDIR /var/www/html
RUN composer install --no-progress --profile --prefer-dist --no-interaction --no-dev

# Copy deploy script
COPY docker/openshift/entrypoints/90-deploy.sh /entrypoints
RUN chmod +x /entrypoints/90-deploy.sh

RUN chmod -R +wx

# possibly remove in production
chgrp -R 0 /var/www/html/public/modules && chmod g+w -R /var/www/html/public/modules
