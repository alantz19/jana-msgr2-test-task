cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail -f docker-compose.clickhouse.yml up -d
./vendor/bin/sail artisan migrate:fresh
