# Authentication & User Management System

## Deskripsi Project
**PKM Asset Management** - Sistem manajemen aset dengan fitur authentication dan user management lengkap.

## Fitur Authentication

### ✅ Login System
- Landing page dengan gradient background
- Form login dengan validasi
- Session management
- Remember me functionality
- Password hashing dengan PHP `password_hash()`
- Protected routes dengan middleware

### ✅ Registration System  
- Self-registration untuk user baru
- Validasi form lengkap (username, email, password)
- Konfirmasi password
- User status default: inactive (butuh approval admin)
- Email & username unique validation

### ✅ User Management (CRUD)
- List semua user
- Tambah user baru
- Edit user (termasuk update password opsional)
- Soft delete user
- Toggle status aktif/nonaktif
- Proteksi: user tidak bisa hapus akun sendiri

## Database Schema

```sql
CREATE TABLE "usr_user" (
  "id" integer PRIMARY KEY AUTO_INCREMENT,
  "username" varchar(100) UNIQUE,
  "password" varchar(255),
  "email" varchar(150) UNIQUE,
  "nama" varchar(200),
  "nohp" varchar(20),
  "nomor_registrasi" varchar(100),
  "departemen_id" integer,
  "active" boolean DEFAULT true,
  "created_at" timestamp,
  "created_by" varchar(100),
  "updated_at" timestamp,
  "updated_by" varchar(100),
  "is_deleted" boolean DEFAULT false,
  "deleted_at" timestamp,
  "deleted_by" varchar(100)
);
```

## Setup Database

### 1. Buat Database
```sql
CREATE DATABASE pkm_asm;
```

### 2. Konfigurasi Database
Edit file `.env`:
```env
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = pkm_asm
database.default.username = postgres
database.default.password = 
database.default.DBDriver = Postgre
database.default.port = 5432
```

### 3. Jalankan Migration
```bash
php spark migrate
```

### 4. Jalankan Seeder (Data Default)
```bash
php spark db:seed UserSeeder
```

**User Default:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@pkm-asm.com`

## Struktur File

### Controllers
```
app/Controllers/
├── Auth.php          # Login, Logout, Register
├── User.php          # User CRUD Management
├── Dashboard.php     # Dashboard
├── Pages.php         # Static pages
└── Examples.php      # Examples
```

### Models
```
app/Models/
└── UserModel.php     # User model dengan password hashing
```

### Views - Auth
```
app/Views/auth/
├── login.php         # Landing page login
└── register.php      # Registration page
```

### Views - User Management
```
app/Views/user/
├── index.php         # List users
├── create.php        # Form tambah user
└── edit.php          # Form edit user
```

### Filters (Middleware)
```
app/Filters/
├── AuthFilter.php    # Proteksi halaman yang butuh login
└── NoAuthFilter.php  # Redirect ke dashboard jika sudah login
```

### Database
```
app/Database/
├── Migrations/
│   └── 2026-01-12-000001_CreateUsrUserTable.php
└── Seeds/
    └── UserSeeder.php
```

## Routes

### Public Routes (No Auth)
```php
GET  /                    → Login page
POST /auth/login          → Process login
GET  /auth/register       → Registration page
POST /auth/doRegister     → Process registration
```

### Protected Routes (Auth Required)
```php
GET  /dashboard           → Dashboard
GET  /auth/logout         → Logout

# User Management
GET  /user                → List users
GET  /user/create         → Form create user
POST /user/store          → Save new user
GET  /user/edit/:id       → Form edit user
POST /user/update/:id     → Update user
POST /user/delete/:id     → Delete user (soft)
```

## Security Features

### 1. Password Hashing
```php
// Otomatis di-hash sebelum insert/update
password_hash($password, PASSWORD_DEFAULT)

