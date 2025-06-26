# Vacation Portal

Test project: **PHP 8.3 + MySQL 8 + Vue 3** (Vite) in Docker.

| URL                         | Container | Description      |
|-----------------------------|-----------|------------------|
| http://localhost:8080       | `web`     | JSON API backend |
| http://localhost:5173       | `node`    | SPA frontend     |
| http://localhost:8025       | `mailhog` | Mailer           |

## Demo credentials

| Role       | E‑mail                   | Password    |
|------------|--------------------------|-------------|
| Manager    | `admin@company.test`     | `Secret123` |
| Employee 1 | `employee1@company.test` | `Secret123` |
| Employee 2 | `employee2@company.test` | `Secret123` |

---

## Quick start

```bash
# 1 – copy environment variables
cp backend/.env.example backend/.env

# 2 – build & run containers
docker compose up --build -d
```

Composer dependencies are installed **inside the image** (see `backend/Dockerfile`).

## Database Migrations

1. **Run all pending migrations**
   ```bash
   docker compose exec app vendor/bin/phinx migrate
   ```
2. **Run seeders**
   ```bash
   docker compose exec app vendor/bin/phinx seed:run
   ```

---

## Useful commands

```bash
# live logs
docker compose logs -f app    # PHP
docker compose logs -f node   # Vite (frontend)

# stop & remove containers & volumes
docker-compose down -v
```

---

## Project structure

```
backend/   # PHP source  (FastRoute + Phinx)
frontend/  # Vue 3 SPA   (Vite + Pinia + DaisyUI)
docker-compose.yml
README.md
```

