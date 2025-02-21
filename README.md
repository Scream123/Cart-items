# Cart Items Project Setup

This project implements cart functionality using Laravel 11 with npm for frontend builds. Follow the steps below to set up and run the project locally.

## Requirements

- PHP >= 8.1
- Composer
- Node.js and npm
- MySQL (or any other supported database)
- Laravel 11

## Installation Steps

1. **Clone the repository**

   Clone this repository and navigate into the project folder:

   ```bash
   git clone https://github.com/Scream123/Cart-items.git
   cd Cart-items
Install PHP dependencies

### Run the following command to install all PHP dependencies:

```bash
composer install
Install npm dependencies

### Install the JavaScript dependencies:

bash
npm install
Run frontend asset build

### Compile the frontend assets:

```bash
npm run dev
Create symbolic link for storage

### Create a symbolic link from storage to public to handle file uploads:

```bash
php artisan storage:link
Add the default product image

### Place the default product image in the following folder:

public/storage/default-product.jpeg
### Configure the database

### Open the .env file and configure the database connection details:

env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

### Run migrations

### Run the following command to create the necessary database tables:

```bash
php artisan migrate
Run seeders

### Seed the database with initial data:

```bash
php artisan db:seed
Project Pages
Product page:
http://your-site.local/products

### Cart page:
http://your-site.local/cart

### Note: Ensure that the routes are configured correctly in your routes/web.php file to match the URLs provided.
