<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Daftar Permohonan Asset</h5>
                <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                    <i class="bi bi-plus-circle"></i> Tambah Permohonan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablePermohonan">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nomor Permohonan</th>
                            <th>Departemen</th>
                            <th>Pemohon</th>
                            <th>Total Anggaran</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Permohonan Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPermohonan">
                <div class="modal-body">
                    <input type="hidden" id="permohonan_id" name="permohonan_id">
                    
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Departemen <span class="text-danger">*</span></label>
                        <select class="form-select" id="departemen" name="departemen" required>
                            <option value="">Pilih Departemen</option>
                        </select>
                        <div class="invalid-feedback" id="error-departemen"></div>
                    </div>

                    <div class="mb-3">
                        <label for="pemohon" class="form-label">Pemohon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pemohon" name="pemohon" required>
                        <div class="invalid-feedback" id="error-pemohon"></div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan_tujuan" class="form-label">Keterangan/Tujuan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keterangan_tujuan" name="keterangan_tujuan" rows="3" required></textarea>
                        <div class="invalid-feedback" id="error-keterangan_tujuan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Permohonan Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Nomor Permohonan</th>
                                <td id="detail_nomor"></td>
                            </tr>
                            <tr>
                                <th>Departemen</th>
                                <td id="detail_departemen"></td>
                            </tr>
                            <tr>
                                <th>Pemohon</th>
                                <td id="detail_pemohon"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Keterangan/Tujuan</th>
                                <td id="detail_keterangan"></td>
                            </tr>
                            <tr>
                                <th>Total Anggaran</th>
                                <td id="detail_total"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="detail_status"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Detail Item Asset</h6>
                    <button type="button" class="btn btn-success btn-sm" id="btnAddDetail">
                        <i class="bi bi-plus-circle"></i> Tambah Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tableDetail">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kelompok Asset</th>
                                <th>Nama Asset</th>
                                <th>Keterangan</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Subtotal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailBody">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Total:</th>
                                <th id="grandTotal">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Detail -->
