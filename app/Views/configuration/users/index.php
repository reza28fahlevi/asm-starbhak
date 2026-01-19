<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Daftar User</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Tambah User
          </button>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
          <table id="userTable" class="table table-hover table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th width="12%">Username</th>
                <th width="18%">Nama</th>
                <th width="15%">Email</th>
                <th width="10%">No. HP</th>
                <th width="12%">Departemen</th>
                <th width="8%">Status</th>
                <th width="20%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data akan dimuat via AJAX -->
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="userForm">
        <div class="modal-body">
          <input type="hidden" id="user_id" name="user_id">
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="username" name="username" required>
              <div class="invalid-feedback"></div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
              <div class="invalid-feedback"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nama" name="nama" required>
              <div class="invalid-feedback"></div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="nohp" class="form-label">No. HP</label>
              <input type="text" class="form-control" id="nohp" name="nohp">
              <div class="invalid-feedback"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Password <span class="text-danger" id="password_required">*</span></label>
              <input type="password" class="form-control" id="password" name="password">
              <small class="text-muted" id="password_hint">Minimal 6 karakter</small>
              <div class="invalid-feedback"></div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="confirm_password" class="form-label">Konfirmasi Password <span class="text-danger" id="confirm_required">*</span></label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password">
              <div class="invalid-feedback"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="nomor_registrasi" class="form-label">Nomor Registrasi</label>
              <input type="text" class="form-control" id="nomor_registrasi" name="nomor_registrasi">
              <div class="invalid-feedback"></div>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="departemen_id" class="form-label">Departemen</label>
              <select class="form-select" id="departemen_id" name="departemen_id">
                <option value="">-- Pilih Departemen --</option>
                <option value="1">IT</option>
                <option value="2">Finance</option>
                <option value="3">HR</option>
                <option value="4">Operations</option>
              </select>
              <div class="invalid-feedback"></div>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
              <label class="form-check-label" for="active">
                Aktif
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmit">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
  let table;
  let isEdit = false;

  // Initialize DataTable
  table = $('#userTable').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
      "url": "<?= base_url('user/getData') ?>",
      "type": "GET",
      "dataSrc": "data",
      "error": function(xhr, error, code) {
        console.error('DataTables AJAX Error:');
        console.error('Status:', xhr.status);
        console.error('Error:', error);
        console.error('Code:', code);
        console.error('Response:', xhr.responseText);
        toastr.error('Gagal memuat data. Periksa console untuk detail.');
      }
    },
    "columns": [
      { "data": "no" },
      { "data": "username" },
      { "data": "nama" },
      { "data": "email" },
      { "data": "nohp" },
      { "data": "departemen" },
      { 
        "data": "active",
        "render": function(data, type, row) {
          if (data) {
            return '<span class="badge bg-success">Aktif</span>';
          } else {
            return '<span class="badge bg-danger">Nonaktif</span>';
          }
        }
      },
      {
        "data": null,
        "orderable": false,
        "render": function(data, type, row) {
          return `
            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}" title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" data-username="${row.username}" title="Hapus">
              <i class="bi bi-trash"></i>
            </button>
          `;
        }
      }
    ],
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
    },
    "pageLength": 10,
    "ordering": true,
    "searching": true,
    "responsive": true
  });
  
  // Debug: Log when DataTable is initialized
  console.log('DataTable initialized');
  console.log('AJAX URL:', '<?= base_url('user/getData') ?>');

  // Reset Form
  window.resetForm = function() {
    isEdit = false;
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#userModalLabel').text('Tambah User');
    $('#btnSubmit').html('<i class="bi bi-save"></i> Simpan');
    $('#password').prop('required', true);
    $('#confirm_password').prop('required', true);
    $('#password_required').show();
    $('#confirm_required').show();
    $('#password_hint').text('Minimal 6 karakter');
    $('#active').prop('checked', true);
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
  };

  // Submit Form
  $('#userForm').on('submit', function(e) {
    e.preventDefault();
    
    const userId = $('#user_id').val();
    const url = isEdit 
      ? '<?= base_url('user/update/') ?>' + userId
      : '<?= base_url('user/store') ?>';
    
    const formData = new FormData(this);
    
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message);
          $('#userModal').modal('hide');
          table.ajax.reload();
          resetForm();
        } else {
          toastr.error(response.message);
          
          // Show validation errors
          if (response.errors) {
            $.each(response.errors, function(field, message) {
              $('#' + field).addClass('is-invalid');
              $('#' + field).next('.invalid-feedback').text(message);
            });
          }
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Terjadi kesalahan pada server');
        console.error('Error:', xhr.responseText);
      },
      complete: function() {
        $('#btnSubmit').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan');
      }
    });
  });

  // Edit Button
  $('#userTable').on('click', '.btn-edit', function() {
    const userId = $(this).data('id');
    
    $.ajax({
      url: '<?= base_url('user/show/') ?>' + userId,
      type: 'GET',
      success: function(response) {
        if (response.success) {
          isEdit = true;
          const data = response.data;
          
          $('#user_id').val(data.id);
          $('#username').val(data.username);
          $('#email').val(data.email);
          $('#nama').val(data.nama);
          $('#nohp').val(data.nohp);
          $('#nomor_registrasi').val(data.nomor_registrasi);
          $('#departemen_id').val(data.departemen_id);
          $('#active').prop('checked', data.active == 1);
          
          // Password not required for edit
          $('#password').prop('required', false);
          $('#confirm_password').prop('required', false);
          $('#password_required').hide();
          $('#confirm_required').hide();
          $('#password_hint').text('Kosongkan jika tidak ingin mengubah password');
          
          $('#userModalLabel').text('Edit User');
          $('#btnSubmit').html('<i class="bi bi-save"></i> Update');
          
          $('#userModal').modal('show');
        } else {
          toastr.error(response.message);
        }
      },
      error: function() {
        toastr.error('Gagal memuat data user');
      }
    });
  });

  // Delete Button
  $('#userTable').on('click', '.btn-delete', function() {
    const userId = $(this).data('id');
    const username = $(this).data('username');
    
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: `Apakah Anda yakin ingin menghapus user <strong>${username}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?= base_url('user/delete/') ?>' + userId,
          type: 'POST',
          data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              table.ajax.reload();
            } else {
              toastr.error(response.message);
            }
          },
          error: function() {
            toastr.error('Gagal menghapus user');
          }
        });
      }
    });
  });

  // Clear validation on input
  $('.form-control').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').text('');
  });
});
</script>
<?= $this->endSection() ?>
