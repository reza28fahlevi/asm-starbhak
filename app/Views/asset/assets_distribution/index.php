<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Daftar Distribusi Asset</h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-2" id="btnCreateAsset">
                        <i class="bi bi-box-seam"></i> Buat Asset dari Penerimaan
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                        <i class="bi bi-plus-circle"></i> Distribusikan Asset
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tableDistribusi">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Asset</th>
                            <th>Nama Asset</th>
                            <th>Lokasi</th>
                            <th>Tanggal Distribusi</th>
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

<!-- Modal Form Distribusi -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Distribusikan Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDistribusi">
                <div class="modal-body">
                    <input type="hidden" id="distribusi_id" name="distribusi_id">
                    
                    <div class="mb-3">
                        <label for="asset_id" class="form-label">Asset <span class="text-danger">*</span></label>
                        <select class="form-select" id="asset_id" name="asset_id" required>
                            <option value="">Pilih Asset</option>
                        </select>
                        <div class="invalid-feedback" id="error-asset_id"></div>
                        <small class="text-muted">Hanya asset yang belum didistribusikan</small>
                    </div>

                    <div class="mb-3">
                        <label for="lokasi_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="lokasi_id" name="lokasi_id" required>
                            <option value="">Pilih Lokasi</option>
                        </select>
                        <div class="invalid-feedback" id="error-lokasi_id"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tgldistribusi" class="form-label">Tanggal Distribusi</label>
                        <input type="date" class="form-control" id="tgldistribusi" name="tgldistribusi" value="<?= date('Y-m-d') ?>">
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

<!-- Modal Create Asset -->
<div class="modal fade" id="modalCreateAsset" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Asset dari Penerimaan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Pilih barang yang sudah diterima untuk dijadikan asset</p>
                
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Kategori</th>
                                <th>Nama Item</th>
                                <th>Qty Terima</th>
                                <th width="15%">Buat Asset</th>
                            </tr>
                        </thead>
                        <tbody id="penerimaanBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Distribusi Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Kode Asset</th>
                        <td id="detail_kode"></td>
                    </tr>
                    <tr>
                        <th>Nama Asset</th>
                        <td id="detail_nama"></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td id="detail_kategori"></td>
                    </tr>
                    <tr>
                        <th>Merk</th>
                        <td id="detail_merk"></td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td id="detail_tipe"></td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td id="detail_serial"></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td id="detail_lokasi"></td>
                    </tr>
                    <tr>
                        <th>Tanggal Distribusi</th>
                        <td id="detail_tanggal"></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td id="detail_keterangan"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
$(document).ready(function() {
    let table;

    // Load options
    loadOptions();

    // Initialize DataTable
    table = $('#tableDistribusi').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('distribusi-asset/getData') ?>',
            type: 'GET'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'kode_asset' },
            { data: 'nama_asset' },
            { data: 'nama_lokasi' },
            { data: 'tgldistribusi_formatted' },
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
            url: '<?= base_url('distribusi-asset/getOptions') ?>',
            type: 'GET',
            success: function(response) {
                // Populate assets
                $('#asset_id').empty().append('<option value="">Pilih Asset</option>');
                response.assets.forEach(function(item) {
                    $('#asset_id').append(`<option value="${item.asset_id}">${item.kode_asset} - ${item.nama_asset}</option>`);
                });

                // Populate lokasi
                $('#lokasi_id').empty().append('<option value="">Pilih Lokasi</option>');
                response.lokasi.forEach(function(item) {
                    $('#lokasi_id').append(`<option value="${item.id}">${item.nama_lokasi}</option>`);
                });

                // Load penerimaan details
                loadPenerimaanDetails(response.penerimaan_details);
            }
        });
    }

    function loadPenerimaanDetails(details) {
        let html = '';
        
        details.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.invoice_number || '-'}</td>
                    <td>${item.nama_kategori || '-'}</td>
                    <td>${item.nama_item || '-'}</td>
                    <td>${item.qty_gr || 0}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control" id="qty_${item.id}" 
                                   min="1" max="${item.qty_gr}" value="1">
                            <button class="btn btn-primary create-asset-btn" 
                                    data-id="${item.id}" data-max="${item.qty_gr}">
                                <i class="bi bi-plus"></i> Buat
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        if (details.length === 0) {
            html = '<tr><td colspan="5" class="text-center">Tidak ada barang yang perlu dijadikan asset</td></tr>';
        }
        
        $('#penerimaanBody').html(html);

        // Create asset button
        $('.create-asset-btn').click(function() {
            const detailId = $(this).data('id');
            const maxQty = $(this).data('max');
            const qty = $(`#qty_${detailId}`).val();
            
            if (!qty || qty <= 0 || qty > maxQty) {
                toastr.error('Jumlah tidak valid');
                return;
            }

            createAssetFromPenerimaan(detailId, qty);
        });
    }

    function createAssetFromPenerimaan(detailId, qty) {
        $.ajax({
            url: '<?= base_url('distribusi-asset/createAssetFromPenerimaan') ?>',
            type: 'POST',
            data: {
                penerimaan_detail_id: detailId,
                qty: qty
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    loadOptions(); // Refresh options
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }

    $('#btnCreateAsset').click(function() {
        loadOptions(); // Refresh data
        $('#modalCreateAsset').modal('show');
    });

    $('#btnAdd').click(function() {
        loadOptions(); // Refresh options
        $('#modalTitle').text('Distribusikan Asset');
        $('#formDistribusi')[0].reset();
        $('#distribusi_id').val('');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#modalForm').modal('show');
    });

    $('#formDistribusi').submit(function(e) {
        e.preventDefault();
        
        const id = $('#distribusi_id').val();
        const url = id ? `<?= base_url('distribusi-asset/update') ?>/${id}` : '<?= base_url('distribusi-asset/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                    loadOptions();
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
            url: `<?= base_url('distribusi-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalTitle').text('Edit Distribusi');
                    $('#distribusi_id').val(response.data.id);
                    $('#lokasi_id').val(response.data.lokasi_id);
                    $('#tgldistribusi').val(response.data.tgldistribusi);
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
            text: "Asset akan dikembalikan ke status available",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('distribusi-asset/delete') ?>/${id}`,
                    type: 'POST',
                    success: function(response) {
                        if (response.status === 'success') {
                            table.ajax.reload();
                            toastr.success(response.message);
                            loadOptions();
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    };

    window.showDetail = function(id) {
        $.ajax({
            url: `<?= base_url('distribusi-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    $('#detail_kode').text(data.kode_asset || '-');
                    $('#detail_nama').text(data.nama_asset || '-');
                    $('#detail_kategori').text(data.nama_kategori || '-');
                    $('#detail_merk').text(data.merk || '-');
                    $('#detail_tipe').text(data.tipe || '-');
                    $('#detail_serial').text(data.serial_number || '-');
                    $('#detail_lokasi').text(data.nama_lokasi || '-');
                    $('#detail_tanggal').text(data.tgldistribusi ? new Date(data.tgldistribusi).toLocaleDateString('id-ID') : '-');
                    $('#detail_keterangan').text(data.keterangan || '-');
                    
                    $('#modalDetail').modal('show');
                }
            }
        });
    };
});
</script>

<?= $this->endSection() ?>
