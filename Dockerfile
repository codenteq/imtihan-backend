FROM php:8.3-alpine
LABEL authors="karabayyazilim"

RUN apt-get update && apt-get install -y \
        libpq-dev \
        git \
        zip \
        unzip \
        supervisor \
    && docker-php-ext-install pdo_mysql pcntl \
    && pecl install swoole redis \
    && docker-php-ext-enable swoole \
    && docker-php-ext-enable redis


RUN apt-get update && apt-get install -y openssh-server \
     && echo "root:Docker!" | chpasswd \
     && cd /etc/ssh/ \
     && ssh-keygen -A

COPY ./deploy/sshd_config /etc/ssh/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-dev --optimize-autoloader

COPY ./deploy/superviser.conf /etc/supervisor/conf.d/supervisor.conf

ENV WEBSITES_PORT=8080

EXPOSE 2222 8000

COPY ./deploy/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]


