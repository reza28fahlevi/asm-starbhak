<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Kategori Asset</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>

                <table id="kategoriAssetTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kategori</th>
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
                <h5 class="modal-title" id="modalTitle">Tambah Kategori Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kategoriAssetForm">
                <div class="modal-body">
                    <input type="hidden" id="kategoriAssetId" name="id">
                    
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
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
    const table = $('#kategoriAssetTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('kategori-asset/getData') ?>',
            type: 'GET'
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nama_kategori' },
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
        $('#kategoriAssetForm')[0].reset();
        $('#kategoriAssetId').val('');
        $('#modalTitle').text('Tambah Kategori Asset');
        $('.form-control').removeClass('is-invalid');
    });

    // Handle form submit
    $('#kategoriAssetForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#kategoriAssetId').val();
        const url = id ? `<?= base_url('kategori-asset/update') ?>/${id}` : '<?= base_url('kategori-asset/store') ?>';
        
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
    $('#kategoriAssetTable').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `<?= base_url('kategori-asset/show') ?>/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#kategoriAssetId').val(response.data.id);
                    $('#nama_kategori').val(response.data.nama_kategori);
                    $('#modalTitle').text('Edit Kategori Asset');
                    $('#modalForm').modal('show');
                }
            }
        });
    });

    // Handle delete button
    $('#kategoriAssetTable').on('click', '.btn-delete', function() {
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
                    url: `<?= base_url('kategori-asset/delete') ?>/${id}`,
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
