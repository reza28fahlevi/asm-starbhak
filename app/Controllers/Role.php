<?php

namespace App\Controllers;

use App\Models\RoleModel;

class Role extends BaseController
{
    protected $roleModel;
    protected $session;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display role list
     */
    public function index()
    {
        $data = [
            'title'       => 'Role Management',
            'page_title'  => 'Role Management',
            'breadcrumbs' => ['Configuration', 'Role']
        ];

        return view('configuration/role/index', $data);
    }

    /**
     * Get roles data as JSON (for DataTables AJAX)
     */
    public function getData()
    {
        $roles = $this->roleModel->getAllRoles();
        
        $data = [];
        foreach ($roles as $index => $role) {
            $data[] = [
                'no'         => $index + 1,
                'role_id'    => $role['role_id'],
                'nama'       => $role['nama'],
                'keterangan' => $role['keterangan'] ?? '-',
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Store new role (AJAX)
     */
    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->get('username'),
        ];


        if ($this->roleModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menambahkan role'
        ]);
    }

    /**
     * Get role by ID (AJAX)
     */
    public function show($id)
    {
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $role
        ]);
    }

    /**
     * Update role (AJAX)
     */
    public function update($id)
    {
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'keterangan' => $this->request->getPost('keterangan'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->get('username'),
        ];

        if ($this->roleModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role berhasil diupdate'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate role'
        ]);
    }

    /**
     * Delete role (AJAX)
     */
    public function delete($id)
    {
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        if ($this->roleModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus role'
        ]);
    }
}
