# 📚 Functions Helper - Dokumentasi

## File Location
`app/Helpers/functions_helper.php`

## Auto-Load
Helper sudah di-autoload di `app/Config/Autoload.php`:
```php
public $helpers = ['url', 'form', 'filesystem', 'date', 'functions'];
```

Jika belum di-autoload, load manual di Controller:
```php
helper('functions');
```

---

## 📋 Daftar Fungsi

### 1. Format Tanggal & Waktu

#### `format_date($date, $format = 'short')`
Format tanggal ke format Indonesia.

**Parameters:**
- `$date` (string): Tanggal yang akan diformat
- `$format` (string): Format output ('short', 'long', 'full')

**Return:** string

**Contoh:**
```php
echo format_date('2026-01-13');              // 13 Jan 2026
echo format_date('2026-01-13', 'long');      // 13 Januari 2026
echo format_date('2026-01-13 10:30', 'full'); // Senin, 13 Januari 2026 10:30
```

**Usage di View:**
```php
<td><?= format_date($user['created_at'], 'long') ?></td>
```

---

#### `indonesian_day($date)`
Dapatkan nama hari dalam Bahasa Indonesia.

**Contoh:**
```php
echo indonesian_day('2026-01-13'); // Senin
```

---

#### `indonesian_month($month)`
Dapatkan nama bulan dalam Bahasa Indonesia.

**Contoh:**
```php
echo indonesian_month(1);  // Januari
echo indonesian_month(12); // Desember
```

---

#### `time_ago($datetime)`
Convert timestamp ke format "waktu yang lalu".

**Contoh:**
```php
echo time_ago('2026-01-13 10:00:00'); // 2 jam yang lalu
```

---

### 2. Format Angka & Currency

#### `format_currency($amount, $symbol = true)`
Format angka ke format Rupiah.

**Contoh:**
```php
echo format_currency(1500000);        // Rp 1.500.000
echo format_currency(1500000, false); // 1.500.000
```

**Usage di View:**
```php
<td><?= format_currency($asset['harga']) ?></td>
```

---

#### `format_number($number, $decimals = 0)`
Format angka dengan separator.

**Contoh:**
```php
echo format_number(1234567);     // 1.234.567
echo format_number(1234.56, 2);  // 1.234,56
```

---

#### `format_filesize($bytes, $precision = 2)`
Format ukuran file dari bytes.

**Contoh:**
```php
echo format_filesize(1024);      // 1 KB
echo format_filesize(1048576);   // 1 MB
echo format_filesize(1073741824); // 1 GB
```

---

### 3. Generate Kode & String

#### `generate_code($prefix = 'AST', $length = 6)`
Generate kode unik untuk nomor asset, transaksi, dll.

**Contoh:**
```php
echo generate_code('AST', 6);    // AST-20260113-123456
echo generate_code('TRX', 8);    // TRX-20260113-12345678
echo generate_code('INV', 4);    // INV-20260113-1234
```

**Usage di Controller:**
```php
$data = [
    'nomor_asset' => generate_code('AST', 6),
    'nama'        => $this->request->getPost('nama'),
    // ...
];
```

---

#### `generate_random_string($length = 10, $type = 'alnum')`
Generate random string.

**Type:** `alpha`, `numeric`, `alnum`

**Contoh:**
```php
echo generate_random_string(10, 'alpha');   // aBcDeFgHiJ
echo generate_random_string(10, 'numeric'); // 1234567890
echo generate_random_string(10, 'alnum');   // aB12cD34eF
```

---

#### `slug($text)`
Generate URL-friendly slug.

**Contoh:**
```php
echo slug('Asset Management System'); // asset-management-system
echo slug('Komputer & Laptop');       // komputer-laptop
```

---

### 4. User & Session

#### `get_current_user($key = null)`
Dapatkan data user yang sedang login.

**Contoh:**
```php
// Get all data
$user = get_current_user();
// ['user_id' => 1, 'username' => 'admin', 'nama' => 'Administrator', ...]

// Get specific key
echo get_current_user('username'); // admin
echo get_current_user('nama');     // Administrator
```

