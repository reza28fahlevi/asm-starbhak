<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class Supplier extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Supplier',
            'page_title' => 'Supplier',
            'breadcrumbs' => ['Configuration', 'Supplier'],
            'menu' => 'configuration',
            'submenu' => 'supplier'
        ];

        return view('configuration/supplier/index', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();
        
        $search = $request->getGet('search')['value'] ?? '';
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $draw = $request->getGet('draw') ?? 1;

        $builder = $this->supplierModel->builder();
        
        if ($search) {
            $builder->groupStart()
                    ->like('nama_supplier', $search)
                    ->orLike('alamat', $search)
                    ->orLike('nomortelepon', $search)
                    ->groupEnd();
        }

        $totalRecords = $this->supplierModel->countAll();
        $filteredRecords = $builder->countAllResults(false);
        
        $data = $builder->orderBy('id', 'DESC')
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
            'nama_supplier' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'nomortelepon' => $this->request->getPost('nomortelepon'),
            'created_by' => session()->get('username')
        ];

        if ($this->supplierModel->insert($data)) {
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
        $data = $this->supplierModel->find($id);
        
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
            'nama_supplier' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'nomortelepon' => $this->request->getPost('nomortelepon'),
            'updated_by' => session()->get('username')
        ];

        if ($this->supplierModel->update($id, $data)) {
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
        if ($this->supplierModel->delete($id)) {
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
}
