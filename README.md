---
applyTo: '*
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
│   └── data/                  # MySQL data files (auto-created)
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
