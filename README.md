# Database Setup untuk SMP YZA 2 Bogor

## Persyaratan
- MySQL/MariaDB Server
- PHP dengan ekstensi PDO
- Akses command line atau phpMyAdmin

## Cara Setup Database

### 1. Menggunakan Browser (Paling Mudah)
1. Pastikan MySQL server sudah berjalan
2. Buka browser dan akses: `http://localhost/abdc/public/php/run_db_setup.php`
3. Script akan otomatis membuat database dan tabel
4. Ikuti instruksi di browser

### 2. Menggunakan Command Line
```bash
# Masuk ke MySQL
mysql -u root -p

# Jalankan script setup
source public/php/db_setup.sql
```

### 3. Menggunakan phpMyAdmin
1. Buka phpMyAdmin di browser
2. Buat database baru bernama `smp_yza2` (jika belum ada)
3. Import file `public/php/db_setup.sql`
4. Klik "Go" untuk menjalankan

### 3. Menggunakan Script PHP (Alternatif)
Buat file `setup_db.php` di root project:
```php
<?php
// Include config
require_once 'public/php/config.php';

// Read and execute SQL file
$sql = file_get_contents('public/php/db_setup.sql');
$statements = array_filter(array_map('trim', explode(';', $sql)));

$pdo = getDBConnection();
foreach ($statements as $statement) {
    if (!empty($statement)) {
        $pdo->exec($statement);
    }
}

echo "Database setup completed!";
?>
```

Kemudian akses `setup_db.php` di browser.

## Struktur Database

### Tabel `admin_users`
- `id`: Primary key
- `username`: Username unik
- `email`: Email unik
- `password`: Password yang di-hash
- `name`: Nama lengkap
- `role`: admin/super_admin
- `status`: active/inactive
- `created_at`, `updated_at`: Timestamp
- `last_login`: Waktu login terakhir
- `login_attempts`: Jumlah percobaan login
- `locked_until`: Waktu kunci akun

### Tabel `ppdb_registrations`
- `id`: Primary key
- `registration_number`: Nomor pendaftaran unik
- `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`
- `no_hp`, `email`, `alamat`
- `sekolah_asal`, `npsn`
- Path file untuk dokumen upload (8 kolom)
- `status`: pending/verified/approved/rejected
- `submitted_at`, `verified_at`
- `verified_by`: Foreign key ke admin_users
- `notes`: Catatan verifikasi

## Akun Admin Default

| Username | Email | Password | Role |
|----------|-------|----------|------|
| admin | admin@smpyza2bogor.sch.id | admin123 | super_admin |
| kepala_sekolah | kepsek@smpyza2bogor.sch.id | admin123 | admin |
| operator | operator@smpyza2bogor.sch.id | admin123 | admin |

## Views

- `active_admins`: Admin yang aktif
- `pending_ppdb`: Pendaftaran PPDB yang pending

## Keamanan

- Password menggunakan `password_hash()` dengan BCRYPT
- Session menggunakan `httpOnly` cookies
- Login attempts tracking untuk mencegah brute force
- Account locking mechanism

## Troubleshooting

1. **Error: Access denied**: Pastikan username/password MySQL benar
2. **Error: Database doesn't exist**: Buat database manual dulu
3. **Error: Table already exists**: Script menggunakan `IF NOT EXISTS`
4. **Login gagal**: Pastikan password di-hash dengan benar

## Backup Database

```sql
mysqldump -u root -p smp_yza2 > backup.sql
```

## Restore Database

```sql
mysql -u root -p smp_yza2 < backup.sql
```