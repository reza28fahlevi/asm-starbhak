<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiAssetModel extends Model
{
    protected $table            = 'ast_distribusi_asset';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'asset_id',
        'lokasi_id',
        'tgldistribusi',
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
        'asset_id' => 'required|integer',
        'lokasi_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'asset_id' => [
            'required' => 'Asset harus dipilih',
            'integer' => 'Asset ID harus berupa angka'
        ],
        'lokasi_id' => [
            'required' => 'Lokasi harus dipilih',
            'integer' => 'Lokasi ID harus berupa angka'
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
     * Get distribusi with asset and lokasi info
     */
    public function getDistribusiWithDetails()
    {
        return $this->select('ast_distribusi_asset.*, 
                             ast_asset.kode_asset,
                             ast_asset.nama_asset,
                             dt_lokasi.nama_lokasi,
                             dt_lokasi.parent_id')
                    ->join('ast_asset', 'ast_asset.asset_id = ast_distribusi_asset.asset_id', 'left')
                    ->join('dt_lokasi', 'dt_lokasi.id = ast_distribusi_asset.lokasi_id', 'left')
                    ->orderBy('ast_distribusi_asset.id', 'DESC')
                    ->findAll();
    }
}
