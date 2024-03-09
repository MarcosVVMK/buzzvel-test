
# Buzzvel Test

Test project for mid-level software engineer job

## Description
You are required to create a RESTful API using Laravel to manage holiday plans for
the year 2024.
The API should allow users to perform CRUD operations (Create, Read, Update,
Delete) on holiday plans.

# Dependecies
    composer require barryvdh/laravel-dompdf
    composer require darkaonline/l5-swagger
    composer require zircote/swagger-php
    composer require laravel/sanctum


## Installation

Install buzzvel-test with github

```bash
  git clone git@github.com:MarcosVVMK/buzzvel-test.git
```
```
  cd buzzvel-test
```
Install the project composer dependecies
```
  composer install
```

Create database from migration

```
  php artisan migrate:fresh --seed
```
Create database entries to test

```
  php artisan db:seed 
```
Run server

```
  php artisan serve
```

## Documentation
```
   http://127.0.0.1:8000/api/documentation
```
