<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Departemen</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>

                <table id="departemenTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Departemen</th>
                            <th>Deskripsi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="departemenForm">
                <div class="modal-body">
                    <input type="hidden" id="departemenId" name="id">
                    
                    <div class="mb-3">
                        <label for="nama_departemen" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#departemenTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('departemen/getData') ?>',
            type: 'GET'
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nama_departemen' },
            { data: 'deskripsi' },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info btn-edit" data-id="${row.id}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Reset form when modal is closed
    $('#modalForm').on('hidden.bs.modal', function() {
        $('#departemenForm')[0].reset();
        $('#departemenId').val('');
        $('#modalTitle').text('Tambah Departemen');
        $('.form-control').removeClass('is-invalid');
    });

    // Handle form submit
    $('#departemenForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#departemenId').val();
        const url = id ? `<?= base_url('departemen/update') ?>/${id}` : '<?= base_url('departemen/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}`).next('.invalid-feedback').text(value);
                        });
                    }
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan pada server');
            }
        });
    });

    // Handle edit button
    $('#departemenTable').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `<?= base_url('departemen/show') ?>/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#departemenId').val(response.data.id);
                    $('#nama_departemen').val(response.data.nama_departemen);
                    $('#deskripsi').val(response.data.deskripsi);
                    $('#modalTitle').text('Edit Departemen');
                    $('#modalForm').modal('show');
                }
            }
        });
    });

    // Handle delete button
    $('#departemenTable').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('departemen/delete') ?>/${id}`,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
