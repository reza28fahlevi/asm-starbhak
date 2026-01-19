<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KelompokAssetModel;

class KelompokAsset extends BaseController
{
    protected $kelompokAssetModel;

    public function __construct()
    {
        $this->kelompokAssetModel = new KelompokAssetModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelompok Asset',
            'page_title' => 'Kelompok Asset',
            'breadcrumbs' => ['Configuration', 'Kelompok Asset'],
            'menu' => 'configuration',
            'submenu' => 'kelompok_asset'
        ];

        return view('configuration/kelompok_asset/index', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();
        
        $search = $request->getGet('search')['value'] ?? '';
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $draw = $request->getGet('draw') ?? 1;

        $builder = $this->kelompokAssetModel->builder();
        
        if ($search) {
            $builder->like('nama_kelompok', $search);
        }

        $totalRecords = $this->kelompokAssetModel->countAll();
        $filteredRecords = $builder->countAllResults(false);
        
        $data = $builder->orderBy('kelompok_id', 'DESC')
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
            'nama_kelompok' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_kelompok' => $this->request->getPost('nama_kelompok'),
            'created_by' => session()->get('username')
        ];

        if ($this->kelompokAssetModel->insert($data)) {
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
        $data = $this->kelompokAssetModel->find($id);
        
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
            'nama_kelompok' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama_kelompok' => $this->request->getPost('nama_kelompok'),
            'updated_by' => session()->get('username')
        ];

        if ($this->kelompokAssetModel->update($id, $data)) {
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
        if ($this->kelompokAssetModel->delete($id)) {
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
