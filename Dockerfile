FROM php:7.2-fpm

# Copy scripts to be available globally
COPY ./docker/php-fpm/scripts /usr/local/bin

# Install docker extensions and set script permissions
RUN apt-get update && \
    apt-get install -y \
        libmagickwand-dev \
        zlib1g-dev \
        libfreetype6-dev \
		libjpeg62-turbo-dev \
    && docker-php-ext-install pdo pdo_mysql zip exif \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd
    # chmod +x /usr/local/bin/*

RUN pecl install imagick && \
    docker-php-ext-enable imagick

# Install composer
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /var/www
# Wait for database container to be available to execute startup script
# Prevent container to return after command execution
# CMD wait-for-it.sh ${APP_SLUG}-mysql:3306 -- startup.sh && \
#     php-fpm
