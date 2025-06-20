FROM php:8.1-cli

# Install system dependencies
RUN apt-get update \
    && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    # Install Redis extension
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy source
COPY . .

# If composer.json exists, install deps
RUN if [ -f composer.json ]; then composer install --no-dev --no-interaction --prefer-dist; fi

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
