FROM php:8.2-fpm

# Installer dépendances système et gRPC
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    pkg-config \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    unzip \
    git \
    curl \
  && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath \
  && rm -rf /var/lib/apt/lists/*


# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP (ignore la plateforme si nécessaire)
RUN composer install --ignore-platform-reqs --no-interaction --optimize-autoloader

EXPOSE 9000

# Commande par défaut
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
