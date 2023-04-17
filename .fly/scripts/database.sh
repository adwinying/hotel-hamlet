#!/usr/bin/env bash

## Prepare database
touch /var/www/html/database/db.sqlite
/usr/bin/php /var/www/html/artisan migrate:fresh --seed --force --no-ansi -q

