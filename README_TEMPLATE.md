# PKM ASM - CodeIgniter 4 dengan Template NiceAdmin

Project CodeIgniter 4 dengan template NiceAdmin Bootstrap yang sudah terintegrasi.

## Quick Start

```bash
# Jalankan development server
php spark serve
```

Buka browser: http://localhost:8080

## Halaman yang Tersedia

- **Dashboard**: http://localhost:8080/ 
- **About**: http://localhost:8080/pages/about
- **Form Example**: http://localhost:8080/examples/form
- **Table Example**: http://localhost:8080/examples/table

## Struktur Template

### Layout Files
```
app/Views/layout/
├── header.php   # Header dengan logo, search, notifikasi
├── sidebar.php  # Menu navigasi sidebar
├── footer.php   # Footer dan scripts
└── main.php     # Layout utama
```

### Cara Membuat Halaman Baru

1. **Buat View** di `app/Views/`:
```php
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Konten Anda -->
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Judul</h5>
    <p>Konten...</p>
  </div>
</div>

<?= $this->endSection() ?>
```

2. **Buat Controller** di `app/Controllers/`:
```php
<?php
namespace App\Controllers;

class NamaController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Page Title',
            'page_title' => 'Heading',
            'breadcrumbs' => ['Home', 'Page']
        ];
        return view('folder/file', $data);
    }
}
```

3. **Tambah Route** di `app/Config/Routes.php`:
```php
$routes->get('/url', 'NamaController::index');
```

## Data yang Bisa Dikirim ke View

```php
$data = [
    'title' => 'Judul untuk <title> tag',
    'page_title' => 'Heading halaman',
    'breadcrumbs' => ['Menu 1', 'Menu 2', 'Current']  // opsional
];
```

## Assets

Semua assets NiceAdmin tersedia di `public/assets/`:
- Bootstrap 5
- Bootstrap Icons, Boxicons, Remix Icons
- Chart.js, ApexCharts, ECharts
- DataTables
- Quill Editor

## Dokumentasi Lengkap

Lihat file `TEMPLATE_GUIDE.md` untuk dokumentasi detail.

## Fitur

✅ Responsive layout  
✅ Header dengan search, notifikasi, profil  
✅ Sidebar menu dengan collapse  
✅ Breadcrumb otomatis  
✅ Bootstrap 5 components  
✅ Icon libraries  
✅ Chart libraries  
✅ Form & table examples  

---
Template by [BootstrapMade](https://bootstrapmade.com/)
