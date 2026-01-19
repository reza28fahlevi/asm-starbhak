<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'usr_role';
    protected $primaryKey       = 'role_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'keterangan',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama role harus diisi',
            'min_length' => 'Nama role minimal 3 karakter',
            'max_length' => 'Nama role maksimal 100 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        return $this->findAll();
    }

    /**
     * Get role by ID
     */
    public function getRoleById($id)
    {
        return $this->find($id);
    }
}
