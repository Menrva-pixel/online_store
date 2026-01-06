# Simple Online Store — Multi Management System
Laravel 12 · PostgreSQL · Multi-Role Workflow

Template e-commerce sederhana dan terstruktur dengan dukungan multi-role,
serta alur verifikasi pembayaran manual. Cocok untuk toko online skala kecil–menengah.

Gratis digunakan.

---

## ✨ Fitur Utama

- Arsitektur Laravel bersih & terstruktur
# Simple Online Store — Multi Management System

Laravel 12 · PostgreSQL · E-commerce · RBAC · Manual Payment Verification

Repository ini adalah template e-commerce terstruktur untuk usaha kecil-menengah yang membutuhkan alur multi-role (Customer, Admin, Customer Service), verifikasi pembayaran manual dua-lapisan, dan optimasi untuk PostgreSQL.

Klik ⭐ jika Anda menyukai project ini — klik stars jika menyukai project ini

---

## Ringkasan singkat 

Simple Online Store adalah boilerplate e-commerce berbasis Laravel 12 yang siap produksi: katalog produk, keranjang, checkout dengan upload bukti pembayaran, manajemen pesanan, dan alur verifikasi pembayaran dua-level. Cocok untuk merchant yang ingin cepat deploy toko online dengan alur verifikasi manual.

Keywords: Laravel 12, online store, e-commerce, PostgreSQL, RBAC, pembayaran manual, verifikasi pembayaran, Laravel starter.

---

## Fitur Utama

- Arsitektur Laravel yang rapi dan modular
- Role-Based Access Control (Customer, Admin, CS layer 1, CS layer 2)
- Alur checkout + upload bukti pembayaran (manual approval)
- Manajemen produk: CRUD, import dari Excel
- Optimasi PostgreSQL untuk performa dan indexing
- Penanganan order, pengiriman, dan pelacakan status pesanan
- Storage terhubung (disk publik) dan helper untuk download/upload

---

## Alur dan Arsitektur

Berikut diagram arsitektur dan alur utama (mermaid):

```mermaid
flowchart TD
	subgraph Client
		A[User Browser / Mobile]
	end

	subgraph WebApp[Laravel App]
		B[Routes] --> C[Controllers]
		C --> D[Services / Jobs]
		C --> E[Views (Blade + Tailwind)]
		D --> F[(PostgreSQL DB)]
		D --> G[Storage / Filesystem]
	end

	A -->|HTTP/HTTPS| B
	F ---|sessions| H[(sessions table)]

	classDef db fill:#f9f,stroke:#333,stroke-width:1px
	class F,H db
```

Sequence flow (simplified):

1. User menelusuri produk dan menambah ke keranjang.
2. Saat checkout, user mengupload bukti pembayaran.
3. CS layer 1 melakukan verifikasi bukti (approve/reject).
4. Jika approve, CS layer 2 memproses pengiriman (input nomor resi, update status).

---

## Struktur Proyek (ringkasan)

- `app/Models` — Eloquent models (User, Product, Cart, Order, OrderItem, PaymentProof)
- `app/Http/Controllers` — Controller untuk web flow
- `database/migrations` — Migration untuk skema DB
- `resources/views` — Blade templates dan partials
- `public/` — asset front controller

---

## Instalasi & Setup (Windows - PowerShell)

Prasyarat:
- PHP >= 8.1
- Composer
- PostgreSQL
- Node >= 16

Langkah singkat (dijalankan di root project yang berisi file `artisan`):

```powershell
# Install PHP dependencies
composer install

# Install JS dependencies dan build assets
npm install
npm run build

# Salin environment dan buat app key
copy .env.example .env
php artisan key:generate

# Sesuaikan .env untuk koneksi database PostgreSQL (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# Jalankan migration (termasuk tabel sessions jika SESSION_DRIVER=database)
php artisan migrate

# (Opsional) Seed data
php artisan db:seed

# Link storage
php artisan storage:link

# Jalankan server pengembangan
php artisan serve
```