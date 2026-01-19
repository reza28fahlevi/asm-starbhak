<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriAssetModel;

class KategoriAsset extends BaseController
{
    protected $kategoriAssetModel;

    public function __construct()
    {
        $this->kategoriAssetModel = new KategoriAssetModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kategori Asset',
            'page_title' => 'Kategori Asset',
            'breadcrumbs' => ['Configuration', 'Kategori Asset'],
            'menu' => 'configuration',
            'submenu' => 'kategori_asset'
        ];

        return view('configuration/kategori_asset/index', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();
        
        $search = $request->getGet('search')['value'] ?? '';
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $draw = $request->getGet('draw') ?? 1;

        $builder = $this->kategoriAssetModel->builder();
        
        if ($search) {
            $builder->like('nama_kategori', $search);
        }

        $totalRecords = $this->kategoriAssetModel->countAll();
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
            'nama_kategori' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'created_by' => session()->get('username')
        ];

        if ($this->kategoriAssetModel->insert($data)) {
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
        $data = $this->kategoriAssetModel->find($id);
        
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
            'nama_kategori' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'updated_by' => session()->get('username')
        ];

        if ($this->kategoriAssetModel->update($id, $data)) {
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
        if ($this->kategoriAssetModel->delete($id)) {
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
