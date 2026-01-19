<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaanAssetModel extends Model
{
    protected $table            = 'ast_penerimaan_asset';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pembelian',
        'invoice_number',
        'nomor_pembelian',
        'tanggal_penerimaan',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_pembelian' => 'required|integer',
        'invoice_number' => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'id_pembelian' => [
            'required' => 'Pembelian harus dipilih',
            'integer' => 'ID Pembelian harus berupa angka'
        ],
        'invoice_number' => [
            'required' => 'Invoice Number harus diisi',
            'max_length' => 'Invoice Number maksimal 100 karakter'
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get penerimaan with pembelian info
     */
    public function getPenerimaanWithPembelian()
    {
        return $this->select('ast_penerimaan_asset.*, ast_pembelian_asset.nomor_pembelian, dt_supplier.nama_supplier')
                    ->join('ast_pembelian_asset', 'ast_pembelian_asset.id = ast_penerimaan_asset.id_pembelian', 'left')
                    ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left')
                    ->orderBy('ast_penerimaan_asset.id', 'DESC')
                    ->findAll();
    }
}
