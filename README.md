#  Simple Online Store Multi Management System
**Laravel 12 · PostgreSQL · Multi-Role Workflow**

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?style=for-the-badge&logo=postgresql)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)

A **simple yet structured e-commerce application** built using **Laravel** and **PostgreSQL**, featuring **multi-role access control** and a **manual payment verification workflow** designed for small to medium-scale online stores.

---

##  Key Features
- Clean Laravel 10+ architecture
- Role-Based Access Control (RBAC)
- Manual payment verification (2-layer CS)
- PostgreSQL optimized database schema
- Excel import/export support
- Production-ready deployment setup

---

##  User Roles & Capabilities

###  Customer
- Browse & search products
- Manage shopping cart
- Checkout with multiple payment methods
- Upload payment proof
- Track order status
- Cancel order (before processing)

###  Admin
- Full product CRUD
- Bulk product import via Excel
- Download Excel templates
- User & role management
- Monitor all orders

###  Customer Service – Layer 1
- Verify payment proofs
- Approve / reject payments
- Notify customers
- View pending payment dashboard

###  Customer Service – Layer 2
- Process approved orders
- Input tracking numbers
- Generate packing slips
- Update shipping status
- Complete orders

---

##  Tech Stack

| Layer | Technology |
|------|-----------|
| Backend | Laravel 12 |
| Frontend | Blade + TailwindCSS +alpineJs |
| Database | PostgreSQL |
| Authentication | Laravel Auth |
| File Storage | Laravel Storage |
| Import/Export | phpoffice/phpspreadsheet |

---

##  Quick Installation

### Prerequisites
- PHP ≥ 8.1
- Composer
- PostgreSQL ≥ 12
- Node.js ≥ 16

### Installation Steps
```bash
# Clone repository
git clone https://github.com/Menrva-pixel/online_store.git
cd online-store

# Install backend & frontend dependencies
composer install
npm install
npm run build

# Setup environment
cp .env.example .env

# Generate key & migrate database
php artisan key:generate
php artisan migrate --seed

# Storage symlink
php artisan storage:link

# Run development server
php artisan serve
```

## .ENV CONFIG ( SESUAIKAN DENGAN KONFIGURASI LOKAL / DB ANDA SENDIRI)

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=toko_online
DB_USERNAME=postgres
DB_PASSWORD=password (password db anda)

APP_URL=http://localhost:8000


### Deployment

## 1.Setup Server (Ubuntu)
### Update & install dependencies
```
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-pgsql php8.1-mbstring php8.1-xml
sudo apt install postgresql postgresql-contrib nginx
sudo apt install git curl composer nodejs npm
```
## 2. Clone & Setup Project
```
cd /var/www
sudo git clone [repository-url] toko-online
cd toko-online

sudo composer install --no-dev --optimize-autoloader
sudo npm install --production
sudo npm run build

sudo cp .env.example .env
sudo nano .env  # Edit konfigurasi database
```
## 3.Konfigurasi Nginx
```
sudo nano /etc/nginx/sites-available/toko-online
```
isi dengan :
```
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/toko-online/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```
lalu ketik perintah :
```
sudo ln -s /etc/nginx/sites-available/toko-online /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```
## 4. Setup Database PostgreSQL
```
sudo -u postgres psql
CREATE DATABASE toko_online;
CREATE USER laravel_user WITH PASSWORD 'password';
GRANT ALL PRIVILEGES ON DATABASE toko_online TO laravel_user;
\q
```
## 5.Setup Permission & Scheduler
```
sudo chown -R www-data:www-data /var/www/toko-online
sudo chmod -R 755 /var/www/toko-online
sudo chmod -R 775 /var/www/toko-online/storage

# Setup crontab untuk scheduler
sudo crontab -u www-data -e
```
Tambahkan :
```
* * * * * cd /var/www/toko-online && php artisan schedule:run >> /dev/null 2>&1
```
## 6. Optimisasi Produksi
```
cd /var/www/toko-online
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
## 7. SSL (Opsional)
```
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```



