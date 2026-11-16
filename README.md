<div align="center">
  <img src="public/images/skull-spin.svg" alt="Rocking Lawd Spinning Skull" width="110" height="110" />

  <h1>ROCKING LAWD</h1>
  <p><strong>The Ultimate Hip-Hop & Underground Concert Ticketing Portal</strong></p>
  <p><em>Lineup Panas, Crowd Pecah — Amankan Tiket & Check-in Instan dalam Satu Platform.</em></p>

  <p>
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PostgreSQL-16+-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  </p>
</div>

---

## Tentang Proyek

**Rocking Lawd** adalah sistem manajemen event dan pemesanan tiket konser berbasis web yang dirancang khusus untuk skena musik hip-hop, trap, dan underground. Aplikasi ini menangani seluruh siklus acara mulai dari manajemen lineup artis lintas panggung, kalkulasi kuota tiket secara real-time, penerbitan E-Ticket dengan QR Code unik, hingga validasi check-in di lokasi acara.

---

## Fitur Utama

* **Manajemen Event & Lineup Multi-Stage:** Pengelolaan jadwal konser beserta relasi *Many-to-Many* antara event dan artis lengkap dengan pembagian panggung (*stage*) dan jam tampil (*performance time*).
* **Transaksi & Kalkulasi Kuota Otomatis:** Menggunakan `DB::transaction` (ACID) untuk menjaga integritas data pemesanan tiket dan penyesuaian sisa kuota secara real-time.
* **E-Ticket & QR Code Scanner:** Penerbitan tiket digital ber-QR Code untuk peserta serta pemindai kamera berbasis web (`html5-qrcode`) bagi panitia untuk verifikasi check-in dan pencegahan tiket ganda.
* **Role-Based Access Control (RBAC):** Pembagian hak akses terstruktur antara **Admin**, **Organizer**, dan **Participant** menggunakan *Spatie Laravel Permission*.
* **Manajemen Aset & Media:** Upload banner event resolusi tinggi dengan pembersihan berkas lama secara otomatis saat data diperbarui atau dihapus.

---

## Tech Stack

* **Backend:** PHP 8.2+, Laravel 11
* **Database:** PostgreSQL
* **Frontend:** Blade Templating, Tailwind CSS, Alpine.js, Vite
* **Packages & Libraries:**
  * `spatie/laravel-permission` (Manajemen Role & Permission)
  * `html5-qrcode` (Pemindai QR Code berbasis kamera browser)
  * `barryvdh/laravel-dompdf` / QR Generator (Cetak E-Ticket)

---

## Instalasi & Menjalankan Proyek

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/sanzekai/rocking-lawd.git
   cd rocking-lawd
   ```

2. **Install dependensi PHP & Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Salin file environment dan generate application key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database PostgreSQL di file `.env`:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=rocking_lawd
   DB_USERNAME=postgres
   DB_PASSWORD=password_kamu
   ```

5. **Jalankan migrasi database, seeder, dan symlink storage:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

6. **Compile aset frontend & jalankan server lokal:**
   ```bash
   npm run build
   php artisan serve
   ```
   Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## Akun Default (Seeder)

Gunakan kredensial berikut untuk mengakses panel administrator setelah menjalankan seeder:

* **Email:** `admin@rockinglawd.id`
* **Password:** `password` *(sesuaikan dengan seeder lokal kamu)*

---

## Lisensi

Proyek ini dikembangkan oleh **[sanzekai](https://github.com/sanzekai)** untuk keperluan pengembangan sistem manajemen event dan portofolio rekayasa perangkat lunak.