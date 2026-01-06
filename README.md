# Simple Online Store — Multi Management System

Laravel 12 · PostgreSQL · E-commerce · RBAC · Manual Payment Verification

Repository ini adalah template e-commerce terstruktur untuk usaha kecil-menengah yang membutuhkan alur multi-role (Customer, Admin, Customer Service), verifikasi pembayaran manual dua-lapisan, dan optimasi untuk PostgreSQL.

Klik ⭐ jika Anda menyukai project ini

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

```mermaid
flowchart TD
    subgraph Client
        A["User Browser / Mobile"]
    end

    subgraph WebApp[Laravel App]
        B["Routes"] --> C["Controllers"]
        C --> D["Services / Jobs"]
        C --> E["Views (Blade + Tailwind)"]
        D --> F[("PostgreSQL DB")]
        D --> G["Storage / Filesystem"]
    end

    A -->|HTTP/HTTPS| B
    F ---|sessions| H[("sessions table")]

    classDef db fill:#f9f,stroke:#333,stroke-width:1px
    class F,H db
```
 
---

## Diagram: Cara kerja toko (business flow)

Berikut beberapa diagram yang menjelaskan proses bisnis toko — fokus pada alur order, verifikasi pembayaran, struktur data utama, dan contoh statistik operasional. Diagram ini membantu developer dan pemilik bisnis memahami bagaimana request pengguna mengalir sampai pesanan diproses.

### 1) Order lifecycle (flowchart)

```mermaid
flowchart LR
  Browse["Browse & Search Produk"] --> AddCart["Tambah ke Keranjang"]
  AddCart --> Checkout["Checkout & Pilih Metode Pembayaran"]
  Checkout --> UploadProof["Upload Bukti Pembayaran"]
  UploadProof --> OrderPending["Order: Pending / Menunggu Verifikasi"]

  OrderPending --> CS1Review["CS Layer 1: Verifikasi Bukti"]
  CS1Review -->|Approve| CS2Process["CS Layer 2: Proses Pengiriman"]
  CS1Review -->|Reject| Rejected["Pembayaran Ditolak / Minta Ulang Bukti"]

  CS2Process --> Shipped["Dikirim (Resi input)"]
  Shipped --> Delivered["Terkirim / Selesai"]

  Rejected --> NotifyCustomer["Notifikasi ke Customer (minta bukti ulang)"]
  NotifyCustomer --> UploadProof

  classDef userAction fill:#e3f2fd,stroke:#333,stroke-width:1px
  class Browse,AddCart,Checkout,UploadProof userAction
```

Keterangan singkat:
- Setelah upload bukti, order berada pada status pending sampai CS layer 1 memverifikasi.
- Jika terverifikasi, proses pengiriman dilakukan oleh CS layer 2; jika ditolak, customer diminta meng-upload ulang bukti.

### 2) Verifikasi pembayaran (sequence diagram)

```mermaid
sequenceDiagram
    participant C as Customer
    participant F as Frontend
    participant S as Server (Laravel)
    participant DB as Database
    participant CS as CS (layer1)

    C->>F: Upload bukti pembayaran
    F->>S: POST /checkout/payment-proof
    S->>DB: simpan PaymentProof (order_id, file, metadata)
    Note right of S: Order status -> "awaiting_verification"
    CS->>S: Ambil daftar proof untuk review
    CS->>S: Update status (approve/reject)
    S->>C: Kirim notifikasi hasil verifikasi
```

Penjelasan:
- Sequence menunjukkan langkah teknis bisnis (apa yang dilakukan pengguna dan CS) tanpa membahas detail framework.

### 3) Struktur data utama (ER diagram, ringkas)

```mermaid
erDiagram
    USERS ||--o{ ORDERS : places
    ORDERS ||--|{ ORDER_ITEMS : contains
    PRODUCTS ||--o{ ORDER_ITEMS : "included_in"
    USERS ||--o{ PAYMENT_PROOFS : submits
    ORDERS ||--o{ PAYMENT_PROOFS : "has"
    USERS ||--o{ CARTS : owns
    CARTS ||--o{ CART_ITEMS : contains
```

Catatan: ER diagram di atas adalah ringkasan; cek folder `database/migrations` untuk skema kolom lengkap.

### 4) Contoh statistik operasional (pie chart)

```mermaid
pie title Distribusi status pesanan (contoh)
    "Pending / Awaiting Verification" : 25
    "Awaiting CS2 / Processing" : 15
    "Approved" : 40
    "Shipped" : 15
    "Delivered" : 5
```

Penjelasan:
- Statistik di atas hanyalah contoh ilustratif. Untuk statistik nyata, hubungkan aplikasi dengan analytics (database query, Prometheus, atau BI tools) dan tampilkan angka aktual.

---

Jika Anda ingin, saya bisa:
- Mengganti angka pada pie chart dengan nilai nyata dari database (saya bisa tuliskan query contoh untuk PostgreSQL).
- Menambahkan diagram swimlane untuk memperlihatkan tanggung jawab (Customer / CS1 / CS2 / Admin).
- Menghasilkan PNG/SVG dari mermaid diagram dan menaruhnya di repo (`docs/` atau `assets/`) agar tampil langsung di GitHub (mermaid di README kadang perlu plugin atau pihak ketiga untuk render secara visual di preview).



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
