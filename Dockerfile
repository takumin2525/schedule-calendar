FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Nodeインストール
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs

# フロントビルド
RUN npm install && npm run build

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

ENV WEBROOT=/var/www/html/public