<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'usr_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'email',
        'nama',
        'nohp',
        'nomor_registrasi',
        'departemen_id',
        'active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[usr_user.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[usr_user.email,id,{id}]',
        'nama'     => 'required|min_length[3]|max_length[200]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique'  => 'Username sudah digunakan',
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique'   => 'Email sudah digunakan',
        ],
        'nama' => [
            'required'   => 'Nama lengkap harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
        ],
        'password' => [
            'required'   => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Get active users (not deleted)
     */
    public function getActiveUsers()
    {
        return $this->where('is_deleted', false)
                    ->where('active', true)
                    ->findAll();
    }

    /**
     * Get user by username for authentication
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)
                    ->where('is_deleted', false)
                    ->where('active', true)
                    ->first();
    }

    /**
     * Soft delete user
     */
    public function softDelete($id, $deletedBy = null)
    {
        return $this->update($id, [
            'is_deleted' => true,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy ?? session()->get('username')
        ]);
    }

    /**
     * Get all users excluding soft deleted
     */
    public function getAllUsers()
    {
        return $this->where('is_deleted', false)->findAll();
    }
}
