# Laporan Project: Golden Knead Premium Bakery

## 1. Pendahuluan

### 1.1 Latar Belakang

Golden Knead adalah aplikasi web toko roti premium yang dikembangkan untuk memenuhi kebutuhan bisnis bakery modern. Aplikasi ini dirancang untuk memberikan pengalaman berbelanja yang mudah dan menyenangkan bagi pelanggan, sekaligus menyediakan sistem manajemen yang efisien bagi admin.

### 1.2 Tujuan

- Memudahkan pelanggan dalam memesan produk bakery secara online
- Meningkatkan efisiensi pengelolaan pesanan dan inventori
- Menyediakan platform yang user-friendly untuk admin dan pelanggan
- Mengoptimalkan proses bisnis toko roti

### 1.3 Ruang Lingkup

- Sistem manajemen pesanan
- Sistem manajemen produk
- Sistem manajemen pelanggan
- Dashboard admin
- Interface pelanggan

## 2. Analisis Sistem

### 2.1 Analisis Kebutuhan Fungsional

#### 2.1.1 Kebutuhan Pelanggan

1. Registrasi dan login akun
2. Melihat dan mengedit profil (termasuk foto profil)
3. Melihat katalog produk dengan filter kategori
4. Melakukan pemesanan dengan catatan khusus
5. Melihat riwayat dan detail pesanan
6. Memberikan ulasan dan testimoni produk

#### 2.1.2 Kebutuhan Admin

1. Login ke sistem admin
2. Mengelola status pesanan
3. Melihat detail pesanan pelanggan
4. Mengelola produk dan kategori
5. Melihat data pelanggan
6. Mengelola testimoni pelanggan

### 2.2 Analisis Kebutuhan Non-Fungsional

1. Keamanan sistem (password hashing, validasi input)
2. Performa aplikasi yang optimal
3. User interface yang responsif di berbagai perangkat
4. Kemudahan penggunaan dengan desain intuitif
5. Validasi form dalam bahasa Indonesia

## 3. Perancangan Sistem

### 3.1 Arsitektur Sistem

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Database: MongoDB
- Web Server: XAMPP (Apache)

### 3.2 Desain Database

Struktur Collection MongoDB:

1. Pelanggan

   - \_id
   - nama
   - email
   - password
   - telepon
   - alamat
   - foto
   - tanggal_daftar

2. Produk

   - \_id
   - nama
   - deskripsi
   - harga
   - gambar
   - kategori
   - stok

3. Pesanan

   - \_id
   - pelanggan_id
   - produk_id
   - nama_produk
   - jumlah
   - harga
   - total
   - alamat_pengiriman
   - catatan
   - status
   - tanggal_pesanan

4. Admin

   - \_id
   - username
   - password
   - nama
   - email
   - role

5. Testimoni
   - \_id
   - pelanggan_id
   - nama
   - peran
   - rating
   - testimoni
   - tanggal

### 3.3 Desain Interface

1. Halaman Utama (index.php)

   - Hero section
   - Form login/register modal
   - Deskripsi toko

2. Halaman Pelanggan

   - Katalog produk dengan filter kategori
   - Profil dengan foto
   - Form pemesanan
   - Riwayat pesanan
   - Detail pesanan

3. Halaman Admin
   - Dashboard pesanan
   - Detail pesanan
   - Manajemen status pesanan

## 4. Implementasi

### 4.1 Teknologi yang Digunakan

1. Frontend

   - HTML5
   - CSS3 (Bootstrap 5.3.0)
   - Bootstrap Icons 1.10.0
   - Google Fonts (Playfair Display & Poppins)
   - JavaScript (Bootstrap bundle)

2. Backend

   - PHP 7.4+
   - MongoDB Driver

3. Database

   - MongoDB 4.0+

4. Development Tools
   - XAMPP
   - Visual Studio Code
   - MongoDB Compass

### 4.2 Fitur yang Diimplementasikan

#### 4.2.1 Sistem Autentikasi

- Login/register pelanggan dengan validasi
- Login admin terpisah
- Session management
- Password hashing untuk keamanan
- Validasi form dalam bahasa Indonesia

#### 4.2.2 Manajemen Produk

- Tampilan produk dengan filter kategori
- Detail produk lengkap
- Gambar produk
- Informasi stok
- Harga format Rupiah

#### 4.2.3 Sistem Pemesanan

- Form pemesanan dengan validasi
- Perhitungan total otomatis
- Status pesanan (Menunggu, Diproses, Selesai, Dibatalkan)
- Riwayat pesanan dengan detail
- Alamat pengiriman otomatis dari profil

#### 4.2.4 Manajemen Profil

- Edit profil pelanggan
- Upload foto profil
- Update password opsional
- Edit alamat pengiriman

#### 4.2.5 Sistem Testimoni

