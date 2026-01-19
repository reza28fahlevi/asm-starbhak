<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Daftar User</h5>
          <a href="<?= base_url('user/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah User
          </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show d-none" role="alert">
            <?= session()->getFlashdata('success') ?>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show d-none" role="alert">
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>

        <!-- Table with hoverable rows -->
        <table id="userTable" class="table table-hover">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Username</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              <th scope="col">No. HP</th>
              <th scope="col">Departemen</th>
              <th scope="col">Status</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr>
                <td colspan="8" class="text-center">Tidak ada data user</td>
              </tr>
            <?php else: ?>
              <?php foreach ($users as $index => $user): ?>
                <tr>
                  <th scope="row"><?= $index + 1 ?></th>
                  <td><?= esc($user['username']) ?></td>
                  <td><?= esc($user['nama']) ?></td>
                  <td><?= esc($user['email']) ?></td>
                  <td><?= esc($user['nohp'] ?? '-') ?></td>
                  <td>
                    <?php
                    $dept = ['', 'IT', 'Finance', 'HR', 'Operations'];
                    echo $user['departemen_id'] ? $dept[$user['departemen_id']] ?? '-' : '-';
                    ?>
                  </td>
                  <td>
                    <?php if ($user['active']): ?>
                      <span class="badge bg-success">Aktif</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?= base_url('user/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                            data-id="<?= $user['id'] ?>" 
                            data-username="<?= esc($user['username']) ?>" 
                            title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
        <!-- End Table with hoverable rows -->

      </div>
    </div>

 script>
$(document).ready(function() {
  // Initialize DataTable
  const table = $('#userTable').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
    },
    "pageLength": 10,
    "ordering": true,
    "searching": true,
    "responsive": true,
    "columnDefs": [
      { "orderable": false, "targets": [7] } // Disable sorting on Action column
    ]
  });

  // Delete button with SweetAlert
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
        // Create form and submit
        const form = $('<form>', {
          'method': 'POST',
          'action': '<?= base_url('user/delete/') ?>' + userId
        });
        
        // Add CSRF token
        form.append($('<input>', {
          'type': 'hidden',
          'name': '<?= csrf_token() ?>',
          'value': '<?= csrf_hash() ?>'
        }));
        
        $('body').append(form);
        form.submit();
      }
    });
  });
  
  // Toggle active status with AJAX
  window.toggleActive = function(userId, currentStatus) {
    $.ajax({
      url: '<?= base_url('user/toggleActive/') ?>' + userId,
      type: 'POST',
      data: {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message);
          setTimeout(() => location.reload(), 1000);
        } else {
          toastr.error(response.message);
        }
      },
      error: function() {
        toastr.error('Terjadi kesalahan pada server');
      }
    });
  };
}); document.getElementById('deleteUsername').textContent = username;
  document.getElementById('deleteForm').action = '<?= base_url('user/delete/') ?>' + id;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?= $this->endSection() ?>
