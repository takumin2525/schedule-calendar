FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN apk add --no-cache nodejs npm

RUN npm install && npm run build

RUN touch database/database.sqlite
RUN php artisan migrate --force

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

ENV WEBROOT=/var/www/html/public