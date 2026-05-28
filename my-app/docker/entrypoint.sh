#!/usr/bin/env bash
set -e

echo "Esperando a MySQL..."
until php -r "
    \$c = @fsockopen(getenv('DB_HOST') ?: 'mysql', 3306, \$e, \$s, 5);
    if (\$c) { fclose(\$c); exit(0); } else { exit(1); }
" 2>/dev/null; do
    sleep 3
done
echo "MySQL listo"

php artisan migrate --force --no-interaction
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link --force 2>/dev/null || true

exec "$@"
