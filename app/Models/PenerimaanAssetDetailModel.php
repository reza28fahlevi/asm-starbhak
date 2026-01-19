<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaanAssetDetailModel extends Model
{
    protected $table            = 'ast_penerimaan_asset_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_penerimaan',
        'invoice_number',
        'pembelian_detail_id',
        'kategori_id',
        'kelompok_id',
        'nama_item',
        'qty',
        'qty_gr',
        'keterangan'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_penerimaan' => 'required|integer',
        'nama_item' => 'required|max_length[255]',
        'qty' => 'required|decimal',
        'qty_gr' => 'required|decimal',
    ];

    protected $validationMessages = [
        'id_penerimaan' => [
            'required' => 'ID Penerimaan harus diisi',
            'integer' => 'ID Penerimaan harus berupa angka'
        ],
        'nama_item' => [
            'required' => 'Nama Item harus diisi',
            'max_length' => 'Nama Item maksimal 255 karakter'
        ],
        'qty' => [
            'required' => 'Qty Order harus diisi',
            'decimal' => 'Qty Order harus berupa angka'
        ],
        'qty_gr' => [
            'required' => 'Qty Terima harus diisi',
            'decimal' => 'Qty Terima harus berupa angka'
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
     * Get detail with related data
     */
    public function getDetailWithRelation($penerimaanId)
    {
        return $this->select('ast_penerimaan_asset_detail.*, 
                             dt_kelompok_asset.nama_kelompok,
                             dt_kategori_asset.nama_kategori')
                    ->join('dt_kelompok_asset', 'dt_kelompok_asset.kelompok_id = ast_penerimaan_asset_detail.kelompok_id', 'left')
                    ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_penerimaan_asset_detail.kategori_id', 'left')
                    ->where('id_penerimaan', $penerimaanId)
                    ->findAll();
    }
}
