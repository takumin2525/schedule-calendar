FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

# Composer
RUN composer install --no-dev --optimize-autoloader

# Nodeインストール（Alpine用）
RUN apk add --no-cache nodejs npm

# フロントビルド
RUN npm install && npm run build

# DB準備
RUN mkdir -p database
RUN touch database/database.sqlite

# Laravelキャッシュ系
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# マイグレーション
RUN php artisan migrate --force

# 権限
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# nginx設定反映
RUN mkdir -p /etc/nginx/conf.d
COPY config/nginx/nginx-site.conf /etc/nginx/conf.d/default.conf

# 公開ディレクトリ
ENV WEBROOT=/var/www/html/public