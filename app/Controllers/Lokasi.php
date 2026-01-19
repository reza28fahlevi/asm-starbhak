<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LokasiModel;

class Lokasi extends BaseController
{
    protected $lokasiModel;

    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Lokasi',
            'page_title' => 'Lokasi',
            'breadcrumbs' => ['Configuration', 'Lokasi'],
            'menu' => 'configuration',
            'submenu' => 'lokasi'
        ];

        return view('configuration/lokasi/index', $data);
    }

    public function getData()
    {
        $request = \Config\Services::request();
        
        $search = $request->getGet('search')['value'] ?? '';
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $draw = $request->getGet('draw') ?? 1;

        $builder = $this->lokasiModel->builder();
        
        if ($search) {
            $builder->like('nama', $search);
        }

        $totalRecords = $this->lokasiModel->countAll();
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
            'level' => $this->request->getPost('level'),
            'nama' => $this->request->getPost('nama'),
            'induk' => $this->request->getPost('induk'),
            'created_by' => session()->get('username')
        ];

        if ($this->lokasiModel->insert($data)) {
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
        $data = $this->lokasiModel->find($id);
        
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
            'level' => $this->request->getPost('level'),
            'nama' => $this->request->getPost('nama'),
            'induk' => $this->request->getPost('induk'),
            'updated_by' => session()->get('username')
        ];

        if ($this->lokasiModel->update($id, $data)) {
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
        if ($this->lokasiModel->delete($id)) {
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
        $lokasi = $this->lokasiModel->findAll();
        return $this->response->setJSON($lokasi);
    }
}
