# 🛍️ Toko Online Sederhana - Laravel & PostgreSQL

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?style=for-the-badge&logo=postgresql)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)

Aplikasi e-commerce dengan sistem multi-role (Customer, Admin, CS1, CS2) dan workflow terstruktur untuk verifikasi pembayaran manual.

## Fitur Utama

### **Customer**
- ✅ Browse & search produk
- ✅ Keranjang belanja
- ✅ Checkout dengan 3 metode bayar
- ✅ Upload bukti pembayaran
- ✅ Track order status
- ✅ Batalkan order

### **Admin**
- ✅ CRUD produk lengkap
- ✅ Import produk massal via Excel
- ✅ Download template Excel
- ✅ Manajemen user & role
- ✅ Monitoring semua order

### **CS Layer 1**
- ✅ Verifikasi bukti pembayaran
- ✅ Approve/Reject payment
- ✅ Konfirmasi ke customer
- ✅ Dashboard pending payments

### **CS Layer 2**
- ✅ Proses order
- ✅ Input tracking number
- ✅ Generate packing slip
- ✅ Update status pengiriman
- ✅ Complete order

## Instalasi Cepat

### Prasyarat
- PHP 8.1+
- Composer
- PostgreSQL 12+
- Node.js 16+

### Langkah Instalasi

```bash
# 1. Clone repository
git clone [online_store](https://github.com/Menrva-pixel/online_store)
cd online-store

# 2. Install dependencies
composer install
npm install
npm run build

# 3. Setup environment
cp .env.example .env
# Edit .env file dengan konfigurasi database

# 4. Generate key & migrasi
php artisan key:generate
php artisan migrate --seed

# 5. Setup storage
php artisan storage:link

# 6. Jalankan server
php artisan serve
```
## .ENV CONFIG
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=toko_online
DB_USERNAME=postgres
DB_PASSWORD=password

APP_URL=http://localhost:8000
```

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



