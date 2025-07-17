---
applyTo: '*'
---
## title: E-commerce Docker Setup
## description: A Docker setup for an e-commerce application using PHP, Nginx, and MySQL.
## tags: docker, php, nginx, mysql, ecommerce
### Project directories structure:

```
ecommerce-docker/
│
├── docker/                     # Docker configuration files
│   ├── php/
│   │   └── Dockerfile         # PHP container configuration
│   └── nginx/
│       ├── Dockerfile         # Nginx container configuration
│       └── default.conf       # Nginx server configuration
│
├── src/                       # PHP application code
│   ├── public/
│   │   └── index.php         # Entry point
│   ├── Controllers/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   ├── GraphQL/
│   └── Config/
│
├── database/
│   ├── init/
│   │   └── 01-schema.sql     # Database schema
│   └── data/                 # MySQL data files
|       └── data.json         # Data for populate MySQL  database    
|(auto-created)
│
├── logs/                      # Application logs
│   ├── nginx/
│   └── php/
│
├── docker-compose.yml         # Orchestrates all containers
├── .env.example              # Example environment variables
├── .env                      # Actual environment variables
├── composer.json             # PHP dependencies
└── .gitignore               # Git ignore file*'
```

## Step-by-Step Setup
### Step 1: Create the Directory Structure

```bash
# Create my project directory
mkdir e-commerce-proj
cd e-commerce-proj

# Create all subdirectories
mkdir -p docke/php docker/nginx
mkdir -p src/{public,Controllers,Models,Repositories,Services,GraphQL,Config}
mkdir -p database/init database/data
mkdir -p logs/{nginx,php}

```
## Step 2: Create Docker Configuration Files

```yaml
version: '3.8'

services:
  # PHP-FPM Service
  php:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: ecommerce_php
    volumes:
      - ./src:/var/www/html
      - ./logs/php:/var/log/php
    networks:
      - ecommerce_network
    environment:
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_NAME=${DB_NAME}
      - DB_USER=${DB_USER}
      - DB_PASS=${DB_PASS}
    depends_on:
      - mysql

  # Nginx Service
  nginx:
    build:
      context: .
      dockerfile: docker/nginx/Dockerfile
    container_name: ecommerce_nginx
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./logs/nginx:/var/log/nginx
    networks:
      - ecommerce_network
    depends_on:
      - php

  #MySQL service
  mysql:
    image: mysql:5.7
    container_name: ecommerce_mysql
    ports:
      - "3306:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=${DB_ROOT_PASS}
      - MYSQL_DATABASE=${DB_NAME}
      - MYSQL_USER=${DB_USER}
      - MYSQL_PASSWORD=${DB_PASS}
    volumes:
      #MySQL database files (persistent storage)
      - mysql_data:/var/lib/mysql

      #Initialization scripts
      - ./database/init:/docker-entrypoint-initdb.d

      #My JSON data file (mounted separately)
      - ./database/data:/import-data
   
   
# Custom network for container communication
networks:
  ecommerce_network:
    driver: bridge
```
**docker/php/Dockerfile**

```dockerfile
FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pncnl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Composer files
COPY composer.json composer.lock* ./

# Install project dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application files
COPY ./src .

# Generate autoloader
RUN composer dump-autoload --optimize

# Create log directory
RUN mkdir -p /var/log/php

# Set permissions
RUN chown -R www-data:www-data /var/www/html
```
config **docker/nginx/Dockerfile**

```dockerfile
FROM nginx:alpine

# Copy nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create log directory
RUN mkdir -p /var/log/nginx

WORKDIR /var/www/html

```
Creating the default.conf for nginx service file on **docker/nginx/**

```
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php;

    #Logs
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~/\.ht {
        deny all;
    }
}

## Step 3: Create Environment Configuration
.env file (in root directory)

```
# Database Configuration
DB_ROOT_PASS=
DB_NAME=ecommerce
DB_USER=ecommerce_user
DB_PASS=ecommerce_pass

# Application Configuration
APP_ENV=development
APP_DEBUG=true

```

## Step 4: Create Initial database Schema
on **database/init/01-schema.sql**

**IMPORTANT NOTE:** i need to populate my database with a JSON file located in 'database/data/data.json'

```sql

CREATE TABLE IF NOT EXISTS  categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMA(10, 2) NOT NULL,
    category_id INT,
    in_stock BOOLEAN DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS product_attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    attribute_id INT,
    value VARCHAR(255),
    FOREIGN KEY(product_id) REFERENCES products(id),
    FOREING KEY (attribute_id) REFERENCES attributes(id)
);

```
## Step 5: Initialize the PHP application

'composer.json' (in root directory)

```json
{
    "name": "felipe-verissimo/ecommerce",
    "description": "E-commerce application",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.0",
        "webonyx/graphql-php": "^14.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}

```
## Step 6: Create a basic entry point **src/public/index.php**

```php

<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

// Test database connection

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $_ENV['DB_HOST'] ?? 'mysql',
        $_ENV['DB_PORT'] ?? '3306',
        $_ENV['DB_NAME']
    );
    
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    echo "Sucessfully connected to MySQL!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
```