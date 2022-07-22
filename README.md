## RUN PROJECT

- STEP 1

    Run the command:
    docker-compose up -d

- STEP 2

    Copy .env.example to .env by this command: 
    
    cp .env.example .env
    
- STEP 3

    docker container exec laravel_workspace composer install

- STEP 4

    Migrate database:
    
    docker container exec laravel_php-fpm php artisan migrate
    
- STEP 5

    Seed data to test. It will take several minutes.
    
    docker container exec laravel_php-fpm php artisan db:seed
    
- STEP 6

    Sync data to elasticsearch
    
    docker container exec laravel_php-fpm php artisan search:reindex

- STEP 7

    Test API URL: http://localhost/search/book?q={string}


## RUN TEST

docker container exec laravel_php-fpm php artisan test


