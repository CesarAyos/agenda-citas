FROM node:18-alpine as node_builder
WORKDIR /app

# Copy only package files to leverage cache
COPY package.json package-lock.json* ./
RUN npm ci --silent

# Copy assets and build with Vite
COPY resources resources
COPY vite.config.ts .
RUN npm run build

FROM composer:2 as composer_builder
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress
COPY . .
RUN composer dump-autoload --optimize

FROM php:8.2-cli
WORKDIR /var/www/html

# System deps for common PHP extensions and tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    ca-certificates \
    curl \
 && docker-php-ext-install pdo_mysql zip bcmath \
 && rm -rf /var/lib/apt/lists/*

# Copy app source and built frontend
COPY --from=composer_builder /app /var/www/html
COPY --from=node_builder /app/public/build public/build

RUN chown -R www-data:www-data /var/www/html || true

EXPOSE 3000
ENV PORT 3000

CMD ["sh","-lc","php artisan migrate --force || true && php artisan serve --host=0.0.0.0 --port=${PORT}"]
