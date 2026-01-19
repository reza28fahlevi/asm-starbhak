<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianAssetDetailModel extends Model
{
    protected $table            = 'ast_pembelian_asset_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pembelian_id',
        'nomor_pembelian',
        'permohonan_detail_id',
        'kategori_id',
        'kelompok_id',
        'nama_item',
        'qty',
        'harga',
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
        'pembelian_id' => 'required|integer',
        'kelompok_id' => 'required|integer',
        'nama_item' => 'required|max_length[255]',
        'qty' => 'required|decimal',
        'harga' => 'required|decimal',
    ];

    protected $validationMessages = [
        'pembelian_id' => [
            'required' => 'ID Pembelian harus diisi',
            'integer' => 'ID Pembelian harus berupa angka'
        ],
        'kelompok_id' => [
            'required' => 'Kelompok Asset harus dipilih',
            'integer' => 'Kelompok Asset harus berupa angka'
        ],
        'nama_item' => [
            'required' => 'Nama Item harus diisi',
            'max_length' => 'Nama Item maksimal 255 karakter'
        ],
        'qty' => [
            'required' => 'Jumlah harus diisi',
            'decimal' => 'Jumlah harus berupa angka'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'decimal' => 'Harga harus berupa angka'
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
    public function getDetailWithRelation($pembelianId)
    {
        return $this->select('ast_pembelian_asset_detail.*, 
                             dt_kelompok_asset.nama_kelompok,
                             dt_kategori_asset.nama_kategori')
                    ->join('dt_kelompok_asset', 'dt_kelompok_asset.kelompok_id = ast_pembelian_asset_detail.kelompok_id', 'left')
                    ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_pembelian_asset_detail.kategori_id', 'left')
                    ->where('pembelian_id', $pembelianId)
                    ->findAll();
    }
}
