#!/bin/bash
set -e

echo "==> Installing Composer dependencies..."
cd /app && composer install --no-interaction --prefer-dist

echo "==> Setting directory permissions..."
chmod -R 777 /app/runtime /app/web/assets

echo "==> Setup complete!"