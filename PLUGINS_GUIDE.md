# 📚 Plugin & Library Documentation

## Installed Plugins

### 1. jQuery 3.7.1
**CDN:** https://code.jquery.com/jquery-3.7.1.min.js

**Fungsi:** JavaScript library untuk manipulasi DOM, AJAX, dan event handling.

**Contoh Penggunaan:**
```javascript
// DOM manipulation
$('#userTable').DataTable();

// AJAX request
$.ajax({
  url: '/user/getData',
  type: 'GET',
  success: function(response) {
    console.log(response);
  }
});

// Event handling
$('.btn-delete').on('click', function() {
  const id = $(this).data('id');
  // do something
});
```

---

### 2. DataTables 1.13.7
**CDN:** 
- CSS: https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css
- JS: https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js

**Fungsi:** Plugin jQuery untuk membuat tabel interaktif dengan fitur sorting, searching, pagination.

**Contoh Penggunaan:**
```javascript
// Basic initialization
$('#userTable').DataTable({
  "language": {
    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
  },
  "pageLength": 10,
  "ordering": true,
  "searching": true
});

// With AJAX data source
$('#userTable').DataTable({
  "ajax": {
    "url": "/user/getData",
    "type": "GET"
  },
  "columns": [
    { "data": "no" },
    { "data": "username" },
    { "data": "nama" },
    { "data": "email" }
  ]
});
```

**Fitur:**
- ✅ Sorting (ascending/descending)
- ✅ Searching/Filter
- ✅ Pagination
- ✅ Responsive layout
- ✅ Bahasa Indonesia support

---

### 3. Toastr.js
**CDN:**
- CSS: https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css
- JS: https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js

**Fungsi:** Library untuk menampilkan notifikasi toast (pop-up notification).

**Contoh Penggunaan:**
```javascript
// Success notification
toastr.success('Data berhasil disimpan!');

// Error notification
toastr.error('Gagal menyimpan data!');

// Warning notification
toastr.warning('Peringatan: Data sudah ada!');

// Info notification
toastr.info('Memproses data...');

// Custom configuration
toastr.options = {
  "closeButton": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "timeOut": "3000"
};
```

**Di CodeIgniter (Controller):**
```php
// Success message
return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');

// Error message
return redirect()->back()->with('error', 'Gagal menyimpan data');

// Warning message
return redirect()->back()->with('warning', 'Data sudah ada');

// Info message
return redirect()->to('/dashboard')->with('info', 'Selamat datang!');
```

**Positions:**
- `toast-top-right` (default)
- `toast-top-left`
- `toast-bottom-right`
- `toast-bottom-left`
- `toast-top-center`
- `toast-bottom-center`

---

### 4. SweetAlert2
**CDN:** https://cdn.jsdelivr.net/npm/sweetalert2@11

**Fungsi:** Library untuk menampilkan dialog/modal konfirmasi yang cantik dan interaktif.

**Contoh Penggunaan:**

**Simple Alert:**
```javascript
Swal.fire('Berhasil!', 'Data berhasil disimpan', 'success');
```

**Confirm Dialog:**
```javascript
Swal.fire({
  title: 'Konfirmasi Hapus',
  text: 'Apakah Anda yakin?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#d33',
  cancelButtonColor: '#3085d6',
  confirmButtonText: 'Ya, Hapus!',
  cancelButtonText: 'Batal'
}).then((result) => {
  if (result.isConfirmed) {
    // User clicked "Ya, Hapus!"
    deleteUser(userId);
  }
});
```

**With HTML Content:**
```javascript
Swal.fire({
  title: 'Konfirmasi',
  html: 'Hapus user <strong>admin</strong>?',
  icon: 'question',
  showCancelButton: true
});
```

**Icon Types:**
- `success` - ✓ (green)
- `error` - ✗ (red)
- `warning` - ⚠ (orange)
- `info` - ℹ (blue)
- `question` - ? (gray)

---

## Custom JavaScript Functions (app.js)

### UserManager

```javascript
// Delete user with SweetAlert
UserManager.delete(userId, username, baseUrl);

// Toggle active status
UserManager.toggleActive(userId, baseUrl);

// Load user data via AJAX
UserManager.loadData(baseUrl, function(response) {
  console.log(response);
});

// Save user (create/update)
UserManager.save(formData, baseUrl, isEdit);
```

### DataTableHelper

```javascript
// Initialize DataTable
const table = DataTableHelper.init('#userTable', {
  // custom options
});

// Reload DataTable
DataTableHelper.reload(table);
```

### FormHelper