**Usage di Controller:**
```php
$data = [
    'created_by' => get_current_user('username'),
    'created_at' => date('Y-m-d H:i:s'),
];
```

---

#### `is_logged_in()`
Cek apakah user sudah login.

**Contoh:**
```php
if (is_logged_in()) {
    // User sudah login
} else {
    // User belum login
    return redirect()->to('/login');
}
```

---

#### `user_has_role($role)`
Cek apakah user memiliki role tertentu.

**Contoh:**
```php
if (user_has_role('Admin')) {
    // User adalah Admin
}

// Multiple roles
if (user_has_role(['Admin', 'Manager'])) {
    // User adalah Admin atau Manager
}
```

**Usage di View:**
```php
<?php if (user_has_role('Admin')): ?>
    <button class="btn btn-danger">Hapus Semua</button>
<?php endif; ?>
```

---

### 5. HTML & UI Helpers

#### `status_badge($status, $labels = [])`
Generate badge HTML untuk status.

**Contoh:**
```php
echo status_badge('active');   // <span class="badge bg-success">Aktif</span>
echo status_badge('inactive'); // <span class="badge bg-danger">Nonaktif</span>
echo status_badge('pending');  // <span class="badge bg-warning">Pending</span>
echo status_badge(true);       // <span class="badge bg-success">Aktif</span>
echo status_badge(false);      // <span class="badge bg-danger">Nonaktif</span>

// Custom labels
echo status_badge('rusak', [
    'rusak' => '<span class="badge bg-danger">Rusak</span>',
    'baik'  => '<span class="badge bg-success">Baik</span>'
]);
```

**Usage di View:**
```php
<td><?= status_badge($asset['kondisi']) ?></td>
```

---

#### `active_menu($path, $class = 'active')`
Cek apakah menu sedang aktif.

**Contoh:**
```php
<li class="nav-item">
    <a class="nav-link <?= active_menu('/user') ?>" href="/user">
        User Management
    </a>
</li>
```

---

#### `array_to_options($array, $selected = '', $emptyLabel = '-- Pilih --')`
Convert array ke HTML select options.

**Contoh:**
```php
$roles = [1 => 'Admin', 2 => 'User', 3 => 'Manager'];
echo array_to_options($roles, 2);
// Output:
// <option value="">-- Pilih --</option>
// <option value="1">Admin</option>
// <option value="2" selected>User</option>
// <option value="3">Manager</option>
```

**Usage di View:**
```php
<select name="role_id" class="form-control">
    <?= array_to_options($roles, old('role_id')) ?>
</select>
```

---

### 6. Text Processing

#### `truncate_text($text, $limit = 100, $ellipsis = '...')`
Potong text dengan ellipsis.

**Contoh:**
```php
$desc = "Lorem ipsum dolor sit amet consectetur adipiscing elit...";
echo truncate_text($desc, 50); // Lorem ipsum dolor sit amet consectetur adipi...
```

---

#### `clean_string($string)`
Bersihkan string dari karakter spesial.

**Contoh:**
```php
echo clean_string('<p>Hello World! @#$%</p>'); // Hello World
```

---

#### `sanitize_filename($filename)`
Sanitize filename untuk upload.

**Contoh:**
```php
echo sanitize_filename('My File (2024).pdf');  // my_file_2024.pdf
echo sanitize_filename('Foto #1 Asset.jpg');   // foto_1_asset.jpg
```

**Usage di Controller:**
```php
$file = $this->request->getFile('foto');
$newName = sanitize_filename($file->getName());
$file->move(WRITEPATH . 'uploads', $newName);
```

---

### 7. Utility Functions

#### `get_client_ip()`
Dapatkan IP address client.

**Contoh:**
```php
$ip = get_client_ip(); // 192.168.1.100
```

**Usage untuk Logging:**
```php
$logData = [
    'user_id'    => get_current_user('user_id'),
    'ip_address' => get_client_ip(),
    'activity'   => 'Login',
    'created_at' => date('Y-m-d H:i:s')
];
```

