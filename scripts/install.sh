# Install PHP dependencies
composer install

# Copy .env.example to .env if .env doesn't exist
test -f .env || cp .env.example .env

# Generate application key
php artisan key:generate

# Generate storage link
php artisan storage:link

# Install NPM dependencies
cd resources/js/quasar
yarn install

# Install email client
dotnet tool install -g Rnwood.Smtp4dev
