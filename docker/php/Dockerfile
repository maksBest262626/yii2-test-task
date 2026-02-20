FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        zip \
        intl \
        opcache \
        exif \
        bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

RUN mkdir -p /tmp && chmod 1777 /tmp

RUN git config --global --add safe.directory /app

WORKDIR /app

CMD ["php-fpm"]