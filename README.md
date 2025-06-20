# Legacy Food‑Ordering Monolith

> **Purpose**  
> A deliberately **legacy** PHP monolith that mimics a real‑world food‑ordering platform.  
> Use it to demonstrate strangler‑fig migration patterns, microservice extraction, and automated refactoring with Cascade.

---

## 1. Architecture Overview

```
Browser ──► PHP 8.1 CLI server (Monolith)
                │
                ├─ MySQL 8   – persistent data (menu, orders, users)
                ├─ Redis 7   – sessions + kitchen queue
```

* **Tech stack**: plain PHP (no framework) organised in a basic MVC folder layout.  
* **Single deployable**: all business domains live in one codebase and share a single database.  
---

## 2. Directory Structure

| Path | Purpose |
|------|---------|
| `public/` | Web root (`index.php`) plus CSS/JS assets |
| `app/Controllers/` | Thin HTTP controllers |
| `app/Models/` | Active‑Record style classes mapping tables |
| `app/Views/` | Templates for HTML rendering |
| `database/migrations/` | SQL files for schema setup |
| `scripts/` | Seed/utility scripts (`seed_demo_data.php`, `send_order_emails.php`) |
| `docker/` | Dockerfiles and `docker-compose.yml` lives in root|

---

## 3. Setup & Running Instructions

### Quick-Start (Docker)

Follow this exact sequence to boot the stack from a clean slate:

```bash
# 1. Build the PHP image (installs redis ext & composer deps layer)
docker compose build

# 2. Start containers (PHP, MySQL, Redis)
docker compose up -d

# 3. Install PHP dependencies inside the running php container
docker compose exec php composer install

# 4. Run database migrations (creates tables)
docker compose exec php php scripts/setup_db.php

# 5. Open the application in your browser
open http://localhost:8080     # public menu
open http://localhost:8080/kitchen  # kitchen view
```

By default the app uses **file-based PHP sessions** (simpler during local dev). To switch to **Redis-backed sessions**:
1. Uncomment the Redis `session.save_handler` block at the top of `public/index.php`.
2. Ensure the `redis` container is running (`docker compose ps`).

To tear everything down and wipe DB/Redis volumes:
```bash
docker compose down -v --rmi local
```

## 4. Application Workflows & Endpoints

| Action         | URL/Route                 | Description                                                    |
|----------------|--------------------------|----------------------------------------------------------------|
| Browse menu    | `/menu`                  | Reads menu from DB, caches in Redis, renders menu view         |
| Add to cart    | `/cart/add?id=42`        | Adds item to cart (session, Redis-backed), shows cart view     |
| Checkout       | `/checkout`              | Creates order, stores items, pushes to Redis queue, clears cart|
| Kitchen view   | `/kitchen`               | Shows queued orders (polled from Redis), kitchen staff view    |
| Mark ready     | `/kitchen/ready?id=99`   | Marks order ready, removes from queue, triggers email via cron |

---

## 7. Project Structure & Migration Talking Points

* **Hot spot:** `menu` reads – perfect candidate for first microservice.  
* **Side effects:** global helpers cause hidden coupling (pricing logic inside `helpers.php`).  
* **Shared DB:** all domains reference the same tables, making data carve‑out a key challenge.

---

## 7. Cleaning Up

```bash
docker compose down -v    # stops containers and removes volumes
```

_Coverage is intentionally minimal to highlight difficulties when migrating legacy code._

---

## 8. License

MIT – for demo and educational use.

*Created June 20, 2025.*
