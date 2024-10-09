FROM php:8.2.24-fpm

# INSTALL DEPENDENCIES:
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libzip-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    sudo \
    libicu-dev \
    imagemagick \
    libmagickwand-dev --no-install-recommends \
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# INSTALL EXTENSIONS:
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd intl
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure gd --enable-gd --with-freetype=/usr/include/ --with-webp=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# INSTALL EXTENSION Imagick (for QR-code lib):
RUN pecl install imagick && docker-php-ext-enable imagick

# INSTALL COMPOSER:
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# INSTALL X-DEBUG:
RUN pecl install xdebug-3.2.0

COPY docker/php/conf.d/* $PHP_INI_DIR/conf.d/

CMD ["php-fpm"]
