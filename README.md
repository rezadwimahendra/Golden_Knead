# Golden Knead Premium Bakery

Aplikasi web toko roti premium yang dikembangkan menggunakan PHP dan MongoDB.

## Fitur

- Sistem autentikasi pelanggan dan admin
- Manajemen produk roti
- Sistem pemesanan
- Manajemen profil pelanggan
- Sistem ulasan dan testimoni
- Dashboard admin
- Desain responsif

## Teknologi yang Digunakan

- PHP 7.4+
- MongoDB 4.0+
- Bootstrap 5.3.0
- Bootstrap Icons 1.10.0
- Google Fonts (Playfair Display & Poppins)

## Persyaratan Sistem

- XAMPP (Apache, PHP)
- MongoDB
- MongoDB PHP Driver
- Composer

## Cara Instalasi

1. Salin repositori ini:

```bash
git clone https://github.com/rezadwimahendra13/Golden_Knead.git
```

2. Masuk ke direktori proyek:

```bash
cd Golden_Knead
```

3. Pasang dependensi menggunakan Composer:

```bash
composer install
```

4. Import database MongoDB:

   - Buat database baru bernama `golden_knead`
   - Import koleksi yang diperlukan

5. Atur koneksi database:

   - Buka berkas `koneksi.php`
   - Sesuaikan pengaturan MongoDB jika diperlukan

6. Buat admin default:
   - Akses `admin/tambah_admin.php` melalui peramban
   - Masuk menggunakan kredensial default:
     - Email: admin@goldenknead.com
     - Kata Sandi: admin123

## Struktur Folder

```
Golden_Knead/
├── admin/                 # Berkas panel admin
├── assets/               # Aset statis
│   ├── css/             # Berkas stylesheet
│   └── images/          # Berkas gambar
├── components/          # Komponen bersama
├── user/                # Berkas panel pengguna
├── vendor/              # Dependensi Composer
├── .gitignore
├── composer.json
├── index.php           # Halaman utama
├── koneksi.php         # Koneksi database
└── README.md          # Dokumentasi
```

## Fitur Admin

- Dashboard dengan statistik
- Manajemen pesanan
- Manajemen produk
- Manajemen pelanggan
- Manajemen testimoni

## Fitur Pelanggan

- Pendaftaran dan masuk
- Melihat dan memesan produk
- Manajemen profil
- Riwayat pesanan
- Memberikan ulasan

## Cara Berkontribusi

1. Fork repositori
2. Buat cabang baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Menambahkan fitur baru'`)
4. Push ke cabang (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

[Lisensi MIT](LICENSE)

## Kontak

- Surel: info@goldenknead.com
- Situs Web: https://goldenknead.com
