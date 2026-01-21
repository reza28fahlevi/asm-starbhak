<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Daftar Penerimaan Barang</h5>
                <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                    <i class="bi bi-plus-circle"></i> Terima Barang
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablePenerimaan">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Invoice Number</th>
                            <th>Nomor PO</th>
                            <th>Supplier</th>
                            <th>Tanggal Terima</th>
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
                <h5 class="modal-title" id="modalTitle">Terima Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPenerimaan">
                <div class="modal-body">
                    <input type="hidden" id="penerimaan_id" name="penerimaan_id">
                    
                    <div class="mb-3">
                        <label for="id_pembelian" class="form-label">Purchase Order <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_pembelian" name="id_pembelian" required>
                            <option value="">Pilih PO</option>
                        </select>
                        <div class="invalid-feedback" id="error-id_pembelian"></div>
                    </div>

                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
                        <div class="invalid-feedback" id="error-invoice_number"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_penerimaan" class="form-label">Tanggal Penerimaan</label>
                        <input type="date" class="form-control" id="tanggal_penerimaan" name="tanggal_penerimaan">
                        <div class="invalid-feedback" id="error-tanggal_penerimaan"></div>
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
                <h5 class="modal-title">Detail Penerimaan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Invoice Number</th>
                                <td id="detail_invoice"></td>
                            </tr>
                            <tr>
                                <th>Nomor PO</th>
                                <td id="detail_po"></td>
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
                                <th>Keterangan</th>
                                <td id="detail_keterangan"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h6>Detail Item</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tableDetail">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th>Kelompok</th>
                                <th>Nama Item</th>
                                <th width="10%">Qty Order</th>
                                <th width="10%">Qty Terima</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailBody">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
$(document).ready(function() {
    let table;
    let currentPenerimaanId = null;
    
    // Initialize Bootstrap modals
    const modalForm = new bootstrap.Modal(document.getElementById('modalForm'));
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

    // Load options
    loadOptions();

    // Initialize DataTable
    table = $('#tablePenerimaan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('penerimaan-asset/getData') ?>',
            type: 'GET'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'invoice_number' },
            { data: 'nomor_pembelian' },
            { data: 'nama_supplier', 
              render: function(data) {
                  return data || '-';
              }
            },
            { data: 'tanggal_penerimaan_formatted' },
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
            url: '<?= base_url('penerimaan-asset/getOptions') ?>',
            type: 'GET',
            success: function(response) {
                $('#id_pembelian').empty().append('<option value="">Pilih PO</option>');
                response.pembelian.forEach(function(item) {
                    $('#id_pembelian').append(`<option value="${item.id}">${item.nomor_pembelian} - ${item.nama_supplier || 'No Supplier'}</option>`);
                });
            }
        });
    }

    $('#btnAdd').click(function() {
        loadOptions(); // Refresh options
        $('#modalTitle').text('Terima Barang');
        $('#formPenerimaan')[0].reset();
        $('#penerimaan_id').val('');
        $('.form-control, .form-select').removeClass('is-invalid');
        modalForm.show();
    });

    $('#formPenerimaan').submit(function(e) {
        e.preventDefault();
        
        const id = $('#penerimaan_id').val();
        const url = id ? `<?= base_url('penerimaan-asset/update') ?>/${id}` : '<?= base_url('penerimaan-asset/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    modalForm.hide();
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
            url: `<?= base_url('penerimaan-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalTitle').text('Edit Penerimaan');
                    $('#penerimaan_id').val(response.data.id);
                    $('#invoice_number').val(response.data.invoice_number);
                    $('#tanggal_penerimaan').val(response.data.tanggal_penerimaan);
                    $('#keterangan').val(response.data.keterangan);
                    $('.form-control, .form-select').removeClass('is-invalid');
                    modalForm.show();
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
                    url: `<?= base_url('penerimaan-asset/delete') ?>/${id}`,
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
        currentPenerimaanId = id;
        
        $.ajax({
            url: `<?= base_url('penerimaan-asset/show') ?>/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    $('#detail_invoice').text(data.invoice_number || '-');
                    $('#detail_po').text(data.nomor_pembelian || '-');
                    $('#detail_supplier').text(data.nama_supplier || '-');
                    $('#detail_tanggal').text(data.tanggal_penerimaan ? new Date(data.tanggal_penerimaan).toLocaleDateString('id-ID') : '-');
                    $('#detail_keterangan').text(data.keterangan || '-');
                    
                    loadDetailTable(data.details || []);
                    
                    modalDetail.show();
                }
            }
        });
    };

    function loadDetailTable(details) {
        let html = '';
        
        details.forEach(function(item, index) {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_kategori || '-'}</td>
                    <td>${item.nama_kelompok || '-'}</td>
                    <td>${item.nama_item || '-'}</td>
                    <td>${item.qty || 0}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm qty-gr-input" 
                               data-id="${item.id}" 
                               value="${item.qty_gr || 0}" 
                               step="1" min="0" max="${item.qty || 0}">
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success update-qty-btn" data-id="${item.id}">
                            <i class="bi bi-check"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        if (details.length === 0) {
            html = '<tr><td colspan="7" class="text-center">Belum ada detail item</td></tr>';
        }
        
        $('#detailBody').html(html);

        // Update qty_gr event
        $('.update-qty-btn').click(function() {
            const detailId = $(this).data('id');
            const qtyGr = $(`.qty-gr-input[data-id="${detailId}"]`).val();
            
            $.ajax({
                url: `<?= base_url('penerimaan-asset/updateQtyGr') ?>/${detailId}`,
                type: 'POST',
                data: { qty_gr: qtyGr },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success('Qty Terima berhasil diupdate');
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
    }
});
</script>

<?= $this->endSection() ?>
