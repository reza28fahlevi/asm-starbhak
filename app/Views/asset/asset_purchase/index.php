<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Daftar Pembelian Asset</h5>
                <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                    <i class="bi bi-plus-circle"></i> Buat Purchase Order
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablePembelian">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nomor PO</th>
                            <th>Nomor Permohonan</th>
                            <th>Supplier</th>
                            <th>Tanggal</th>
                            <th>Total</th>
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
                <h5 class="modal-title" id="modalTitle">Buat Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPembelian">
                <div class="modal-body">
                    <input type="hidden" id="pembelian_id" name="pembelian_id">
                    
                    <div class="mb-3">
                        <label for="permohonan_id" class="form-label">Permohonan Asset <span class="text-danger">*</span></label>
                        <select class="form-select" id="permohonan_id" name="permohonan_id" required>
                            <option value="">Pilih Permohonan</option>
                        </select>
                        <div class="invalid-feedback" id="error-permohonan_id"></div>
                        <small class="text-muted">Hanya permohonan yang sudah disetujui</small>
                    </div>

                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">Pilih Supplier</option>
                        </select>
                        <div class="invalid-feedback" id="error-supplier_id"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian">
                        <div class="invalid-feedback" id="error-tanggal_pembelian"></div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
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
                <h5 class="modal-title">Detail Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Nomor PO</th>
                                <td id="detail_nomor"></td>
                            </tr>
                            <tr>
                                <th>Nomor Permohonan</th>
                                <td id="detail_permohonan"></td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td id="detail_supplier"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Tanggal</th>
                                <td id="detail_tanggal"></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td id="detail_total"></td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td id="detail_keterangan"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Detail Item</h6>
                    <button type="button" class="btn btn-success btn-sm" id="btnAddDetail">
                        <i class="bi bi-plus-circle"></i> Tambah Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tableDetail">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th>Kelompok</th>
                                <th>Nama Item</th>
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
                <h5 class="modal-title">Tambah Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDetail">
                <div class="modal-body">
                    <input type="hidden" id="detail_pembelian_id" name="pembelian_id">
                    
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori Asset</label>
                        <select class="form-select" id="kategori_id" name="kategori_id">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kelompok_id" class="form-label">Kelompok Asset <span class="text-danger">*</span></label>
                        <select class="form-select" id="kelompok_id" name="kelompok_id" required>
                            <option value="">Pilih Kelompok</option>
                        </select>
                        <div class="invalid-feedback" id="error-kelompok_id"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_item" class="form-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                        <div class="invalid-feedback" id="error-nama_item"></div>
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

                    <div class="mb-3">
                        <label for="detail_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="detail_keterangan" name="keterangan" rows="2"></textarea>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
$(document).ready(function() {
    let table;
    let currentPembelianId = null;

    // Load options
    loadOptions();

    // Initialize DataTable
    table = $('#tablePembelian').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('pembelian-asset/getData') ?>',
            type: 'GET'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nomor_pembelian' },
            { data: 'nomor_permohonan' },
            { data: 'nama_supplier', 
              render: function(data) {
                  return data || '-';
              }
            },
            { data: 'tanggal_pembelian_formatted' },
            { data: 'total_anggaran_formatted' },
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

    function loadOptions() {
        $.ajax({
            url: '<?= base_url('pembelian-asset/getOptions') ?>',
            type: 'GET',
            success: function(response) {
                // Populate permohonan
                $('#permohonan_id').empty().append('<option value="">Pilih Permohonan</option>');
                response.permohonan.forEach(function(item) {
                    $('#permohonan_id').append(`<option value="${item.id}">${item.nomor_permohonan} - ${item.departemen} (Rp ${parseInt(item.total_anggaran).toLocaleString('id-ID')})</option>`);
                });

                // Populate supplier
                $('#supplier_id').empty().append('<option value="">Pilih Supplier</option>');
                response.supplier.forEach(function(item) {
                    $('#supplier_id').append(`<option value="${item.id}">${item.nama_supplier}</option>`);
                });

                // Populate kategori
                $('#kategori_id').empty().append('<option value="">Pilih Kategori</option>');
                response.kategori.forEach(function(item) {
                    $('#kategori_id').append(`<option value="${item.id}">${item.nama_kategori}</option>`);
                });

                // Populate kelompok
                $('#kelompok_id').empty().append('<option value="">Pilih Kelompok</option>');
                response.kelompok.forEach(function(item) {
                    $('#kelompok_id').append(`<option value="${item.id}">${item.nama_kelompok}</option>`);
                });
            }
        });
    }

    $('#btnAdd').click(function() {
        loadOptions(); // Refresh options
        $('#modalTitle').text('Buat Purchase Order');
        $('#formPembelian')[0].reset();
        $('#pembelian_id').val('');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#modalForm').modal('show');
    });

    $('#formPembelian').submit(function(e) {
        e.preventDefault();
        
        const id = $('#pembelian_id').val();
        const url = id ? `<?= base_url('pembelian-asset/update') ?>/${id}` : '<?= base_url('pembelian-asset/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                    loadOptions(); // Refresh options after creating PO
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

    window.edit = function(id) {
        $.ajax({
            url: `<?= base_url('pembelian-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalTitle').text('Edit Purchase Order');
                    $('#pembelian_id').val(response.data.id);
                    $('#supplier_id').val(response.data.supplier_id);
                    $('#tanggal_pembelian').val(response.data.tanggal_pembelian);
                    $('#keterangan').val(response.data.keterangan);
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('#modalForm').modal('show');
                }
            }
        });
    };

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
                    url: `<?= base_url('pembelian-asset/delete') ?>/${id}`,
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

    window.showDetail = function(id) {
        currentPembelianId = id;
        
        $.ajax({
            url: `<?= base_url('pembelian-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    $('#detail_nomor').text(data.nomor_pembelian || '-');
                    $('#detail_permohonan').text(data.nomor_permohonan || '-');
                    $('#detail_supplier').text(data.nama_supplier || '-');
                    $('#detail_tanggal').text(data.tanggal_pembelian ? new Date(data.tanggal_pembelian).toLocaleDateString('id-ID') : '-');
                    $('#detail_total').text('Rp ' + (parseInt(data.total_anggaran) || 0).toLocaleString('id-ID'));
                    $('#detail_keterangan').text(data.keterangan || '-');
                    
                    loadDetailTable(data.details || []);
                    
                    $('#modalDetail').modal('show');
                }
            }
        });
    };

    function loadDetailTable(details) {
        let html = '';
        let grandTotal = 0;
        
        details.forEach(function(item, index) {
            const subtotal = (parseFloat(item.harga) || 0) * (parseFloat(item.qty) || 0);
            grandTotal += subtotal;
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_kategori || '-'}</td>
                    <td>${item.nama_kelompok || '-'}</td>
                    <td>${item.nama_item || '-'}</td>
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

    $('#btnAddDetail').click(function() {
        $('#formDetail')[0].reset();
        $('#detail_pembelian_id').val(currentPembelianId);
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#modalFormDetail').modal('show');
    });

    $('#formDetail').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('pembelian-asset/storeDetail') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalFormDetail').modal('hide');
                    toastr.success(response.message);
                    showDetail(currentPembelianId);
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
                    url: `<?= base_url('pembelian-asset/deleteDetail') ?>/${id}`,
                    type: 'POST',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            showDetail(currentPembelianId);
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
