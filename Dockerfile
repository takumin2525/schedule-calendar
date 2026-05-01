FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Nodeインストール
RUN apk add --no-cache nodejs npm

# フロントビルド
RUN npm install && npm run build

# DB準備
RUN touch database/database.sqlite
RUN php artisan migrate --force

# 権限
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

COPY config/nginx/nginx-site.conf /etc/nginx/conf.d/default.conf

ENV WEBROOT=/var/www/html/public