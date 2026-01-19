# Quick Start - Setup Authentication System

## 🚀 Langkah-Langkah Setup

### 1. Buat Database PostgreSQL

```bash
# Login ke PostgreSQL
psql -U postgres

# Buat database
CREATE DATABASE pkm_asm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar
EXIT;
```

### 2. Konfigurasi Database

File `.env` sudah dikonfigurasi dengan:
```env
database.default.hostname = localhost
database.default.database = pkm_asm
database.default.username = root
database.default.password = 
database.default.port = 3306
```

**Jika password PostgreSQL Anda berbeda, edit baris:**
```env
database.default.password = your_password
```

### 3. Jalankan Migration

```bash
cd /opt/homebrew/var/www/pkm-asm
php spark migrate
```

**Output yang diharapkan:**
```
Running: 2026-01-12-000001_CreateUsrUserTable
Migrated: 2026-01-12-000001_CreateUsrUserTable
```

### 4. Jalankan Seeder (User Default)

```bash
php spark db:seed UserSeeder
```

**Output yang diharapkan:**
```
Database seeded successfully.
```

**User admin yang dibuat:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@pkm-asm.com`
- Status: Aktif

### 5. Jalankan Development Server

```bash
php spark serve --port=8080
```

### 6. Akses Aplikasi

Buka browser: **http://localhost:8080**

**Login dengan:**
- Username: `admin`
- Password: `admin123`

## ✅ Fitur yang Tersedia

### Halaman Public (Tanpa Login)
- `/` - Landing page login
- `/auth/register` - Registrasi user baru

### Halaman Protected (Perlu Login)
- `/dashboard` - Dashboard utama
- `/user` - User management
- `/user/create` - Tambah user baru
- `/user/edit/:id` - Edit user
- `/examples/form` - Contoh form
- `/examples/table` - Contoh table

## 🔐 Test Authentication Flow

### Test 1: Login
1. Buka http://localhost:8080
2. Login dengan: `admin` / `admin123`
3. Redirect ke dashboard ✅
4. Cek header: nama "Administrator" muncul

### Test 2: Registrasi
1. Klik "Daftar sekarang" di halaman login
2. Isi form registrasi
3. Submit → Redirect ke login dengan pesan sukses
4. Login dengan user baru → **Gagal** (user masih inactive)

### Test 3: Aktivasi User Baru
1. Login sebagai admin
2. Buka User Management
3. Edit user yang baru didaftar
4. Centang "Aktif" → Save
5. Logout
6. Login dengan user baru → **Berhasil** ✅

### Test 4: User CRUD
1. Tambah user baru via "/user/create"
2. Edit user via tombol edit
3. Coba delete user lain (berhasil)
4. Coba delete akun sendiri (gagal, ada proteksi)

### Test 5: Session & Logout
1. Login → Ada session
2. Coba akses `/` → Redirect ke dashboard
3. Logout → Session dihapus
4. Coba akses `/dashboard` → Redirect ke login

## 🛠️ Troubleshooting

### Error: Unknown database 'pkm_asm'
```bash
# Buat database dulu
mysql -u root -p
CREATE DATABASE pkm_asm;
```

### Error: Access denied for user 'root'
```bash
# Update password di .env
database.default.password = your_postgres_password
```

### Error: Migration table not found
```bash
# Pastikan database ada dan accessible
php spark migrate:status
```

### Error: writable/session not writable
```bash
chmod -R 777 writable/
```

### Lupa password admin
```bash
# Jalankan seeder ulang
php spark db:seed UserSeeder
```

## 📋 Checklist Setup

- [ ] Database `pkm_asm` sudah dibuat
- [ ] File `.env` sudah dikonfigurasi
- [ ] Migration berhasil dijalankan
- [ ] Seeder berhasil dijalankan
- [ ] Server development running
- [ ] Bisa login dengan admin/admin123
- [ ] Bisa akses dashboard
- [ ] Bisa akses user management
- [ ] Bisa logout

## 📚 Dokumentasi Lengkap

Lihat file-file berikut untuk detail:
- `AUTH_GUIDE.md` - Panduan authentication lengkap
- `TEMPLATE_GUIDE.md` - Panduan template & layout
- `PROJECT_SUMMARY.md` - Summary project

## 🎯 Next Steps

Setelah setup berhasil:
1. ✅ Ganti password admin default
2. ✅ Buat user baru untuk testing
3. ✅ Explore fitur user management
4. ✅ Mulai develop fitur asset management
5. ✅ Customize layout sesuai kebutuhan

---

**Happy Coding! 🚀**
