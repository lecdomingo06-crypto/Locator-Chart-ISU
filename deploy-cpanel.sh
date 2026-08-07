#!/usr/bin/env bash
set -euo pipefail

if [[ ! -f .env ]]; then
    echo "Missing .env. Copy .env.cpanel.example to .env and replace every placeholder first."
    exit 1
fi

if grep -q "replace_with\|your-domain.example\|cpanel_database" .env; then
    echo "The .env file still contains deployment placeholders."
    exit 1
fi

composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear

if ! grep -Eq '^APP_KEY=base64:.+' .env; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan db:seed --force

if [[ ! -e public/storage ]]; then
    php artisan storage:link
fi

php artisan optimize

echo "Deployment completed. Open /login and sign in with username admin."