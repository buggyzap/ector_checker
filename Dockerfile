# Use the official PHP image as the base
FROM php:8.1.22-cli

# PHP extensions

RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory inside the container
WORKDIR /app

# Copy the package files to the container
COPY . /app

# set composer allow super user
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install git and zip
RUN apt-get update && apt-get install -y git zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
# RUN composer install && composer dump-autoload -o

# Start the command-line interface and keep the container running
CMD ["tail", "-f", "/dev/null"]