<div class="modal fade" id="modalFormDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDetail">
                <div class="modal-body">
                    <input type="hidden" id="detail_permohonan_id" name="permohonan_id">
                    
                    <div class="mb-3">
                        <label for="kelompok_id" class="form-label">Kelompok Asset <span class="text-danger">*</span></label>
                        <select class="form-select" id="kelompok_id" name="kelompok_id" required>
                            <option value="">Pilih Kelompok</option>
                        </select>
                        <div class="invalid-feedback" id="error-kelompok_id"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_asset" class="form-label">Nama Asset <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_asset" name="nama_asset" required>
                        <div class="invalid-feedback" id="error-nama_asset"></div>
                    </div>

                    <div class="mb-3">
                        <label for="detail_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="detail_keterangan" name="keterangan" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qty" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="qty" name="qty" step="1" required>
                                <div class="invalid-feedback" id="error-qty"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
                                <div class="invalid-feedback" id="error-harga"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let table;
    let currentPermohonanId = null;
    let departemenOptions = [];
    let kelompokOptions = [];

    // Load options
    loadOptions();

    // Initialize DataTable
    table = $('#tablePermohonan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('permohonan-asset/getData') ?>',
            type: 'GET'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nomor_permohonan' },
            { data: 'departemen' },
            { data: 'pemohon' },
            { data: 'total_anggaran_formatted' },
            { data: 'status_badge' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-info btn-sm" onclick="showDetail(${row.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="edit(${row.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteData(${row.id})">
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

    // Load options
    function loadOptions() {
        $.ajax({
            url: '<?= base_url('permohonan-asset/getOptions') ?>',
            type: 'GET',
            success: function(response) {
                departemenOptions = response.departemen;
                kelompokOptions = response.kelompok;
                
                // Populate departemen
                $('#departemen').empty().append('<option value="">Pilih Departemen</option>');
                response.departemen.forEach(function(item) {
                    $('#departemen').append(`<option value="${item.nama_departemen}">${item.nama_departemen}</option>`);
                });

                // Populate kelompok
                $('#kelompok_id').empty().append('<option value="">Pilih Kelompok</option>');
                response.kelompok.forEach(function(item) {
                    $('#kelompok_id').append(`<option value="${item.id}">${item.nama_kelompok}</option>`);
                });
            }
        });
    }

    // Add button
    $('#btnAdd').click(function() {
        $('#modalTitle').text('Tambah Permohonan Asset');
        $('#formPermohonan')[0].reset();
        $('#permohonan_id').val('');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#modalForm').modal('show');
    });

    // Form submit
    $('#formPermohonan').submit(function(e) {
        e.preventDefault();
        
        const id = $('#permohonan_id').val();
        const url = id ? `<?= base_url('permohonan-asset/update') ?>/${id}` : '<?= base_url('permohonan-asset/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(key) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(response.errors[key]);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            }
        });
    });

    // Edit
    window.edit = function(id) {
        $.ajax({
            url: `<?= base_url('permohonan-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalTitle').text('Edit Permohonan Asset');
                    $('#permohonan_id').val(response.data.id);
                    $('#departemen').val(response.data.departemen);
                    $('#pemohon').val(response.data.pemohon);
                    $('#keterangan_tujuan').val(response.data.keterangan_tujuan);
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('#modalForm').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    };

    // Delete
    window.deleteData = function(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('permohonan-asset/delete') ?>/${id}`,
                    type: 'POST',
                    success: function(response) {
                        if (response.status === 'success') {
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    };

    // Show detail
    window.showDetail = function(id) {
        currentPermohonanId = id;
        
        $.ajax({
            url: `<?= base_url('permohonan-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    // Fill header info
                    $('#detail_nomor').text(data.nomor_permohonan || '-');
                    $('#detail_departemen').text(data.departemen || '-');
                    $('#detail_pemohon').text(data.pemohon || '-');
                    $('#detail_keterangan').text(data.keterangan_tujuan || '-');
                    $('#detail_total').text('Rp ' + (parseInt(data.total_anggaran) || 0).toLocaleString('id-ID'));
                    
                    // Status badge
                    let statusBadge = '';
                    switch (data.status) {
                        case 0:
                            statusBadge = '<span class="badge bg-warning">Pending</span>';
                            break;
                        case 1:
                            statusBadge = '<span class="badge bg-success">Approved</span>';
                            break;
                        case 2:
                            statusBadge = '<span class="badge bg-danger">Rejected</span>';
                            break;
                        default:
                            statusBadge = '<span class="badge bg-secondary">Draft</span>';
                    }
                    $('#detail_status').html(statusBadge);
                    
                    // Fill detail table
                    loadDetailTable(data.details || []);
                    
                    $('#modalDetail').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    };

    // Load detail table
    function loadDetailTable(details) {
        let html = '';
        let grandTotal = 0;
        
        details.forEach(function(item, index) {
            const subtotal = (parseFloat(item.harga) || 0) * (parseFloat(item.qty) || 0);
            grandTotal += subtotal;
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_kelompok || '-'}</td>
                    <td>${item.nama_asset || '-'}</td>
                    <td>${item.keterangan || '-'}</td>
                    <td>${item.qty || 0}</td>
                    <td>Rp ${(parseFloat(item.harga) || 0).toLocaleString('id-ID')}</td>
                    <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteDetail(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        if (details.length === 0) {
            html = '<tr><td colspan="8" class="text-center">Belum ada detail item</td></tr>';
        }
        
        $('#detailBody').html(html);
        $('#grandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
    }

    // Add detail
    $('#btnAddDetail').click(function() {
        $('#formDetail')[0].reset();
        $('#detail_permohonan_id').val(currentPermohonanId);
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#modalFormDetail').modal('show');
    });

    // Form detail submit
    $('#formDetail').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('permohonan-asset/storeDetail') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalFormDetail').modal('hide');
                    toastr.success(response.message);
                    // Reload detail
                    showDetail(currentPermohonanId);
                    // Reload main table
                    table.ajax.reload(null, false);
                } else {
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(key) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(response.errors[key]);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            }
        });
    });

    // Delete detail
    window.deleteDetail = function(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Item ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('permohonan-asset/deleteDetail') ?>/${id}`,
                    type: 'POST',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            // Reload detail
                            showDetail(currentPermohonanId);
                            // Reload main table
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    };
});
</script>

<?= $this->endSection() ?>
