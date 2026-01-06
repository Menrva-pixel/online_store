# Simple Online Store — Multi Management System
Laravel 12 · PostgreSQL · Multi-Role Workflow

Template e-commerce sederhana dan terstruktur dengan dukungan multi-role,
serta alur verifikasi pembayaran manual. Cocok untuk toko online skala kecil–menengah.

Gratis digunakan.

---

## ✨ Fitur Utama

- Arsitektur Laravel bersih & terstruktur
- Role-Based Access Control (RBAC)
- Verifikasi pembayaran manual (2 layer CS)
- Optimasi PostgreSQL
- Import / Export Excel
- Siap deployment ke production

---

## 👥 Role & Hak Akses

### Customer
- Lihat & cari produk  
- Kelola keranjang  
- Checkout + upload bukti pembayaran  
- Lacak status pesanan  
- Batalkan pesanan (sebelum diproses)

### Admin
- CRUD produk lengkap  
- Import produk dari Excel  
- Download template Excel  
- Kelola user & role  
- Monitoring semua pesanan

### Customer Service — Layer 1
- Verifikasi bukti pembayaran  
- Approve / reject  
- Kirim notifikasi ke customer  
- Dashboard pembayaran pending

### Customer Service — Layer 2
- Proses pesanan yang sudah approve  
- Input nomor resi  
- Generate packing slip  
- Update status pengiriman  
- Selesaikan pesanan

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|------|-----------|
| Backend | Laravel 12 |
| Frontend | Blade + TailwindCSS + Alpine.js |
| Database | PostgreSQL |
| Auth | Laravel Auth |
| Storage | Laravel Storage |
| Import/Export | phpoffice/phpspreadsheet |

---

## ⚙️ Instalasi

### Prasyarat
- PHP ≥ 8.1  
- Composer  
- PostgreSQL ≥ 12  
- Node.js ≥ 16  

### Langkah Instalasi

```bash
git clone https://github.com/Menrva-pixel/online_store.git
cd online_store

composer install
npm install
npm run build

cp .env.example .env

php artisan key:generate
php artisan migrate
php artisan migrate --seed

php artisan storage:link
php artisan serve