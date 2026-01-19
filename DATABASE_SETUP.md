# ⚠️ PENTING - BACA SEBELUM MENJALANKAN APLIKASI

## Setup Database Diperlukan!

Sistem authentication & user management sudah **100% siap**, tapi Anda perlu setup database terlebih dahulu.

---

## 🔧 LANGKAH SETUP (5 Menit)

### 1. Pastikan PostgreSQL Running

```bash
# Cek status PostgreSQL (pilih salah satu sesuai sistem Anda)
brew services list | grep postgresql    # macOS dengan Homebrew
sudo systemctl status postgresql        # Linux
# Atau buka pgAdmin
```

**Jika belum running:**
```bash
# macOS
brew services start postgresql

# Linux
sudo systemctl start postgresql
```

### 2. Buat Database

**Opsi A: Via Terminal**
```bash
# Login ke PostgreSQL (tanpa password jika fresh install)
psql -U postgres

# Atau jika ada password
psql -U postgres -W

# Buat database
CREATE DATABASE pkm_asm WITH ENCODING='UTF8';

# Verifikasi
\l  # List semua database, pastikan pkm_asm ada

# Keluar
\q
```

**Opsi B: Via pgAdmin**
1. Buka pgAdmin (biasanya di browser: http://localhost:5050)
2. Klik kanan "Databases"
3. Create > Database
4. Database name: `pkm_asm`
5. Encoding: `UTF8`
6. Click Save

### 3. Update File .env (Jika Perlu)

File `.env` sudah dikonfigurasi dengan default PostgreSQL:
```env
database.default.hostname = localhost
database.default.database = pkm_asm
database.default.username = postgres
database.default.password = 
database.default.DBDriver = Postgre
database.default.port = 5432
```

**Jika password PostgreSQL Anda TIDAK KOSONG**, edit file `.env` baris ini:
```env
database.default.password = your_postgres_password_here
```

**Jika port PostgreSQL Anda BUKAN 5432**, edit:
```env
database.default.port = 5433  # atau port Anda
```

**Jika username PostgreSQL berbeda**, edit:
```env
database.default.username = your_username  # default: postgres
```

### 4. Jalankan Migration

```bash
cd /opt/homebrew/var/www/pkm-asm
php spark migrate
```

**Output yang benar:**
```
Running: 2026-01-12-000001_CreateUsrUserTable
Migrated: 2026-01-12-000001_CreateUsrUserTable
```

**Jika error:**
- Cek connection database di `.env`
- Pastikan PostgreSQL running
- Pastikan database `pkm_asm` sudah dibuat

### 5. Jalankan Seeder (Buat User Admin)

```bash
php spark db:seed UserSeeder
```

**Output yang benar:**
```
Database seeded successfully.
```

Ini akan membuat user admin dengan kredensial:
- **Username:** admin
- **Password:** admin123
- **Email:** admin@pkm-asm.com

### 6. Jalankan Development Server

```bash
php spark serve --port=8080
```

### 7. Test Login

1. Buka browser: **http://localhost:8080**
2. Login dengan:
   - Username: `admin`
   - Password: `admin123`
3. ✅ Berhasil! Anda masuk ke dashboard

---

## 🎯 VERIFIKASI SETUP BERHASIL

Checklist ini memastikan semuanya berjalan:

- [ ] Database `pkm_asm` sudah dibuat
- [ ] File `.env` sudah dikonfigurasi dengan benar
- [ ] Migration berhasil dijalankan (ada tabel `usr_user`)
- [ ] Seeder berhasil dijalankan (ada 1 user admin)
- [ ] Server running di http://localhost:8080
- [ ] Bisa buka halaman login
- [ ] Bisa login dengan admin/admin123
- [ ] Redirect ke dashboard setelah login
- [ ] Nama "Administrator" muncul di header
- [ ] Menu "User Management" bisa diakses
- [ ] Bisa logout

---

## ❌ TROUBLESHOOTING

### Error: "Unknown database 'pkm_asm'"
**Solusi:** Database belum dibuat. Kembali ke langkah 2.
```bash
psql -U postgres
CREATE DATABASE pkm_asm;
\q
```

### Error: "FATAL: password authentication failed for user 'postgres'"
**Solusi:** Password salah atau user tidak ada.
```bash
# Test koneksi
psql -U postgres -W
# Jika berhasil, update password di .env

# Atau reset password PostgreSQL (Linux)
sudo -u postgres psql
ALTER USER postgres PASSWORD 'new_password';
\q
```

### Error: "php: command not found"
**Solusi:** PHP belum terinstall atau tidak ada di PATH.
```bash
# Install PHP dengan PostgreSQL extension (macOS)
brew install php
brew install php-pgsql

# Install PHP (Ubuntu/Debian)
sudo apt install php php-pgsql php-mbstring php-xml
```

### Error: "SQLSTATE[08006] Connection refused"
**Solusi:** PostgreSQL tidak running.
```bash
# macOS
brew services start postgresql

# Linux
sudo systemctl start postgresql
```

### Error: Migration sudah jalan tapi tabel tidak ada
**Solusi:** Cek di database yang benar.
```bash
psql -U postgres -d pkm_asm
\dt  # List semua tabel, harusnya ada 'migrations' dan 'usr_user'
SELECT * FROM usr_user;  # Cek data
\q
```

### Lupa password admin setelah seeder
**Solusi:** Jalankan seeder ulang (akan replace data admin).
```bash
php spark db:seed UserSeeder
```

---

## 📋 QUICK COMMAND REFERENCE

```bash
# Setup Database PostgreSQL
psql -U postgres
CREATE DATABASE pkm_asm;
\q

# Run Migration
cd /opt/homebrew/var/www/pkm-asm
php spark migrate

# Run Seeder
php spark db:seed UserSeeder

# Start Server
php spark serve --port=8080

# Check Migration Status
php spark migrate:status

# Rollback Migration (jika perlu reset)
php spark migrate:rollback

# View Routes
php spark routes

# PostgreSQL Commands
psql -U postgres -d pkm_asm  # Connect to database
\dt                           # List tables
\d usr_user                   # Describe table
SELECT * FROM usr_user;       # View data
\q                            # Quit
```

---

## 🎉 SETELAH SETUP BERHASIL

### Hal yang Bisa Dilakukan:

1. **Test Authentication**
   - Login/Logout
   - Registrasi user baru
   - Verifikasi password hashing

2. **Test User Management**
   - List users
   - Create new user
   - Edit user
   - Delete user (soft delete)
   - Toggle active status

3. **Explore Template**
   - Dashboard dengan cards
   - Form examples
   - Table examples
   - UI components

4. **Start Development**
   - Buat module Asset Management
   - Tambah role & permission
   - Kustomisasi UI
   - Tambah fitur baru

---

## 📚 DOKUMENTASI LENGKAP

Setelah setup berhasil, baca dokumentasi:

1. **SETUP_GUIDE.md** ← Anda di sini
2. **AUTH_GUIDE.md** - Detail authentication system
3. **TEMPLATE_GUIDE.md** - Cara pakai template
4. **FINAL_SUMMARY.md** - Complete feature list

---

## 💬 BUTUH BANTUAN?

Jika masih ada masalah:

1. Cek error message dengan teliti
2. Baca troubleshooting di atas
3. Cek log di `writable/logs/`
4. Pastikan semua requirement terpenuhi:
   - PHP 7.4+
   - PostgreSQL 12+
   - Composer
   - CodeIgniter 4 dependencies

---

**Status Saat Ini:**
- ✅ Kode 100% ready
- ✅ Template terintegrasi
- ✅ Auth system complete
- ✅ User management ready
- ⏳ **Database setup needed** ← Langkah Anda selanjutnya

**Estimasi waktu setup: 5-10 menit**

---

**Good luck! 🚀**

Setelah setup selesai, Anda akan punya sistem PKM Asset Management yang production-ready dengan authentication & user management lengkap!
