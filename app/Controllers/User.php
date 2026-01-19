<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display user list
     */
    public function index()
    {
        $data = [
            'title'       => 'User Management',
            'page_title'  => 'User Management',
            'breadcrumbs' => ['Configuration', 'Users']
        ];

        return view('configuration/users/index', $data);
    }

    /**
     * Display create user form
     */
    public function create()
    {
        $data = [
            'title'       => 'Tambah User',
            'page_title'  => 'Tambah User Baru',
            'breadcrumbs' => ['Master', 'User', 'Tambah']
        ];

        return view('user/create', $data);
    }

    /**
     * Store new user (AJAX)
     */
    public function store()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[100]|is_unique[usr_user.username]',
            'email'            => 'required|valid_email|is_unique[usr_user.email]',
            'nama'             => 'required|min_length[3]|max_length[200]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $data = [
            'username'         => $this->request->getPost('username'),
            'email'            => $this->request->getPost('email'),
            'nama'             => $this->request->getPost('nama'),
            'password'         => $this->request->getPost('password'),
            'nohp'             => $this->request->getPost('nohp'),
            'nomor_registrasi' => $this->request->getPost('nomor_registrasi'),
            'departemen_id'    => $this->request->getPost('departemen_id'),
            'active'           => $this->request->getPost('active') ? true : false,
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => $this->session->get('username'),
        ];

        if ($this->userModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menambahkan user'
        ]);
    }

    /**
     * Get user by ID (AJAX)
     */
    public function show($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $user
        ]);
    }

    /**
     * Update user (AJAX)
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[usr_user.username,id,$id]",
            'email'    => "required|valid_email|is_unique[usr_user.email,id,$id]",
            'nama'     => 'required|min_length[3]|max_length[200]',
        ];

        // Only validate password if provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $data = [
            'username'         => $this->request->getPost('username'),
            'email'            => $this->request->getPost('email'),
            'nama'             => $this->request->getPost('nama'),
            'nohp'             => $this->request->getPost('nohp'),
            'nomor_registrasi' => $this->request->getPost('nomor_registrasi'),
            'departemen_id'    => $this->request->getPost('departemen_id'),
            'active'           => $this->request->getPost('active') ? true : false,
            'updated_at'       => date('Y-m-d H:i:s'),
            'updated_by'       => $this->session->get('username'),
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil diupdate'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate user'
        ]);
    }

    /**
     * Delete user (AJAX - soft delete)
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Prevent deleting own account
        if ($id == $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri'
            ]);
        }

        if ($this->userModel->softDelete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus user'
        ]);
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        $newStatus = !$user['active'];
        
        $data = [
            'active'     => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->get('username'),
        ];

        if ($this->userModel->update($id, $data)) {
            $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            return $this->response->setJSON(['success' => true, 'message' => "User berhasil $status"]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status user']);
    }

    /**
     * Get users data as JSON (for AJAX/DataTables)
     */
    public function getData()
    {
        $users = $this->userModel->getAllUsers();
        
        // Format data for DataTables
        $data = [];
        $dept = ['', 'IT', 'Finance', 'HR', 'Operations'];
        
        foreach ($users as $index => $user) {
            $data[] = [
                'no'         => $index + 1,
                'username'   => $user['username'],
                'nama'       => $user['nama'],
                'email'      => $user['email'],
                'nohp'       => $user['nohp'] ?? '-',
                'departemen' => $user['departemen_id'] ? ($dept[$user['departemen_id']] ?? '-') : '-',
                'active'     => $user['active'],
                'id'         => $user['id']
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }
}
