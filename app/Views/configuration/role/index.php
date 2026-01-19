<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Daftar Role</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Tambah Role
          </button>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
          <table id="roleTable" class="table table-hover table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Role</th>
                <th width="50%">Keterangan</th>
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
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="roleModalLabel">Tambah Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="roleForm">
        <div class="modal-body">
          <input type="hidden" id="role_id" name="role_id">
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Role <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" required>
            <div class="invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
            <div class="invalid-feedback"></div>
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
  table = $('#roleTable').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
      "url": "<?= base_url('role/getData') ?>",
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
      { "data": "nama" },
      { "data": "keterangan" },
      {
        "data": null,
        "orderable": false,
        "render": function(data, type, row) {
          return `
            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.role_id}" title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.role_id}" data-nama="${row.nama}" title="Hapus">
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
  console.log('AJAX URL:', '<?= base_url('role/getData') ?>');

  // Reset Form
  window.resetForm = function() {
    isEdit = false;
    $('#roleForm')[0].reset();
    $('#role_id').val('');
    $('#roleModalLabel').text('Tambah Role');
    $('#btnSubmit').html('<i class="bi bi-save"></i> Simpan');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
  };

  // Submit Form
  $('#roleForm').on('submit', function(e) {
    e.preventDefault();
    
    const roleId = $('#role_id').val();
    const url = isEdit 
      ? '<?= base_url('role/update/') ?>' + roleId
      : '<?= base_url('role/store') ?>';
    
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
          $('#roleModal').modal('hide');
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
      },
      complete: function() {
        $('#btnSubmit').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan');
      }
    });
  });

  // Edit Button
  $('#roleTable').on('click', '.btn-edit', function() {
    const roleId = $(this).data('id');
    
    $.ajax({
      url: '<?= base_url('role/show/') ?>' + roleId,
      type: 'GET',
      success: function(response) {
        if (response.success) {
          isEdit = true;
          const data = response.data;
          
          $('#role_id').val(data.role_id);
          $('#nama').val(data.nama);
          $('#keterangan').val(data.keterangan);
          
          $('#roleModalLabel').text('Edit Role');
          $('#btnSubmit').html('<i class="bi bi-save"></i> Update');
          
          $('#roleModal').modal('show');
        } else {
          toastr.error(response.message);
        }
      },
      error: function() {
        toastr.error('Gagal memuat data role');
      }
    });
  });

  // Delete Button
  $('#roleTable').on('click', '.btn-delete', function() {
    const roleId = $(this).data('id');
    const nama = $(this).data('nama');
    
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: `Apakah Anda yakin ingin menghapus role <strong>${nama}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?= base_url('role/delete/') ?>' + roleId,
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
            toastr.error('Gagal menghapus role');
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