---

#### `age_from_date($date)`
Hitung umur dari tanggal lahir.

**Contoh:**
```php
echo age_from_date('1990-01-13'); // 36
```

---

#### `flash_message($type, $message)`
Set flash message.

**Type:** `success`, `error`, `warning`, `info`

**Contoh:**
```php
flash_message('success', 'Data berhasil disimpan');
flash_message('error', 'Terjadi kesalahan');
```

**Di Controller:**
```php
if ($this->model->save($data)) {
    flash_message('success', 'Role berhasil ditambahkan');
    return redirect()->to('/role');
}
```

---

### 8. Debugging

#### `dd(...$vars)`
Dump and die (untuk debugging).

**Contoh:**
```php
$user = ['id' => 1, 'name' => 'Admin'];
dd($user); // Print dan stop execution
```

---

#### `pr($var, $die = false)`
Print readable.

**Contoh:**
```php
pr($data);        // Print saja
pr($data, true);  // Print dan die
```

---

### 9. Departemen Helpers

#### `departemen_options($selected = '')`
Generate departemen select options.

**Contoh:**
```php
<select name="departemen_id" class="form-control">
    <?= departemen_options(old('departemen_id')) ?>
</select>
```

---

#### `get_departemen_name($id)`
Dapatkan nama departemen dari ID.

**Contoh:**
```php
echo get_departemen_name(1); // IT
echo get_departemen_name(2); // Finance
```

**Usage di View:**
```php
<td><?= get_departemen_name($user['departemen_id']) ?></td>
```

---

## 💡 Contoh Penggunaan Lengkap

### Di Controller

```php
<?php

namespace App\Controllers;

class Asset extends BaseController
{
    public function store()
    {
        $data = [
            'nomor_asset'   => generate_code('AST', 6),
            'nama'          => clean_string($this->request->getPost('nama')),
            'departemen_id' => $this->request->getPost('departemen_id'),
            'created_by'    => get_current_user('username'),
            'created_at'    => date('Y-m-d H:i:s'),
            'ip_address'    => get_client_ip()
        ];
        
        if ($this->assetModel->save($data)) {
            flash_message('success', 'Asset berhasil ditambahkan');
            return redirect()->to('/asset');
        }
        
        flash_message('error', 'Gagal menyimpan asset');
        return redirect()->back()->withInput();
    }
}
```

### Di View

```php
<!-- Tabel Asset -->
<table class="table">
    <tbody>
        <?php foreach ($assets as $asset): ?>
        <tr>
            <td><?= format_date($asset['created_at'], 'long') ?></td>
            <td><?= $asset['nomor_asset'] ?></td>
            <td><?= truncate_text($asset['keterangan'], 50) ?></td>
            <td><?= format_currency($asset['harga']) ?></td>
            <td><?= get_departemen_name($asset['departemen_id']) ?></td>
            <td><?= status_badge($asset['kondisi']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Form Select -->
<select name="departemen_id" class="form-control">
    <?= departemen_options(old('departemen_id', $asset['departemen_id'])) ?>
</select>
```

---

## 🔧 Tips & Best Practices

1. **Auto-load di Autoload.php** untuk kemudahan akses global
2. **Gunakan helper functions di View** untuk formatting data
3. **Jangan overload helper** - pisahkan helper berdasarkan kategori jika sudah terlalu banyak
4. **Type safety** - pastikan parameter sesuai type yang diharapkan
5. **Documentation** - tambahkan komentar untuk setiap fungsi baru

---

## 📝 Menambahkan Fungsi Baru

Untuk menambahkan fungsi baru:

```php
if (!function_exists('nama_fungsi')) {
    /**
     * Deskripsi fungsi
     * 
     * @param string $param
     * @return mixed
     */
    function nama_fungsi($param)
    {
        // Kode fungsi
        return $result;
    }
}
```

**Note:** Selalu cek dengan `!function_exists()` untuk menghindari conflict.

---

**Created:** January 13, 2026  
**Project:** PKM Asset Management System  
**Framework:** CodeIgniter 4.6.4