- Form ulasan dengan rating bintang
- Tampilan testimoni di halaman utama
- Manajemen testimoni oleh admin
- Rating produk dengan skala 1-5
- Validasi input testimoni

### 4.3 Keamanan Sistem

1. Validasi input di sisi client dan server
2. Sanitasi data untuk mencegah XSS
3. Password hashing menggunakan algoritma modern
4. Proteksi session dan pengecekan login
5. Validasi file upload untuk gambar

## 5. Pengujian

### 5.1 Skenario Pengujian

1. Registrasi dan Login

   - Registrasi dengan data valid
   - Login dengan kredensial benar
   - Login dengan kredensial salah
   - Validasi form bahasa Indonesia

2. Manajemen Profil

   - Update data profil
   - Upload foto profil
   - Ganti password
   - Update alamat

3. Pemesanan

   - Filter produk per kategori
   - Proses pemesanan
   - Input jumlah dan catatan
   - Cek riwayat pesanan

4. Admin Panel

   - Login admin
   - Lihat pesanan
   - Update status pesanan
   - Lihat detail pesanan

5. Testimoni
   - Input testimoni: Berhasil
   - Rating produk: Berhasil
   - Manajemen admin: Berhasil

### 5.2 Hasil Pengujian

1. Fungsionalitas

   - Sistem autentikasi: Berhasil
   - Manajemen profil: Berhasil
   - Sistem pemesanan: Berhasil
   - Panel admin: Berhasil
   - Testimoni: Berhasil

2. Responsivitas

   - Desktop: Optimal
   - Tablet: Responsif
   - Mobile: Responsif

3. Validasi
   - Form validation: Berfungsi
   - File upload: Berfungsi
   - Input sanitization: Berfungsi

## 6. Kesimpulan dan Saran

### 6.1 Kesimpulan

- Aplikasi berhasil diimplementasikan sesuai kebutuhan
- Interface responsif dan user-friendly
- Sistem pemesanan berjalan dengan baik
- Keamanan sistem terjaga
- Manajemen admin efisien
- Testimoni pelanggan berhasil diimplementasikan

### 6.2 Saran Pengembangan

1. Penambahan fitur:

   - Sistem pembayaran online
   - Notifikasi email
   - Chat dengan admin
   - Rating dan review produk
   - Sistem testimoni pelanggan

2. Peningkatan sistem:
   - Optimasi performa
   - Backup otomatis
   - Laporan penjualan detail
   - Sistem reward pelanggan

## 7. Dokumentasi

### 7.1 Struktur Folder

```
Golden_Knead/
├── admin/                 # Admin panel files
│   ├── components/       # Admin components
│   ├── dashboard.php
│   ├── detail_pesanan.php
│   ├── proses_update_status.php
│   ├── testimoni.php
│   ├── proses_tambah_testimoni.php
│   ├── proses_hapus_testimoni.php
│   ├── edit_testimoni.php
│   ├── proses_edit_testimoni.php
│   └── tambah_admin.php
├── assets/               # Static assets
│   ├── css/             # Stylesheet files
│   └── images/          # Image files
├── components/           # Shared components
├── user/                 # User panel files
│   ├── beranda.php
│   ├── detail_pesanan.php
│   ├── edit_profil.php
│   ├── pesan.php
│   ├── produk.php
│   ├── profil.php
│   ├── riwayat.php
│   ├── ulasan.php
│   └── proses_ulasan.php
├── index.php            # Landing page
├── koneksi.php          # Database connection
└── LAPORAN_PROJECT.md   # Documentation
```

### 7.2 Panduan Instalasi

1. Persiapan Sistem:

   - Install XAMPP
   - Install MongoDB
   - Install MongoDB PHP Driver
   - Aktifkan extension MongoDB di php.ini

2. Instalasi Aplikasi:
   - Clone repository ke htdocs
   - Import database MongoDB
   - Konfigurasi koneksi di koneksi.php
   - Buat admin menggunakan tambah_admin.php

### 7.3 Panduan Penggunaan

1. Panduan Admin:

   - Login melalui /admin
   - Lihat daftar pesanan di dashboard
   - Klik detail untuk informasi lengkap
   - Update status pesanan sesuai progress

2. Panduan Pelanggan:
   - Register/login di halaman utama
   - Browse produk dengan filter kategori
   - Klik pesan untuk membuat pesanan
   - Cek status di riwayat pesanan
   - Update profil jika diperlukan

## 8. Referensi

1. Dokumentasi PHP: https://www.php.net/docs.php
2. Dokumentasi MongoDB: https://docs.mongodb.com/
3. Dokumentasi Bootstrap 5: https://getbootstrap.com/docs/5.3/
4. Bootstrap Icons: https://icons.getbootstrap.com/
5. Google Fonts: https://fonts.google.com/
