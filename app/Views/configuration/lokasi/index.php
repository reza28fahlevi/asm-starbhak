<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Lokasi</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>

                <table id="lokasiTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Level</th>
                            <th>Nama Lokasi</th>
                            <th>Induk</th>
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
                <h5 class="modal-title" id="modalTitle">Tambah Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="lokasiForm">
                <div class="modal-body">
                    <input type="hidden" id="lokasiId" name="id">
                    
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="number" class="form-control" id="level" name="level">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="induk" class="form-label">Induk Lokasi</label>
                        <select class="form-select" id="induk" name="induk">
                            <option value="">-- Pilih Induk Lokasi --</option>
                        </select>
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
    // Load lokasi options
    function loadLokasiOptions() {
        $.ajax({
            url: '<?= base_url('lokasi/getOptions') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const select = $('#induk');
                select.find('option:not(:first)').remove();
                response.forEach(function(lokasi) {
                    select.append(`<option value="${lokasi.id}">${lokasi.nama}</option>`);
                });
            }
        });
    }

    loadLokasiOptions();

    // Initialize DataTable
    const table = $('#lokasiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('lokasi/getData') ?>',
            type: 'GET'
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'level' },
            { data: 'nama' },
            { data: 'induk' },
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
        $('#lokasiForm')[0].reset();
        $('#lokasiId').val('');
        $('#modalTitle').text('Tambah Lokasi');
        $('.form-control').removeClass('is-invalid');
    });

    // Handle form submit
    $('#lokasiForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#lokasiId').val();
        const url = id ? `<?= base_url('lokasi/update') ?>/${id}` : '<?= base_url('lokasi/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    loadLokasiOptions();
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
    $('#lokasiTable').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `<?= base_url('lokasi/show') ?>/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#lokasiId').val(response.data.id);
                    $('#level').val(response.data.level);
                    $('#nama').val(response.data.nama);
                    $('#induk').val(response.data.induk);
                    $('#modalTitle').text('Edit Lokasi');
                    $('#modalForm').modal('show');
                }
            }
        });
    });

    // Handle delete button
    $('#lokasiTable').on('click', '.btn-delete', function() {
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
                    url: `<?= base_url('lokasi/delete') ?>/${id}`,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            loadLokasiOptions();
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
