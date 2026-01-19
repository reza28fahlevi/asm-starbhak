<?php

namespace App\Models;

use CodeIgniter\Model;

class KelompokAssetModel extends Model
{
    protected $table            = 'dt_kelompok_asset';
    protected $primaryKey       = 'kelompok_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_kelompok', 'created_by', 'updated_by', 'deleted_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_kelompok' => 'required|min_length[3]|max_length[255]',
    ];
    protected $validationMessages   = [
        'nama_kelompok' => [
            'required' => 'Nama kelompok harus diisi',
            'min_length' => 'Nama kelompok minimal 3 karakter',
            'max_length' => 'Nama kelompok maksimal 255 karakter',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
