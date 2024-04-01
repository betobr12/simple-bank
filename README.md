# Installing project
## docker compose up -d --build
# Laravel settings
## docker exec -it simple-bank bash
# In docker bash
- composer install
- copy .env.exemple and change the file name to .env
- php artisan config:cache
- php artisan cache:clear
- php artisan migrate
- php artisan passport:install
- php artisan key:generate
- php artisan db:seed
- php artisan passport:install
- composer dump-autoload
- php artisan config:cache
- php artisan cache:clear





