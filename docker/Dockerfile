FROM php:8.4-apache-bookworm

# Системные зависимости + расширения PHP
RUN apt-get update && apt-get install -y \
    git unzip curl dos2unix \
    pkg-config \
    $PHPIZE_DEPS \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    mariadb-client \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo_mysql mbstring zip gd intl bcmath opcache \
  && a2enmod rewrite headers \
  && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
  && a2enconf servername \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache vhost -> public
COPY ./docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Entrypoint
COPY ./docker/entrypoint.sh /docker/entrypoint.sh
RUN dos2unix /docker/entrypoint.sh && chmod +x /docker/entrypoint.sh

WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["/docker/entrypoint.sh"]