<?php

namespace App\Models;

use CodeIgniter\Model;

class PermohonanAssetModel extends Model
{
    protected $table            = 'ast_permohonan_asset';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_permohonan',
        'departemen',
        'keterangan_tujuan',
        'total_anggaran',
        'pemohon',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
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
        'departemen' => 'required|max_length[255]',
        'keterangan_tujuan' => 'required',
        'pemohon' => 'required|max_length[255]',
    ];

    protected $validationMessages = [
        'departemen' => [
            'required' => 'Departemen harus diisi',
            'max_length' => 'Departemen maksimal 255 karakter'
        ],
        'keterangan_tujuan' => [
            'required' => 'Keterangan tujuan harus diisi'
        ],
        'pemohon' => [
            'required' => 'Pemohon harus diisi',
            'max_length' => 'Pemohon maksimal 255 karakter'
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
     * Generate nomor permohonan otomatis
     */
    public function generateNomorPermohonan()
    {
        $prefix = 'PA-' . date('Ym') . '-';
        
        // Get last number for this month
        $lastData = $this->like('nomor_permohonan', $prefix, 'after')
                         ->orderBy('id', 'DESC')
                         ->first();
        
        if ($lastData) {
            $lastNumber = (int) substr($lastData['nomor_permohonan'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
