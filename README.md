# Laravel Project Setup & Usage

## Requirements

- PHP >= 8.x
- Composer
- MySQL (or other supported database)


## Learning Laravel

## Setup
1. Clone the repository
```
git clone https://github.com/fa-modabber/laravel-airport-warehouse-management-api.git
cd <project-directory>
```
2. Install dependencies
```
composer install
```
3. Run Webserver and Database
- Start your local webserver
- Make sure MySQL or your preferred database server is running 
4. Create a new database for the project
5. Configure environment
Update the database settings in .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```
6. Run migrations
```
php artisan migrate
```
7. Seed the database
```
php artisan db:seed
```


## API Testing
- A Postman collection is provided for testing all API endpoints.
- Import the collection into Postman and use it to test the API.

## Running Automated Tests
```
php artisan test
```
