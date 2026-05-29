# 🛣️ LAJARUS — Sistem Pelaporan Jalan Rusak

Sistem informasi berbasis web untuk pelaporan infrastruktur jalan rusak, dilengkapi keamanan transportasi data berbasis Web Service.

---

## 📋 Prasyarat

Pastikan sudah terinstal:
- **PHP** (dengan ekstensi `soap` aktif di `php.ini`)
- **Composer**
- **MySQL / MariaDB**

---

## 🚀 Cara Menjalankan

### 1. Clone & Install Dependensi

```bash
git clone https://github.com/zegherb/project-lajarus.git
cd project-lajarus
composer install
```

### 2. Konfigurasi Database

Buat database baru di MySQL (contoh: `db_lajarus`), lalu sesuaikan `config/db.php`:

```php
<?php
return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'mysql:host=localhost;dbname=db_lajarus',
    'username' => 'root',  // Sesuaikan dengan konfigurasi lokal Anda
    'password' => '',
    'charset'  => 'utf8',
];
```

Kemudian jalankan migrasi untuk membuat tabel:

```bash
php yii migrate
```

### 3. Jalankan Server

> ⚠️ Sistem ini menggunakan SOAP Web Service internal. Untuk mencegah deadlock (loading terus-menerus), server **harus dijalankan sesuai dengan environment anda**.

**Untuk Pengguna Windows (Laragon / XAMPP):**
Web server bawaan (Apache/Nginx) sudah mendukung *multi-threading*. Anda **tidak perlu** menggunakan perintah CLI (`php yii serve`). 
1. Pindahkan folder `project-lajarus` ke direktori web server Anda (`www` untuk Laragon, atau `htdocs` untuk XAMPP).
2. Jalankan Apache melalui Control Panel Laragon / XAMPP.
3. Akses aplikasi langsung melalui browser:
   * **Laragon:** `http://project-lajarus.test` *(atau sesuai Virtual Host)*
   * **XAMPP:** `http://localhost/project-lajarus/web`
     
**Untuk Pengguna Linux Native (Apache2 & MySQL):**
Apache2 sudah mendukung *multi-threading* secara bawaan.
1. Pastikan ekstensi SOAP terinstal (contoh di Ubuntu: `sudo apt install php-soap` lalu `sudo systemctl restart apache2`).
2. Pindahkan folder proyek ke `/var/www/html/project-lajarus`.
3. Berikan hak akses *write* pada folder yang dibutuhkan Yii2:
```bash
   sudo chmod -R 777 /var/www/html/project-lajarus/web/assets
   sudo chmod -R 777 /var/www/html/project-lajarus/runtime
```
Akses aplikasi di: `http://localhost/project-lajarus/web`

**Untuk Pengguna Mode Terminal / CLI (Linux / macOS):**
Jika Anda tidak menggunakan Apache/Nginx dan murni menggunakan server bawaan PHP, Anda wajib memaksa PHP menjalankan Multi-Worker agar request SOAP tidak saling menunggu.
```bash
PHP_CLI_SERVER_WORKERS=4 php yii serve --port=8080
```
Akses aplikasi di: `http://localhost:8080`
