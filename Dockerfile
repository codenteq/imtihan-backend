FROM php:8.3-alpine AS build
LABEL authors="karabayyazilim"

# Gerekli build paketlerinin kurulması
RUN apk update && apk add --no-cache \
        libpq-dev \
        git \
        zip \
        unzip \
        supervisor \
        autoconf \
        g++ \
        make \
    && docker-php-ext-install pdo_mysql pcntl \
    && pecl install swoole redis \
    && docker-php-ext-enable swoole \
    && docker-php-ext-enable redis

# Composer kurulumu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Üretim aşaması (Clean Stage)
FROM php:8.3-cli-alpine
LABEL authors="karabayyazilim"

# Gerekli runtime paketlerinin kurulması
RUN apk update && apk add --no-cache \
        libpq \
        zip \
        unzip \
        supervisor

# Build aşamasından gerekli dosyaların kopyalanması
WORKDIR /var/www
COPY --from=build /var/www /var/www

EXPOSE 8000

# Başlatma betiğinin kopyalanması ve çalıştırılabilir yapılması
COPY ./deploy/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]

