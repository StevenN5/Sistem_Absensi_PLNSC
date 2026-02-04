# Attendance Management System

Aplikasi manajemen absensi berbasis web dengan Laravel. Menyediakan dashboard admin dan pengguna untuk mengelola pegawai, jadwal, absensi, serta laporan.

## Fitur Utama
- Dashboard admin dengan ringkasan absensi
- Manajemen pegawai (tambah, ubah, hapus)
- Manajemen jadwal (jam masuk/pulang)
- Log absensi dengan status (tepat waktu, terlambat, terlalu cepat)
- Daftar keterlambatan
- Daftar pulang awal/izin
- Daftar lembur
- Lembar absensi (tampilan matriks harian/bulanan)
- Ekspor sheet report per pengguna dan semua pengguna
- Upload/unduh laporan bulanan dan final
- Manajemen perangkat finger (list/tambah/ubah/detail)
- Profil pengguna dan riwayat absensi pengguna

## Bahasa Pemrograman & Teknologi
- PHP (Laravel)
- JavaScript (jQuery, DataTables)
- HTML (Blade Template)
- CSS (Bootstrap)

## Menjalankan (Local)
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Run
```bash
php artisan serve
```

## Demo Statis (GitHub Pages)
Repo ini menyediakan exporter demo statis:
```bash
php artisan serve --host 127.0.0.1 --port 8000
powershell -ExecutionPolicy Bypass -File .\scripts\export-demo.ps1
```
Publish folder `docs/` lewat GitHub Pages.

## Lisensi
MIT