```javascript
// Get FormData from form
const formData = FormHelper.toFormData('#userForm');

// Reset form
FormHelper.reset('#userForm');

// Show validation errors
FormHelper.showErrors('#userForm', {
  'username': 'Username sudah digunakan',
  'email': 'Format email tidak valid'
});
```

### NotificationHelper

```javascript
// Success notification
NotificationHelper.success('Berhasil!');

// Error notification
NotificationHelper.error('Gagal!');

// Confirm dialog
NotificationHelper.confirm('Konfirmasi', 'Lanjutkan?', function() {
  // callback if confirmed
});

// Confirm delete
NotificationHelper.confirmDelete('Hapus data ini?', function() {
  // callback if confirmed
});
```

---

## Implementasi di Project

### 1. User Management (index.php)

**Fitur yang digunakan:**
- ✅ DataTables untuk tabel user
- ✅ SweetAlert untuk konfirmasi delete
- ✅ Toastr untuk notifikasi success/error
- ✅ jQuery AJAX untuk toggle active status

**Code:**
```javascript
$(document).ready(function() {
  // Initialize DataTable
  const table = $('#userTable').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
    }
  });

  // Delete with SweetAlert
  $('#userTable').on('click', '.btn-delete', function() {
    const userId = $(this).data('id');
    const username = $(this).data('username');
    
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: `Hapus user <strong>${username}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        // Submit delete form
      }
    });
  });
});
```

### 2. Login Page

**Fitur yang digunakan:**
- ✅ Toastr untuk notifikasi login success/error

**Code:**
```php
<?php if (session()->getFlashdata('error')): ?>
  toastr.error('<?= session()->getFlashdata('error') ?>');
<?php endif; ?>
```

### 3. Layout Footer (Global)

**Fitur yang digunakan:**
- ✅ Auto-display toastr dari flash message CodeIgniter

**Code:**
```javascript
<?php if (session()->getFlashdata('success')): ?>
  toastr.success('<?= session()->getFlashdata('success') ?>');
<?php endif; ?>
```

---

## AJAX Routes (Controller)

### User Controller

```php
/**
 * Get users data as JSON (for AJAX/DataTables)
 */
public function getData()
{
    $users = $this->userModel->getAllUsers();
    return $this->response->setJSON(['data' => $users]);
}

/**
 * Toggle user active status
 */
public function toggleActive($id)
{
    // ... code ...
    return $this->response->setJSON([
        'success' => true, 
        'message' => 'Status berhasil diubah'
    ]);
}
```

### Routes Configuration

```php
// AJAX endpoints
$routes->get('/user/getData', 'User::getData');
$routes->post('/user/toggleActive/(:num)', 'User::toggleActive/$1');
```

---

## Tips & Best Practices

### 1. CSRF Token untuk AJAX
```javascript
$.ajax({
  url: '/user/delete/1',
  type: 'POST',
  data: {
    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
  }
});
```

### 2. Loading State
```javascript
// Before AJAX
toastr.info('Memproses...');

// After success
toastr.success('Berhasil!');
```

### 3. Error Handling
```javascript
$.ajax({
  url: '/api/endpoint',
  success: function(response) {
    if (response.success) {
      toastr.success(response.message);
    } else {
      toastr.error(response.message);
    }
  },
  error: function(xhr, status, error) {
    toastr.error('Terjadi kesalahan pada server');
  }
});
```

### 4. DataTables Column Definition
```javascript
$('#table').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [0, 7] }, // Disable sort on column 0 and 7
    { "searchable": false, "targets": [7] },    // Disable search on column 7
    { "width": "20%", "targets": 0 }            // Set column width
  ]
});
```

---

## Troubleshooting

### DataTables tidak muncul
**Solusi:** Pastikan jQuery dimuat sebelum DataTables
```html
<script src="jquery.min.js"></script>
<script src="dataTables.min.js"></script>
```

### Toastr tidak muncul
**Solusi:** Cek console browser untuk error, pastikan CSS dan JS ter-load

### SweetAlert button tidak klik
**Solusi:** Pastikan tidak ada conflict dengan Bootstrap modal

### AJAX CSRF token invalid
**Solusi:** Regenerate CSRF token atau disable CSRF untuk route AJAX

---

## Resources

- **jQuery Docs:** https://api.jquery.com/
- **DataTables Docs:** https://datatables.net/
- **Toastr Docs:** https://github.com/CodeSeven/toastr
- **SweetAlert2 Docs:** https://sweetalert2.github.io/

---

**Created:** January 12, 2026  
**Project:** PKM Asset Management System  
**Framework:** CodeIgniter 4.6.4
