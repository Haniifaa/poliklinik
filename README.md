# Web Poliklinik dengan Laravel 11 dan Tailwind CSS
![Screenshot (1750)](https://github.com/user-attachments/assets/9b8e0586-2e8f-4646-b3ba-710e36aed633)  

Web Sistem Temu Janji Poliklinik adalah aplikasi berbasis web yang dibangun menggunakan Laravel 11 dan Tailwind CSS, yang memungkinkan pasien untuk melakukan pendaftaran, pemeriksaan, dan konsultasi dengan dokter secara online. Aplikasi ini menyediakan berbagai fitur, seperti manajemen data pasien, jadwal dokter, dan riwayat pemeriksaan, yang memudahkan administrasi dan meningkatkan efisiensi layanan kesehatan di poliklinik. Desain responsif dengan Tailwind CSS memastikan tampilan yang nyaman di berbagai perangkat.


## Prasyarat

Sebelum memulai, pastikan Anda memiliki hal-hal berikut yang terpasang di mesin lokal Anda:

1. **PHP 8.2 atau lebih tinggi**  
2. **Composer** – Dependency Manager untuk PHP  
3. **Node.js** – Untuk Tailwind CSS dan dependensi front-end lainnya  
4. **NPM** – Node package manager
   
## Langkah-Langkah Instalasi dan Run

### Langkah 1: Clone Repository

Clone terlebih dahulu menggunakan perintah berikut:

```bash
git clone https://github.com/Haniifaa/poliklinik.git
cd poliklinik
```

### Langkah 2: Install Dependensi
```bash
composer install
```

### Langkah 3: Buat Database di MySQL
Buat database baru bernama poliklinik di MySQL:
```bash
CREATE DATABASE poliklinik;
```

### Langkah 4: Install Tailwind CSS
```bash
npm install
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### Langkah 5: Build CSS
```bash
npm run dev
```

### Langkah 6: Jalankan Migrasi dan Seeder
```bash
php artisan migrate --seed
```

### Langkah 7: Jalankan Aplikasi
```bash
php artisan serve
```
## Cara Login sebagai Dokter
Nama/username: Andi  
Password: Semanggi

## Cara Login sebagai Admin
URL:  
```bash
http://127.0.0.1:8000/admin/login
```
Nama/username: admin@example.com  
Password: password123

## Cara Login sebagai Pasien
Password: alamat pasien yang di daftarkan

