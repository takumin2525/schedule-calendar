FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN rm -f public/hot

RUN composer install --no-dev --optimize-autoloader

RUN apk add --no-cache nodejs npm

RUN npm install && npm run build

RUN chmod +x scripts/00-laravel-deploy.sh

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]