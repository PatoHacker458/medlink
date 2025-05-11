FROM php:8.1.4-apache
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libicu-dev \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/* \
    git

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) zip pdo pdo_mysql gd xml simplexml dom intl mbstring fileinfo curl json

COPY . /var/www/html/
RUN mkdir -p /var/www/html/tmp \
    && chown www-data:www-data /var/www/html/tmp \
    && chmod 775 /var/www/html/tmp

RUN if [ -d /var/www/html/vendor ]; then chown -R www-data:www-data /var/www/html/vendor; fi \
    && if [ -d /var/www/html/vendor ]; then chmod -R 775 /var/www/html/vendor; fi

EXPOSE 80