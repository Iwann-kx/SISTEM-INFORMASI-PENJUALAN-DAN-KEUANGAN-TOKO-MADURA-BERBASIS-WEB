# Sistem Informasi Penjualan dan Keuangan Toko Madura
Aplikasi web Laravel untuk mengelola stok barang, supplier, penjualan, pembelian, dan laporan transaksi. Nama tabel serta kolom bisnis mengikuti database aplikasi desktop lama.

## Database Project

Koneksi default `.env` menggunakan database `sistem_informasi_penjualan_dan_keuangan_toko_madura_berbasis_web`. Database ini dibuat khusus untuk project Laravel dan tidak mengubah database lama `warung_sembako`.

## Menggunakan Database Lama
1. Jalankan MySQL dari Laragon.
2. Pastikan database `warung_sembako` sudah tersedia dan berisi tabel `barang`, `supplier`, `penjualan`, `penjualan_detail`, `pembelian`, dan `pembelian_detail`.
3. Konfigurasi `.env` diarahkan ke `127.0.0.1:3306`, database `warung_sembako`, user `root`, dan password kosong seperti konfigurasi lama. Sesuaikan nilainya bila MySQL Anda berbeda.
4. Jalankan `php artisan serve`, lalu buka `http://127.0.0.1:8000`.

Jangan jalankan `php artisan migrate` atau `php artisan migrate:fresh` pada database lama. Tabel bisnis sudah ada dan migration project baru belum tercatat pada tabel migration database tersebut; gunakan database lama tanpa menjalankan migration.

Jika database lama belum ada, impor `warung_sembako.sql` dari folder project desktop melalui phpMyAdmin, atau buat database kosong bernama `warung_sembako` lalu ikuti langkah instalasi baru.

## Database Baru
Salin `.env.example` menjadi `.env`, sesuaikan koneksi MySQL, lalu jalankan:

```powershell
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Seeder menambahkan sepuluh barang dan satu supplier contoh hanya jika kode barang/supplier tersebut belum ada; nilai data yang sudah tersimpan tidak ditimpa.
## Fitur

- Dashboard penjualan, pembelian, selisih harian, tren tujuh hari, dan stok menipis.
- CRUD barang dengan pencarian dan perlindungan penghapusan barang yang memiliki histori transaksi.
- Penjualan dengan hitung total dan kembalian di server, validasi pembayaran, dan pengurangan stok atomik.
- Pembelian dari supplier dengan penambahan stok atomik.
- Laporan penjualan dan pembelian berdasarkan rentang tanggal, siap dicetak.

## Pengujian
Test menggunakan SQLite in-memory. Jika extension SQLite belum aktif pada PHP CLI Laragon, jalankan:

```powershell
php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit
```
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

```powershell
php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit
```

