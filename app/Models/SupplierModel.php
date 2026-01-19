<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'dt_supplier';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_supplier', 'alamat', 'nomortelepon', 'created_by', 'updated_by', 'deleted_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_supplier' => 'required|min_length[3]|max_length[255]',
    ];
    protected $validationMessages   = [
        'nama_supplier' => [
            'required' => 'Nama supplier harus diisi',
            'min_length' => 'Nama supplier minimal 3 karakter',
            'max_length' => 'Nama supplier maksimal 255 karakter',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
