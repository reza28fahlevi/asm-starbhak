# 🏢 PKM Asset Management System

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.6.4-orange)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)
![License](https://img.shields.io/badge/License-MIT-green)

Sistem manajemen aset lengkap dengan **authentication system**, **user management**, dan **template admin modern** menggunakan CodeIgniter 4 dan NiceAdmin Bootstrap.

---

## ✨ Features

### 🔐 Authentication System
- ✅ **Login** - Secure authentication dengan password hashing
- ✅ **Registration** - Self-registration dengan admin approval
- ✅ **Session Management** - Secure session-based authentication
- ✅ **Password Security** - bcrypt hashing dengan `password_hash()`
- ✅ **Middleware Protection** - Route guards dengan filters

### 👥 User Management (CRUD)
- ✅ **List Users** - Tabel responsive dengan status badges
- ✅ **Create User** - Form lengkap dengan validasi
- ✅ **Edit User** - Update data & password optional
- ✅ **Soft Delete** - Data tidak hilang permanen
- ✅ **Audit Trail** - Track created_by, updated_by, deleted_by

### 🎨 Modern Admin Template
- ✅ **NiceAdmin** - Bootstrap 5 admin template
- ✅ **Responsive Design** - Mobile-friendly layout
- ✅ **Modular Components** - Reusable header, sidebar, footer
- ✅ **Dynamic Menu** - Active state & user info
- ✅ **Flash Messages** - User feedback system

---

## 🚀 Quick Start

### 1. Clone/Download Project
```bash
cd /opt/homebrew/var/www/pkm-asm
```

### 2. Setup Database
```bash
# Buat database PostgreSQL
psql -U postgres
CREATE DATABASE pkm_asm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Konfigurasi di .env (sudah dikonfigurasi)
# Edit password jika diperlukan: database.default.password = your_password

# Run migration
php spark migrate

# Run seeder (buat admin)
php spark db:seed UserSeeder
```

### 3. Run Development Server
```bash
php spark serve --port=8080
```

### 4. Access Application
```
URL: http://localhost:8080
Username: admin
Password: admin123
```

**📖 Lihat [DATABASE_SETUP.md](DATABASE_SETUP.md) untuk panduan lengkap!**

---

## 📁 Project Structure

```
pkm-asm/
├── app/
│   ├── Controllers/      # Auth, User, Dashboard
│   ├── Models/           # UserModel dengan password hash
│   ├── Views/            # Layout, Auth, User views
│   ├── Filters/          # AuthFilter, NoAuthFilter
│   ├── Database/         # Migrations & Seeders
│   └── Config/           # Routes, Filters config
├── public/
│   └── assets/           # NiceAdmin template
├── .env                  # Environment configuration
└── docs/                 # Documentation files
```

---

## 🔒 Security Features

| Feature | Implementation |
|---------|---------------|
| **Password Hashing** | bcrypt dengan `password_hash()` |
| **CSRF Protection** | Token di semua form |
| **Session Security** | File-based sessions |
| **SQL Injection** | Query Builder CI4 |
| **XSS Protection** | Auto escaping output |
| **Route Protection** | Middleware filters |
| **Input Validation** | Server-side validation |
| **Soft Delete** | Audit trail & data integrity |

---

## 📚 Documentation

| File | Description |
|------|-------------|
| [DATABASE_SETUP.md](DATABASE_SETUP.md) | **⭐ START HERE** - Setup database |
| [AUTH_GUIDE.md](AUTH_GUIDE.md) | Authentication system complete guide |
| [TEMPLATE_GUIDE.md](TEMPLATE_GUIDE.md) | Template & layout usage |
| [FINAL_SUMMARY.md](FINAL_SUMMARY.md) | Complete feature list & summary |

---

## 🎯 Default Credentials

```
Username: admin
Password: admin123
Email: admin@pkm-asm.com
```

**⚠️ Ganti password setelah login pertama!**

---

## 🛠️ Tech Stack

- **Backend:** CodeIgniter 4.6.4, PHP 8.1+
- **Database:** PostgreSQL 12+
- **Frontend:** Bootstrap 5.3, NiceAdmin Template
- **Icons:** Bootstrap Icons, Boxicons, Remix Icons
- **Security:** bcrypt, CSRF, Session-based auth

---

## 📸 Screenshots

### Login Page
Landing page dengan gradient purple background

### Dashboard
Modern dashboard dengan statistics cards

### User Management
Full CRUD dengan table, forms, dan modals

---

## ✅ Testing Checklist

- [x] Login dengan user valid
- [x] Login dengan user invalid (error)
- [x] Registration user baru
- [x] Admin approve user baru
- [x] User CRUD (Create, Read, Update, Delete)
- [x] Soft delete & audit trail
- [x] Session & logout
- [x] Password hashing verified
- [x] Middleware protection
- [x] CSRF validation

---

## 🔮 Roadmap / Next Development

### Phase 1 - Core Features
- [ ] Role & Permission System
- [ ] Asset Management Module
- [ ] Asset Assignment
- [ ] Asset Categories

### Phase 2 - Enhancement
- [ ] Email Verification
- [ ] Forgot Password
- [ ] Profile Management
- [ ] Activity Logs

### Phase 3 - Advanced
- [ ] Dashboard Analytics
- [ ] Reports & Export
- [ ] Barcode/QR Scanner
- [ ] Mobile App Integration

---

## 🤝 Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

- **CodeIgniter 4** - MIT License
- **NiceAdmin Template** - BootstrapMade License
- **Project Code** - MIT License

---

## 🙏 Credits

- **Framework:** [CodeIgniter 4](https://codeigniter.com)
- **Template:** [NiceAdmin](https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/) by BootstrapMade
- **Icons:** Bootstrap Icons, Boxicons, Remix Icons
- **CSS Framework:** Bootstrap 5

---

## 📞 Support

- **Documentation:** Lihat folder `docs/` atau file markdown di root
- **Issues:** Create issue di repository
- **CodeIgniter Docs:** https://codeigniter.com/user_guide/

---

## 🎉 Status

**✅ Production Ready**

- Version: 1.0.0
- Last Updated: January 12, 2026
- Status: Stable & Ready to Use

---

**Made with ❤️ using CodeIgniter 4 & NiceAdmin**

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
