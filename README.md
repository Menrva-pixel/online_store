# 🛍️ Toko Online Sederhana - Laravel & PostgreSQL

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?style=for-the-badge&logo=postgresql)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)

Aplikasi e-commerce dengan sistem multi-role (Customer, Admin, CS1, CS2) dan workflow terstruktur untuk verifikasi pembayaran manual.

## Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Instalasi](#-instalasi-cepat)
- [Akun Default](#-akun-default)
- [Workflow Sistem](#-workflow-sistem)
- [Struktur Database](#-struktur-database)
- [API Endpoints](#-api-endpoints)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)

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


