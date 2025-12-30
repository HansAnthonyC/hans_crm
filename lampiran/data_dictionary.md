# Data Dictionary - PT Smart CRM

## 1. Tabel users
Menyimpan data pengguna sistem.

id = ID unik pengguna
name = Nama lengkap pengguna
email = Email untuk login
email_verified_at = Waktu verifikasi email
password = Password yang sudah encrypted
role = Role: admin, manager, sales
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

## 2. Tabel leads
Menyimpan data calon customer.

id = ID unik lead
company_name = Nama perusahaan
contact_person = Nama kontak person
e mail = Email perusahaan
phone = Nomor telepon
address = Alamat lengkap
status = Status: new, contacted, qualified, unqualified
created_by = ID user yang membuat
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

Status Lead:
new = Lead baru
contacted = Sudah dihubungi
qualified = Memenuhi syarat untuk menjadi customer
unqualified = Tidak memenuhi syarat

## 3. Tabel products
Menyimpan data produk layanan internet.

id = ID unik produk
name = Nama produk
code = Kode produk unik
description = Deskripsi produk
price = Harga per bulan
type = Tipe: fiber, wireless, dedicated
speed = Kecepatan internet
is_active = Status aktif produk
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

Tipe Produk:
fiber = Fiber Optic
wireless = Wireless/Radio
dedicated = Dedicated Line

## 4. Tabel projects
Menyimpan data project penawaran ke lead.

id = ID unik project
project_number = Nomor project (format: PRJ-YYYYMMDD-XXX)
lead_id = ID lead yang berkaitan
created_by = ID user yang membuat (sales)
approved_by = ID user yang approve (manager)
status = Status project
rejection_reason = Alasan penolakan
approved_at = Waktu approval/reject
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

Status Project:
draft = Draf, belum diajukan
pending_approval = Menunggu persetujuan manager
approved = Disetujui
rejected = Ditolak
completed = Selesai, customer sudah dibuat

## 5. Tabel project_products
Tabel pivot untuk relasi many-to-many antara projects dan products.

id = ID unik
project_id = ID project
product_id = ID produk
quantity = Jumlah unit
price = Harga saat project dibuat
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

## 6. Tabel customers
Menyimpan data customer aktif.

id = ID unik customer
project_id = ID project asal
customer_number = Nomor customer (format: CUST-YYYYMMDD-XXX)
company_name = Nama perusahaan
contact_person = Nama kontak person
e mail = Email perusahaan
phone = Nomor telepon
address = Alamat lengkap
status = Status: active, inactive, suspended
subscription_start = Tanggal mulai berlangganan
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

Status Customer:
active = Customer aktif berlangganan
inactive = Customer tidak aktif
suspended = Customer ditangguhkan

## 7. Tabel customer_products
Tabel pivot untuk relasi many-to-many antara customers dan products.

id = ID unik
customer_id = ID customer
product_id = ID produk
monthly_price = Harga bulanan
start_date = Tanggal mulai berlangganan produk
end_date = Tanggal berakhir (NULL jika masih aktif)
status = Status: active, inactive
created_at = Waktu data dibuat
updated_at = Waktu data diupdate

**Keterangan:**
- Satu user dapat membuat banyak leads
- Satu lead hanya memiliki satu project
- Satu project menghasilkan satu customer (jika diapprove)
- Projects dan products memiliki relasi many-to-many
- Customers dan products memiliki relasi many-to-many
