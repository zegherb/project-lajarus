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

> ⚠️ Sistem ini menggunakan Web Service internal. Untuk mencegah deadlock (loading terus-menerus), server **harus dijalankan dengan beberapa worker**.

**Windows (PowerShell):**
```powershell
$env:PHP_CLI_SERVER_WORKERS=4; php yii serve --port=8080
```

**Linux / macOS (Bash/Zsh):**
```bash
PHP_CLI_SERVER_WORKERS=4 php yii serve --port=8080
```

Akses aplikasi di: `http://localhost:8080`