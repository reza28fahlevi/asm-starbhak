# Template Layout NiceAdmin - CodeIgniter 4

## Struktur Layout

Template ini menggunakan sistem layout modular dengan komponen:

### 1. Layout Files
- `app/Views/layout/header.php` - Header dengan logo, search bar, notifikasi, dan profil user
- `app/Views/layout/sidebar.php` - Sidebar menu navigasi
- `app/Views/layout/footer.php` - Footer dengan copyright dan scripts
- `app/Views/layout/main.php` - Layout utama yang menggabungkan header, sidebar, dan footer

### 2. Assets
- Folder `NiceAdmin/assets` sudah dicopy ke `public/assets`
- Semua CSS, JS, images, dan vendor files tersedia

## Cara Menggunakan Template

### 1. Membuat View Baru

Buat file view baru dan extend dari `layout/main`:

```php
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Konten halaman Anda di sini -->
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Judul Halaman</h5>
        <p>Konten halaman...</p>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
```

### 2. Membuat Controller

```php
<?php

namespace App\Controllers;

class NamaController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Judul Page',           // Untuk <title> tag
            'page_title' => 'Judul Halaman',   // Untuk heading di page
            'breadcrumbs' => ['Home', 'Page']  // Untuk breadcrumb navigation
        ];

        return view('folder/namaview', $data);
    }
}
```

### 3. Menambahkan Routes

Di `app/Config/Routes.php`:

```php
$routes->get('/url-anda', 'NamaController::index');
```

## Contoh Implementasi

### Dashboard (Sudah Ada)
- **Route:** `/` atau `/dashboard`
- **Controller:** `app/Controllers/Dashboard.php`
- **View:** `app/Views/dashboard/index.php`

### About Page (Sudah Ada)
- **Route:** `/pages/about`
- **Controller:** `app/Controllers/Pages.php`
- **View:** `app/Views/pages/about.php`

## Menjalankan Aplikasi

```bash
# Development server
php spark serve

# Atau dengan custom port
php spark serve --port=8080
```

Akses aplikasi di browser:
- Dashboard: http://localhost:8080
- About: http://localhost:8080/pages/about

## Kustomisasi

### Mengubah Logo & Nama Aplikasi
Edit file `app/Views/layout/header.php`:
```php
<span class="d-none d-lg-block">Nama Aplikasi Anda</span>
```

### Menambah Menu Sidebar
Edit file `app/Views/layout/sidebar.php` dan tambahkan menu item:
```php
<li class="nav-item">
  <a class="nav-link collapsed" href="<?= base_url('/url-anda') ?>">
    <i class="bi bi-icon-name"></i>
    <span>Nama Menu</span>
  </a>
</li>
```

### Active Menu
Menu akan otomatis active berdasarkan URL saat ini. Contoh sudah ada di sidebar untuk Dashboard.

## Fitur Template

- ✅ Responsive Bootstrap 5
- ✅ Header dengan search, notifikasi, dan profil dropdown
- ✅ Sidebar menu dengan collapse submenu
- ✅ Breadcrumb navigation otomatis
- ✅ Footer dengan copyright
- ✅ Back to top button
- ✅ Bootstrap Icons, Boxicons, Remix Icons
- ✅ Chart.js, ApexCharts, ECharts support
- ✅ DataTables support
- ✅ Quill editor support

## Bootstrap Components Available

Anda dapat menggunakan semua komponen Bootstrap 5:
- Cards
- Alerts
- Badges
- Buttons
- Forms
- Tables
- Modals
- Tabs
- Progress bars
- Dan lainnya

Lihat dokumentasi Bootstrap 5 untuk detail: https://getbootstrap.com/docs/5.3/
