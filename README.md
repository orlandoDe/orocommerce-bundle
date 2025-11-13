# OroCommerce + Custom Bundle [PDP Qty] (Docker Dev Environment)
<img width="576" height="158" alt="image" src="https://github.com/user-attachments/assets/d5027a84-028c-4bf2-a219-11e7a3caae4c" />


This repository provides a painless ready-to-use **OroCommerce development environment** with sample data and a custom bundle integrated, using **Docker** and **PHP 8.4**.

---

## ⚙️ Prerequisites

Make sure you have the following installed on your machine:

- Docker & Docker Compose v2+ WSL
- Git
- At least **8GB RAM** allocated to Docker (OroCommerce needs a lot of memory)
- Node.js 18+ (optional, for local asset build debugging)

---

##  Installation Steps

### **Step 1: Clone OroCommerce**

```bash
git clone https://github.com/oroinc/orocommerce-application.git
cd orocommerce-application
```

---

### **Step 2: Add your custom bundle**

```bash
git clone https://github.com/orlandoDe/orocommerce-bundle.git
cd orocommerce-bundle
chmod +x update-orocommerce.sh
./update-orocommerce.sh
```

> 🧠 This script copies and overrides OroCommerce files (like `docker-compose.yml`, `Dockerfile`, and config changes) with your custom development setup.

---

### **Step 3: Build and run the Docker environment**

```bash
cd ..
docker compose build
docker compose up -d
docker compose exec php bash
git config --global --add safe.directory /var/www/html
```

This will start:
- PHP 8.4 (FPM)
- Nginx
- PostgreSQL 17
- Redis
- Mailcatcher
- Gotenberg (PDF service)

---

### **Step 4: Increase PHP memory limit**

Inside the PHP container:

```bash
echo 'memory_limit = 4024M' > /usr/local/etc/php/conf.d/zz-memory-limit.ini
```

---

### **Step 5: Prepare dependencies**

Still inside the PHP container:

```bash
rm -f composer.lock
composer clear-cache
composer update --no-interaction --prefer-dist --optimize-autoloader
```

---

### **Step 6: Install OroCommerce**

Run the Oro installer:

```bash
php bin/console oro:install --env=dev     --timeout=900     --language=en     --formatting-code=en_US     --organization-name='AAXIS Test'     --user-name=admin     --user-email=admin@example.com     --user-firstname=Admin     --user-lastname=User     --user-password=admin     --application-url='http://localhost'
```

When prompted:
```
Load sample data (y/n): y
```

---

### **Step 7: Fix permissions**

```bash
chown -R www-data:www-data /var/www/html/var
chmod -R 775 /var/www/html/var
chmod -R 777 public/media var/data
```

---

### **Step 8: Build and link assets (for development)**

```bash
php bin/console cache:clear --env=dev
php bin/console oro:assets:install --env=dev --symlink
php -d memory_limit=-1 bin/console oro:assets:build --env=dev
```

---
##  Installation Steps in a already running Orocommerce
---

### **Installation instructions for running the bundle in another environment.**

```bash
composer clear-cache
composer config repositories.orocommerce-bundle git https://github.com/orlandoDe/oro-pdp-qty-bundle.git
composer require orlandode/orocommerce-stockpdp-bundle

php bin/console cache:clear --env=dev
```

---
## Misc
---

## 🖼️ Fix missing product images (optional)

If sample product images don’t appear, regenerate attachments:

```bash
php bin/console oro:attachment:sync --env=dev
```

---
## Test
---
Then reload your site at:

👉 [http://localhost:8080](http://localhost:8080)

Test the module -> [http://localhost:8080/lighting-products/_item/industrial-steel-handheld-flashlight](http://localhost:8080/lighting-products/_item/industrial-steel-handheld-flashlight)

---

## 🧠 Useful Commands

| Command | Description |
|----------|-------------|
| `docker compose exec php bash` | Access PHP container |
| `docker compose down -v` | Stop and remove containers, networks, and volumes |
| `php bin/console cache:clear --env=dev` | Clear application cache |
| `php bin/console oro:assets:build --env=dev` | Rebuild frontend assets |
| `php bin/console oro:attachment:sync --env=dev` | Fix missing product images |

---

## 🧱 Folder Structure (after setup)

```
orocommerce-application/
│
├── docker/                    # Docker configs (PHP, Nginx, etc.)
├── src/                       # Application bundles
├── public/                    # Public assets
├── var/                       # Cache & logs
├── docker-compose.yml
├── .env-app.local
└── orocommerce-bundle/        # Your custom bundle
```

---

## ✅ Access

- **Frontend:** [http://localhost:8080](http://localhost:8080)
- **Backoffice:** [http://localhost:8080/admin](http://localhost:8080/admin)
(user/pass: admin)


---

## 🧹 Reset Everything

To wipe your local environment completely and start fresh:

```bash
docker compose down -v
git clean -fdx
```

Then repeat from **Step 1**.

---

### 🧩 Credits

Based on:
- [OroCommerce](https://github.com/oroinc/orocommerce-application)
- Custom bundle by [@orlandoDe](https://github.com/orlandoDe)
