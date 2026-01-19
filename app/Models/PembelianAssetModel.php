<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianAssetModel extends Model
{
    protected $table            = 'ast_pembelian_asset';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_pembelian',
        'permohonan_id',
        'nomor_permohonan',
        'total_anggaran',
        'supplier_id',
        'tanggal_pembelian',
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
        'supplier_id' => 'permit_empty|integer',
        'tanggal_pembelian' => 'permit_empty|valid_date',
    ];

    protected $validationMessages = [
        'supplier_id' => [
            'integer' => 'Supplier harus berupa angka'
        ],
        'tanggal_pembelian' => [
            'valid_date' => 'Tanggal tidak valid'
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
     * Generate nomor pembelian otomatis
     */
    public function generateNomorPembelian()
    {
        $prefix = 'PO-' . date('Ym') . '-';
        
        // Get last number for this month
        $lastData = $this->like('nomor_pembelian', $prefix, 'after')
                         ->orderBy('id', 'DESC')
                         ->first();
        
        if ($lastData) {
            $lastNumber = (int) substr($lastData['nomor_pembelian'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get pembelian with supplier name
     */
    public function getPembelianWithSupplier()
    {
        return $this->select('ast_pembelian_asset.*, dt_supplier.nama_supplier')
                    ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left')
                    ->orderBy('ast_pembelian_asset.id', 'DESC')
                    ->findAll();
    }
}
