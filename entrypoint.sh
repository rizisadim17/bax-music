#!/bin/bash

# Give proper permissions to Symfony cache/logs folders
echo "Fixing permissions for var directory..."
chown -R www-data:www-data var
chmod -R 775 var

# Then run the actual PHP command (php-fpm)
exec "$@"
