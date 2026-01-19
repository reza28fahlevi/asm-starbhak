# 🔄 PostgreSQL Migration Summary

## ✅ Perubahan yang Sudah Dilakukan

### 1. File Konfigurasi (.env)
**Diubah dari MySQL ke PostgreSQL:**
```env
# BEFORE (MySQL)
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306

# AFTER (PostgreSQL)
database.default.username = postgres
database.default.password = 
database.default.DBDriver = Postgre
database.default.port = 5432
```

### 2. Migration File
**File:** `app/Database/Migrations/2026-01-12-000001_CreateUsrUserTable.php`

**Perubahan Syntax untuk PostgreSQL:**

#### ID Field (Auto Increment)
```php
// BEFORE (MySQL)
'id' => [
    'type'           => 'INT',
    'constraint'     => 11,
    'unsigned'       => true,
    'auto_increment' => true,
],

// AFTER (PostgreSQL)
'id' => [
    'type' => 'SERIAL',
],
```

#### Integer Fields (Foreign Keys)
```php
// BEFORE (MySQL)
'departemen_id' => [
    'type'       => 'INT',
    'constraint' => 11,
],

// AFTER (PostgreSQL)
'departemen_id' => [
    'type'       => 'INTEGER',
],
```

#### Unique Constraints
```php
// BEFORE (MySQL) - Inline dengan field definition
'username' => [
    'type'       => 'VARCHAR',
    'constraint' => 50,
    'unique'     => true,
],

// AFTER (PostgreSQL) - Terpisah dengan addUniqueKey()
'username' => [
    'type'       => 'VARCHAR',
    'constraint' => 50,
],
// ...kemudian di akhir up():
$this->forge->addUniqueKey('username');
$this->forge->addUniqueKey('email');
```

### 3. Dokumentasi
**File yang sudah diupdate:**
- ✅ DATABASE_SETUP.md - Instruksi setup PostgreSQL
- ✅ AUTH_GUIDE.md - Config PostgreSQL
- ✅ SETUP_GUIDE.md - Command PostgreSQL
- ✅ README.md - Tech stack PostgreSQL
- ✅ FINAL_SUMMARY.md - PostgreSQL references

---

## 🎯 Langkah Selanjutnya

### 1. Install PostgreSQL (Jika Belum)

**macOS:**
```bash
# Via Homebrew
brew install postgresql@15
brew services start postgresql@15

# Atau download installer dari:
# https://www.postgresql.org/download/macos/
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql
```

**Windows:**
Download installer dari: https://www.postgresql.org/download/windows/

### 2. Setup Database

```bash
# Login ke PostgreSQL (tanpa password untuk fresh install)
psql -U postgres

# Di dalam psql prompt:
CREATE DATABASE pkm_asm WITH ENCODING='UTF8';

# Verifikasi
\l  # Harusnya ada database 'pkm_asm'

# Keluar
\q
```

### 3. Set Password (Jika Diperlukan)

Jika PostgreSQL Anda butuh password, update di `.env`:
```env
database.default.password = your_password_here
```

### 4. Install PHP PostgreSQL Extension

```bash
# macOS
brew install php
# PostgreSQL extension biasanya sudah include

# Linux
sudo apt install php-pgsql

# Verifikasi
php -m | grep pgsql
# Harusnya muncul: pdo_pgsql dan pgsql
```

### 5. Jalankan Migration

```bash
cd /opt/homebrew/var/www/pkm-asm

# Run migration
php spark migrate

# Output yang diharapkan:
# Running: 2026-01-12-000001_CreateUsrUserTable
# Migrated: 2026-01-12-000001_CreateUsrUserTable
```

### 6. Jalankan Seeder

```bash
php spark db:seed UserSeeder

# Output: Database seeding completed successfully.
```

### 7. Start Development Server

```bash
php spark serve --port=8080

# Buka: http://localhost:8080
```

---

## 🔍 Verifikasi Database

### Check Tables
```bash
psql -U postgres -d pkm_asm
```

