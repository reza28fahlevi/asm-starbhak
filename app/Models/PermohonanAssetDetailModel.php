<?php

namespace App\Models;

use CodeIgniter\Model;

class PermohonanAssetDetailModel extends Model
{
    protected $table            = 'ast_permohonan_asset_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'permohonan_id',
        'nomor_permohonan',
        'kelompok_id',
        'nama_asset',
        'keterangan',
        'harga',
        'qty',
        'lampiran_file'
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
        'permohonan_id' => 'required|integer',
        'kelompok_id' => 'required|integer',
        'nama_asset' => 'required|max_length[255]',
        'qty' => 'required|decimal',
    ];

    protected $validationMessages = [
        'permohonan_id' => [
            'required' => 'ID Permohonan harus diisi',
            'integer' => 'ID Permohonan harus berupa angka'
        ],
        'kelompok_id' => [
            'required' => 'Kelompok Asset harus dipilih',
            'integer' => 'Kelompok Asset harus berupa angka'
        ],
        'nama_asset' => [
            'required' => 'Nama Asset harus diisi',
            'max_length' => 'Nama Asset maksimal 255 karakter'
        ],
        'qty' => [
            'required' => 'Jumlah harus diisi',
            'decimal' => 'Jumlah harus berupa angka'
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
     * Get detail with kelompok asset name
     */
    public function getDetailWithKelompok($permohonanId)
    {
        return $this->select('ast_permohonan_asset_detail.*, dt_kelompok_asset.nama_kelompok')
                    ->join('dt_kelompok_asset', 'dt_kelompok_asset.kelompok_id = ast_permohonan_asset_detail.kelompok_id', 'left')
                    ->where('permohonan_id', $permohonanId)
                    ->findAll();
    }
}
