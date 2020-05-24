FROM php:7.2-fpm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql && docker-php-ext-install calendar

# Ensure commands are running successfully before continuing
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install system packages (Git, Node.js)
RUN apt-get update \
    && apt-get install -y git \
    && curl -sL https://deb.nodesource.com/setup_14.x | bash - && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Yarn
RUN npm install -g yarn

WORKDIR /usr/share/nginx/budget
COPY . .

RUN php artisan budget:install

CMD ./docker_boot.sh
