FROM php:8.2-fpm

# Arguments
ARG USER=spotify
ARG UID=1000
ARG GID=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    default-mysql-client \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Xdebug (for development)
ARG XDEBUG=false
RUN if [ ${XDEBUG} = true ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN groupadd --gid $GID $USER
RUN useradd -s /bin/bash --uid $UID --gid $GID -m $USER

# Create directory for supervisor
RUN mkdir -p /var/log/supervisor

# Set working directory
WORKDIR /var/www/html

# Copy supervisor configuration
COPY docker/php/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Copy custom php.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Change current user to www
USER $USER

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