Di dalam psql:
```sql
-- List semua tabel
\dt

-- Harusnya muncul:
-- migrations
-- usr_user

-- Describe tabel usr_user
\d usr_user

-- View data
SELECT id, username, email, is_active FROM usr_user;

-- Harusnya ada 1 row:
-- id | username | email | is_active
-- 1  | admin    | admin@example.com | t

-- Keluar
\q
```

---

## 🐛 Troubleshooting

### Error: "psql: command not found"
PostgreSQL belum terinstall atau tidak ada di PATH.
```bash
# macOS - tambahkan ke PATH
echo 'export PATH="/opt/homebrew/opt/postgresql@15/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

### Error: "FATAL: password authentication failed"
Password PostgreSQL salah.
```bash
# Reset password
sudo -u postgres psql
ALTER USER postgres PASSWORD 'new_password';
\q

# Update .env dengan password baru
```

### Error: "could not connect to server: Connection refused"
PostgreSQL service tidak running.
```bash
# macOS
brew services start postgresql@15

# Linux
sudo systemctl start postgresql
```

### Error: "database 'pkm_asm' does not exist"
Database belum dibuat.
```bash
psql -U postgres
CREATE DATABASE pkm_asm;
\q
```

### Error: "Call to undefined function pg_connect()"
PHP PostgreSQL extension belum terinstall.
```bash
# Install extension
brew install php-pgsql  # macOS
sudo apt install php-pgsql  # Linux

# Restart PHP
brew services restart php  # macOS
sudo systemctl restart php-fpm  # Linux
```

---

## 📊 Perbedaan MySQL vs PostgreSQL

### Data Types
| MySQL | PostgreSQL | Fungsi |
|-------|-----------|--------|
| INT AUTO_INCREMENT | SERIAL | Auto-increment primary key |
| INT UNSIGNED | INTEGER | Integer tanpa unsigned |
| VARCHAR(n) | VARCHAR(n) | String dengan panjang max |
| DATETIME | TIMESTAMP | Date & time |
| TINYINT(1) | BOOLEAN | True/false |

### Syntax Differences
| Fitur | MySQL | PostgreSQL |
|-------|-------|-----------|
| Auto-increment | `AUTO_INCREMENT` | `SERIAL` |
| Unsigned | `UNSIGNED` | Tidak ada (gunakan CHECK) |
| Unique constraint | Inline di field | `addUniqueKey()` |
| Boolean | `TINYINT(1)` | `BOOLEAN` |
| String concat | `CONCAT()` atau `\|\|` | `\|\|` |
| Case sensitivity | Case-insensitive | Case-sensitive |

### CodeIgniter Query Builder
**Tetap sama untuk kedua database!**
```php
// Ini akan work di MySQL dan PostgreSQL
$this->db->where('username', $username);
$this->db->insert('usr_user', $data);
$this->db->update('usr_user', $data, ['id' => $id]);
```

---

## ✨ Keuntungan PostgreSQL

1. **Standards Compliance** - Lebih sesuai dengan SQL standard
2. **Advanced Features** - JSON support, full-text search, array types
3. **Better Concurrency** - MVCC (Multi-Version Concurrency Control)
4. **Reliability** - ACID compliant, crash recovery
5. **Extensibility** - Custom functions, extensions (PostGIS, etc)
6. **Open Source** - Truly free, no licensing issues

---

## 📝 Notes

- Semua kode PHP **TIDAK PERLU DIUBAH** karena CodeIgniter Query Builder sudah abstraksi database
- Model, Controller, View tetap sama
- Password hashing dengan `password_hash()` tetap sama
- Session management tetap sama
- Yang berubah hanya: **konfigurasi** dan **migration syntax**

---

## 🎉 Selesai!

Setelah menjalankan langkah 1-7 di atas, aplikasi Anda sudah siap dengan PostgreSQL!

**Test login dengan:**
- Username: `admin`
- Password: `admin123`

Selamat coding! 🚀
