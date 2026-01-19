<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Approval Permohonan Asset</h5>
                <div>
                    <select class="form-select form-select-sm" id="filterStatus">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="all">Semua</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tableApproval">
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

                <div id="approvalInfo" class="alert alert-info" style="display: none;">
                    <div class="row">
                        <div class="col-md-6" id="approvedInfo" style="display: none;">
                            <strong>Disetujui oleh:</strong> <span id="approved_by"></span><br>
                            <strong>Tanggal:</strong> <span id="approved_at"></span>
                        </div>
                        <div class="col-md-6" id="rejectedInfo" style="display: none;">
                            <strong>Ditolak oleh:</strong> <span id="rejected_by"></span><br>
                            <strong>Tanggal:</strong> <span id="rejected_at"></span>
                        </div>
                    </div>
                </div>

                <hr>

                <h6>Detail Item Asset</h6>
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
                            </tr>
                        </thead>
                        <tbody id="detailBody">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Total:</th>
                                <th id="grandTotal">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="approvalButtons" class="mt-3" style="display: none;">
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" rows="2"></textarea>
                        <small class="text-muted">*Wajib diisi untuk penolakan</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="btnApprove">
                            <i class="bi bi-check-circle"></i> Setujui
                        </button>
                        <button type="button" class="btn btn-danger" id="btnReject">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let table;
    let currentPermohonanId = null;
    let currentStatus = null;

    // Initialize DataTable
    table = $('#tableApproval').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('approval-permohonan/getData') ?>',
            type: 'GET',
            data: function(d) {
                d.status = $('#filterStatus').val();
            }
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
                        <button class="btn btn-info btn-sm" onclick="showDetail(${row.id}, ${row.status})">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Filter change
    $('#filterStatus').change(function() {
        table.ajax.reload();
    });

    // Show detail
    window.showDetail = function(id, status) {
        currentPermohonanId = id;
        currentStatus = status;
        
        $.ajax({
            url: `<?= base_url('approval-permohonan/show') ?>/${id}`,
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
                    
                    // Show approval info
                    $('#approvalInfo').hide();
                    $('#approvedInfo').hide();
                    $('#rejectedInfo').hide();
                    
                    if (data.status === 1) {
                        $('#approved_by').text(data.approved_by || '-');
                        $('#approved_at').text(data.approved_at ? new Date(data.approved_at).toLocaleString('id-ID') : '-');
                        $('#approvedInfo').show();
                        $('#approvalInfo').show();
                    } else if (data.status === 2) {
                        $('#rejected_by').text(data.rejected_by || '-');
                        $('#rejected_at').text(data.rejected_at ? new Date(data.rejected_at).toLocaleString('id-ID') : '-');
                        $('#rejectedInfo').show();
                        $('#approvalInfo').show();
                    }
                    
                    // Show/hide approval buttons
                    if (data.status === 0) {
                        $('#approvalButtons').show();
                        $('#catatan').val('');
                    } else {
                        $('#approvalButtons').hide();
                    }
                    
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
                </tr>
            `;
        });
        
        if (details.length === 0) {
            html = '<tr><td colspan="7" class="text-center">Belum ada detail item</td></tr>';
        }
        
        $('#detailBody').html(html);
        $('#grandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
    }

    // Approve button
    $('#btnApprove').click(function() {
        Swal.fire({
            title: 'Setujui Permohonan?',
            text: "Permohonan ini akan disetujui",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('approval-permohonan/approve') ?>/${currentPermohonanId}`,
                    type: 'POST',
                    data: {
                        catatan: $('#catatan').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#modalDetail').modal('hide');
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

    // Reject button
    $('#btnReject').click(function() {
        const catatan = $('#catatan').val();
        
        if (!catatan) {
            toastr.error('Catatan penolakan harus diisi');
            return;
        }

        Swal.fire({
            title: 'Tolak Permohonan?',
            text: "Permohonan ini akan ditolak",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('approval-permohonan/reject') ?>/${currentPermohonanId}`,
                    type: 'POST',
                    data: {
                        catatan: catatan
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#modalDetail').modal('hide');
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
