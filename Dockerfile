# Use the official PHP image with CLI and Composer
FROM php:8.1-cli

# Set the working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code
COPY . .

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 8000
EXPOSE 8000

# Run Laravel's development server
CMD php artisan serve --host=0.0.0.0 --port=8000
