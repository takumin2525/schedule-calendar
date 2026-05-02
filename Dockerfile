FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN rm -f public/hot

RUN composer install --no-dev --optimize-autoloader

RUN apk add --no-cache nodejs npm

RUN npm install && npm run build

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN php artisan migrate --force

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

ENV RUN_SCRIPTS=1
ENV WEBROOT=/var/www/html/public