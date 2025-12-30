# PT. Smart CRM
Customer Relationship Management (CRM) untuk PT. Smart - Perusahaan Penyedia Layanan Internet.

## Deskripsi
Aplikasi CRM ini digunakan untuk mengelola proses bisnis dari lead (calon customer) hingga menjadi customer aktif. Sistem memiliki approval project oleh manager sebelum lead menjadi customer.

## Fitur Utama

### Manajemen Lead
- Input dan mengatur data calon customer
- Tracking status lead (New, Contacted, Qualified, Unqualified)
- Pencarian dan filter lead

### Manajemen Produk
- Mengatur produk layanan internet (Fiber, Wireless, Dedicated)
- Aktivasi/nonaktifkan produk
- Informasi harga dan kecepatan

### Manajemen Project
- Sales membuat project dari lead dengan memilih produk
- Sistem approval oleh Manager
- Tracking status project (Draft, Pending Approval, Approved, Rejected, Completed)

### Manajemen Customer
- Data customer otomatis dibuat setelah project disetujui
- Tracking langganan produk per customer
- Status customer (Active, Inactive, Suspended)

### Manajemen User
- Admin dapat membuat, edit, dan hapus user Manager/Sales
- Role-based access control

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/HansAnthonyC/hans_crm.git
cd hans_crm
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=hans_crm
DB_USERNAME=username_anda
DB_PASSWORD=password_anda
```

### 4. Migrasi Database

```bash
php artisan migrate:fresh --seed
```

### 5. Build Assets

```bash
npm run build
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

## Akun Default

Admin = admin@ptsmart.com - password
Manager = manager@ptsmart.com - password
Sales = sales@ptsmart.com - password

## Struktur Role

### Admin
- Akses penuh ke semua fitur
- Dapat mengelola user (Manager & Sales)
- Dapat approve/reject project

### Manager
- Dapat approve/reject project
- Tidak dapat membuat project baru
- Akses penuh ke leads, products, customers

### Sales
- Dapat membuat lead dan project
- Tidak dapat approve project
- Akses read-only ke customers

## Alur Kerja (Workflow)

```
Lead (Sales) → Project (Sales) → Approval (Manager) → Customer (Auto)
```

1. Sales menginput data lead baru
2. Sales membuat project dengan memilih produk untuk lead
3. Sales mengajukan project untuk approval
4. Manager mereview dan approve/reject project
5. Jika disetujui, customer otomatis dibuat dari project

## Struktur Folder

```
hans_crm/
├── app/
│   ├── Http/Controllers/     # Controller aplikasi
│   ├── Models/               # Model Eloquent
│   └── View/Components/      # Blade components
├── database/
│   ├── migrations/           # File migrasi database
│   └── seeders/              # Data awal
├── resources/
│   └── views/                # Template Blade
├── routes/
│   └── web.php               # Routing aplikasi
├── lampiran/
│   ├── erd_pt_smart_crm.drawio   # ERD diagram
│   ├── database_schema.sql       # SQL schema database
│   └── data_dictionary.md        # Data dictionary
└── README.md
```

## Lampiran Dokumentasi
Dokumentasi teknis tersedia di folder `lampiran/`:

`erd_pt_smart_crm.drawio` = Entity Relationship Diagram, buka dengan Draw.io
`database_schema.sql` = SQL schema untuk membuat tabel database
`data_dictionary.md` = Penjelasan setiap kolom tabel

## Timeline Pengerjaan

- 29 Des 2025 20:00 = Membuat desain database (ERD di Draw.io)
- 29 Des 2025 21:00 = Setup project Laravel dan PostgreSQL
- 29 Des 2025 21:30 = Membuat migration, models dan seeder
- 30 Des 2025 09:00 = Membuat sistem login dan middleware
- 30 Des 2025 11:00 = Membuat CRUD untuk leads, products
- 30 Des 2025 15:00 = Membuat fitur approval project dan modul customer
- 30 Des 2025 18:00 = Merapikan UI
- 30 Des 2025 20:00 = Technical Testing
- 30 Des 2025 21:00 = Membuat dokumentasi

## Teknologi yang Digunakan
- **Backend:** Laravel 11
- **Frontend:** Blade, Tailwind CSS
- **Database:** PostgreSQL
- **Authentication:** Laravel Breeze
