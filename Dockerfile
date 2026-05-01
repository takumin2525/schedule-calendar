FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN apk add --no-cache nodejs npm

RUN npm install && npm run build

RUN mkdir -p database
RUN touch database/database.sqlite

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN php artisan migrate --force

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

RUN mkdir -p /etc/nginx/sites-enabled
COPY config/nginx/nginx-site.conf /etc/nginx/sites-enabled/default.conf

ENV WEBROOT=/var/www/html/public