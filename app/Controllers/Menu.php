<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuModel;

class Menu extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Menu',
            'page_title' => 'Menu Management',
            'breadcrumbs' => ['Configuration', 'Menu Management'],
            'menu' => 'configuration',
            'submenu' => 'menu'
        ];

        return view('configuration/menu/index', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();
        
        $search = $request->getGet('search')['value'] ?? '';
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $draw = $request->getGet('draw') ?? 1;

        $builder = $this->menuModel->builder();
        
        if ($search) {
            $builder->groupStart()
                    ->like('nama', $search)
                    ->orLike('url', $search)
                    ->groupEnd();
        }

        $totalRecords = $this->menuModel->countAll();
        $filteredRecords = $builder->countAllResults(false);
        
        $data = $builder->orderBy('level', 'ASC')
                       ->orderBy('pos', 'ASC')
                       ->limit($length, $start)
                       ->get()
                       ->getResultArray();

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'parent_id' => $this->request->getPost('parent_id'),
            'nama' => $this->request->getPost('nama'),
            'url' => $this->request->getPost('url'),
            'icon' => $this->request->getPost('icon'),
            'level' => $this->request->getPost('level'),
            'pos' => $this->request->getPost('pos'),
            'created_by' => session()->get('username')
        ];

        if ($this->menuModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menambahkan data'
        ]);
    }

    public function show($id)
    {
        $data = $this->menuModel->find($id);
        
        if ($data) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'parent_id' => $this->request->getPost('parent_id'),
            'nama' => $this->request->getPost('nama'),
            'url' => $this->request->getPost('url'),
            'icon' => $this->request->getPost('icon'),
            'level' => $this->request->getPost('level'),
            'pos' => $this->request->getPost('pos'),
            'updated_by' => session()->get('username')
        ];

        if ($this->menuModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengupdate data'
        ]);
    }

    public function delete($id)
    {
        if ($this->menuModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus data'
        ]);
    }

    public function getOptions()
    {
        $menus = $this->menuModel->where('level', 1)->findAll();
        return $this->response->setJSON($menus);
    }
}