// Verifikasi saat login
password_verify($input, $hashedPassword)
```

### 2. CSRF Protection
Semua form dilindungi CSRF token:
```php
<?= csrf_field() ?>
```

### 3. Input Validation
- Username: minimal 3 karakter, unique
- Email: valid email format, unique
- Password: minimal 6 karakter
- Konfirmasi password harus match

### 4. Session Management
```php
// Data session setelah login
session()->set([
    'user_id'   => $user['id'],
    'username'  => $user['username'],
    'email'     => $user['email'],
    'nama'      => $user['nama'],
    'logged_in' => true
]);
```

### 5. Middleware Protection
```php
// AuthFilter - cek apakah user sudah login
if (!session()->get('logged_in')) {
    return redirect()->to('/');
}

// NoAuthFilter - redirect jika sudah login
if (session()->get('logged_in')) {
    return redirect()->to('/dashboard');
}
```

### 6. Soft Delete
User tidak benar-benar dihapus, hanya di-mark:
```php
'is_deleted' => true,
'deleted_at' => date('Y-m-d H:i:s'),
'deleted_by' => session()->get('username')
```

## Cara Menggunakan

### 1. Jalankan Server
```bash
php spark serve --port=8080
```

### 2. Akses Aplikasi
```
Login Page:    http://localhost:8080/
Register:      http://localhost:8080/auth/register
Dashboard:     http://localhost:8080/dashboard (setelah login)
User Mgmt:     http://localhost:8080/user (setelah login)
```

### 3. Login dengan Akun Default
```
Username: admin
Password: admin123
```

### 4. Test Flow
1. **Registrasi** → User baru (status inactive)
2. **Login sebagai admin** → Aktifkan user baru di User Management
3. **Logout** → Login dengan user baru
4. **Manage Users** → CRUD operations

## Fitur User Management

### List Users
- Tampilkan semua user (kecuali yang soft deleted)
- Badge status: Aktif/Nonaktif
- Action buttons: Edit & Delete

### Create User
- Form lengkap (username, email, nama, password, dll)
- Validasi realtime
- Checkbox status aktif
- Auto-hash password

### Edit User
- Load data existing user
- Update tanpa ubah password (optional)
- Validasi username & email unique (kecuali milik sendiri)
- Update password hanya jika diisi

### Delete User
- Soft delete (data masih ada di DB)
- Konfirmasi modal sebelum delete
- Proteksi: tidak bisa delete akun sendiri
- Track deleted_by & deleted_at

## Best Practices Implemented

✅ **Separation of Concerns** - Controller, Model, View terpisah
✅ **DRY (Don't Repeat Yourself)** - Reusable layout components  
✅ **Security First** - Password hash, CSRF, validation, filters
✅ **Soft Delete** - Data integrity, audit trail
✅ **Session Management** - Secure authentication
✅ **Input Validation** - Server-side & client-side
✅ **User Feedback** - Flash messages (success/error)
✅ **Audit Trail** - created_by, updated_by, deleted_by
✅ **Responsive Design** - Bootstrap 5, mobile-friendly

## Troubleshooting

### Error: Database connection failed
```bash
# Pastikan PostgreSQL running
# Cek konfigurasi di .env
# Buat database: CREATE DATABASE pkm_asm;
```

### Error: Migration failed
```bash
# Reset migration
php spark migrate:rollback
php spark migrate
```

### Lupa Password Admin
```bash
# Jalankan seeder ulang
php spark db:seed UserSeeder
# atau reset manual di database
```

### Session tidak work
```bash
# Clear cache
php spark cache:clear
# Pastikan folder writable/session dapat ditulis
chmod -R 777 writable/
```

## Pengembangan Selanjutnya

🔜 **Role & Permission System** - Admin, User, Manager roles  
🔜 **Email Verification** - Verify email saat registrasi  
🔜 **Forgot Password** - Reset password via email  
🔜 **Profile Management** - User dapat update profil sendiri  
🔜 **Activity Log** - Track semua aktivitas user  
🔜 **Two-Factor Authentication** - Extra security layer  
🔜 **Asset Management** - CRUD untuk assets  
🔜 **Asset Assignment** - Assign asset ke user  
🔜 **Reports & Analytics** - Dashboard statistics  

---

**Version:** 1.0.0  
**Last Updated:** January 12, 2026  
**Framework:** CodeIgniter 4.6.4  
**Template:** NiceAdmin Bootstrap
