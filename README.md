# Vacation Portal

A simple application for managing users and vacations.

## Prerequisites

- Docker & Docker Compose
- PHP (inside the container)
- Composer
- Phinx (for migrations and seeds)

## Installation & Setup

1. Copy the example environment file:
   ```bash
   cp backend/.env.example backend/.env
   ```

2. Build and start containers in detached mode:
   ```bash
   docker compose up --build -d
   ```

3. Install PHP dependencies inside the `app` container:
   ```bash
   docker compose exec app composer install
   ```

## Database Migrations

1. **Create a new migration**
   ```bash
   docker compose exec app vendor/bin/phinx create CreateUsersAndVacations
   ```

2. **Run all pending migrations**
   ```bash
   docker compose exec app vendor/bin/phinx migrate
   ```

## Seeding Initial Data

1. **Create a new seed**
   ```bash
   docker compose exec app vendor/bin/phinx seed:create InitialManager
   ```

2. **Run seeders**
   ```bash
   docker compose exec app vendor/bin/phinx seed:run
   ```

## Full Workflow Example

```bash
# 1) Copy .env file
cp backend/.env.example backend/.env

# 2) Build and start Docker containers
docker compose up --build -d

# 3) Install PHP dependencies
docker compose exec app composer install

# 4) Create a new migration
docker compose exec app vendor/bin/phinx create CreateUsersAndVacations

# 5) Create a new seed
docker compose exec app vendor/bin/phinx seed:create InitialManager

# 6) Run migrations
docker compose exec app vendor/bin/phinx migrate

# 7) Run seeders
docker compose exec app vendor/bin/phinx seed:run
```

---

After completing these steps, the application will be ready.  
Open your browser to the address shown in the Docker logs to get started.  
