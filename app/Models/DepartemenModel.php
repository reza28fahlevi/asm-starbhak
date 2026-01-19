<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartemenModel extends Model
{
    protected $table            = 'dt_departemen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_departemen', 'deskripsi', 'created_by', 'updated_by', 'deleted_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_departemen' => 'required|min_length[3]|max_length[255]',
    ];
    protected $validationMessages   = [
        'nama_departemen' => [
            'required' => 'Nama departemen harus diisi',
            'min_length' => 'Nama departemen minimal 3 karakter',
            'max_length' => 'Nama departemen maksimal 255 karakter',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
