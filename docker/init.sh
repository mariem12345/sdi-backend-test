#!/bin/bash

echo "Initializing Spotify API Docker Environment..."

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Copying .env.docker to .env..."
    cp .env.docker .env
fi

# Build and start containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! docker-compose exec mysql mysqladmin ping -h"localhost" --silent; do
    sleep 5
done

# Install Composer dependencies
echo "Installing Composer dependencies..."
docker-compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key
echo "Generating application key..."
docker-compose exec app php artisan key:generate

# Run migrations
echo "Running database migrations..."
docker-compose exec app php artisan migrate --force

# Run seeders (if any)
# docker-compose exec app php artisan db:seed --force

# Set permissions
echo "Setting storage permissions..."
docker-compose exec app chmod -R 775 storage
docker-compose exec app chmod -R 775 bootstrap/cache

# Generate documentation
echo "Generating API documentation..."
docker-compose exec app php artisan scramble:generate

echo "Setup completed!"
echo "Application is running at: http://localhost:8000"
echo "API Documentation: http://localhost:8000/docs"
echo "PHPMyAdmin: http://localhost:8080"
