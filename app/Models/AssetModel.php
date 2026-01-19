<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'ast_asset';
    protected $primaryKey       = 'asset_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_asset',
        'nama_asset',
        'merk',
        'tipe',
        'spesifikasi',
        'serial_number',
        'kategori_id',
        'mobile',
        'lampiran',
        'status',
        'kondisi',
        'penerimaan_detail_id',
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
        'nama_asset' => 'required|max_length[255]',
    ];

    protected $validationMessages = [
        'nama_asset' => [
            'required' => 'Nama Asset harus diisi',
            'max_length' => 'Nama Asset maksimal 255 karakter'
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
     * Generate kode asset otomatis
     */
    public function generateKodeAsset()
    {
        $prefix = 'AST-' . date('Y') . '-';
        
        // Get last number for this year
        $lastData = $this->like('kode_asset', $prefix, 'after')
                         ->orderBy('asset_id', 'DESC')
                         ->first();
        
        if ($lastData) {
            $lastNumber = (int) substr($lastData['kode_asset'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get assets with kategori name
     */
    public function getAssetsWithKategori()
    {
        return $this->select('ast_asset.*, dt_kategori_asset.nama_kategori')
                    ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_asset.kategori_id', 'left')
                    ->orderBy('ast_asset.asset_id', 'DESC')
                    ->findAll();
    }

    /**
     * Get available assets for distribution (status available, not yet distributed)
     */
    public function getAvailableAssets()
    {
        return $this->select('ast_asset.*, dt_kategori_asset.nama_kategori')
                    ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_asset.kategori_id', 'left')
                    ->where('ast_asset.status', 'available')
                    ->whereNotIn('ast_asset.asset_id', function($builder) {
                        return $builder->select('asset_id')
                                      ->from('ast_distribusi_asset')
                                      ->where('is_deleted', false);
                    })
                    ->findAll();
    }
}
