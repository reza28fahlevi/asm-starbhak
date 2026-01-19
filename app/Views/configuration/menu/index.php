<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Menu</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>

                <table id="menuTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Menu</th>
                            <th>URL</th>
                            <th>Icon</th>
                            <th>Level</th>
                            <th>Posisi</th>
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
                <h5 class="modal-title" id="modalTitle">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="menuForm">
                <div class="modal-body">
                    <input type="hidden" id="menuId" name="id">
                    
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Menu</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">-- Root Menu --</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL</label>
                        <input type="text" class="form-control" id="url" name="url">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="bi bi-grid">
                        <small class="text-muted">Contoh: bi bi-grid, bi bi-menu-button-wide</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <input type="number" class="form-control" id="level" name="level" value="1">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pos" class="form-label">Posisi</label>
                                <input type="number" class="form-control" id="pos" name="pos" value="1">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
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
    // Load parent menu options
    function loadMenuOptions() {
        $.ajax({
            url: '<?= base_url('menu/getOptions') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const select = $('#parent_id');
                select.find('option:not(:first)').remove();
                response.forEach(function(menu) {
                    select.append(`<option value="${menu.menu_id}">${menu.nama}</option>`);
                });
            }
        });
    }

    loadMenuOptions();

    // Initialize DataTable
    const table = $('#menuTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('menu/getData') ?>',
            type: 'GET'
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nama' },
            { data: 'url' },
            { 
                data: 'icon',
                render: function(data, type, row) {
                    return data ? `<i class="${data}"></i> ${data}` : '';
                }
            },
            { data: 'level' },
            { data: 'pos' },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info btn-edit" data-id="${row.menu_id}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${row.menu_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        order: [[4, 'asc'], [5, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Reset form when modal is closed
    $('#modalForm').on('hidden.bs.modal', function() {
        $('#menuForm')[0].reset();
        $('#menuId').val('');
        $('#modalTitle').text('Tambah Menu');
        $('.form-control').removeClass('is-invalid');
    });

    // Handle form submit
    $('#menuForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#menuId').val();
        const url = id ? `<?= base_url('menu/update') ?>/${id}` : '<?= base_url('menu/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    loadMenuOptions();
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
    $('#menuTable').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `<?= base_url('menu/show') ?>/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#menuId').val(response.data.menu_id);
                    $('#parent_id').val(response.data.parent_id);
                    $('#nama').val(response.data.nama);
                    $('#url').val(response.data.url);
                    $('#icon').val(response.data.icon);
                    $('#level').val(response.data.level);
                    $('#pos').val(response.data.pos);
                    $('#modalTitle').text('Edit Menu');
                    $('#modalForm').modal('show');
                }
            }
        });
    });

    // Handle delete button
    $('#menuTable').on('click', '.btn-delete', function() {
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
                    url: `<?= base_url('menu/delete') ?>/${id}`,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            loadMenuOptions();
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
