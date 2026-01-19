<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermohonanAssetModel;
use App\Models\PermohonanAssetDetailModel;
use App\Models\DepartemenModel;
use App\Models\KelompokAssetModel;
use CodeIgniter\HTTP\ResponseInterface;

class PermohonanAsset extends BaseController
{
    protected $permohonanModel;
    protected $detailModel;
    protected $departemenModel;
    protected $kelompokModel;
    protected $session;

    public function __construct()
    {
        $this->permohonanModel = new PermohonanAssetModel();
        $this->detailModel = new PermohonanAssetDetailModel();
        $this->departemenModel = new DepartemenModel();
        $this->kelompokModel = new KelompokAssetModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Permohonan Asset',
            'page_title' => 'Permohonan Asset',
            'breadcrumbs' => ['Asset Management', 'Permohonan Asset']
        ];

        return view('asset/asset_application/index', $data);
    }

    public function getData()
    {
        $request = $this->request->getGet();
        
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        
        $builder = $this->permohonanModel->builder();
        
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('nomor_permohonan', $searchValue)
                    ->orLike('departemen', $searchValue)
                    ->orLike('pemohon', $searchValue)
                    ->groupEnd();
        }
        
        $totalRecords = $this->permohonanModel->countAllResults(false);
        $totalFiltered = $builder->countAllResults(false);
        
        $data = $builder->orderBy('id', 'DESC')
                       ->limit($length, $start)
                       ->get()
                       ->getResultArray();
        
        // Format data
        foreach ($data as &$row) {
            $statusBadge = '';
            switch ($row['status']) {
                case 0:
                    $statusBadge = '<span class="badge bg-warning">Pending</span>';
                    break;
                case 1:
                    $statusBadge = '<span class="badge bg-success">Approved</span>';
                    break;
                case 2:
                    $statusBadge = '<span class="badge bg-danger">Rejected</span>';
                    break;
                default:
                    $statusBadge = '<span class="badge bg-secondary">Draft</span>';
            }
            $row['status_badge'] = $statusBadge;
            
            // Format currency
            $row['total_anggaran_formatted'] = 'Rp ' . number_format($row['total_anggaran'] ?? 0, 0, ',', '.');
        }
        
        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'departemen' => 'required',
            'keterangan_tujuan' => 'required',
            'pemohon' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            // Generate nomor permohonan
            $nomorPermohonan = $this->permohonanModel->generateNomorPermohonan();
            
            $data = [
                'nomor_permohonan' => $nomorPermohonan,
                'departemen' => $this->request->getPost('departemen'),
                'keterangan_tujuan' => $this->request->getPost('keterangan_tujuan'),
                'total_anggaran' => 0, // Will be calculated from details
                'pemohon' => $this->request->getPost('pemohon'),
                'status' => 0, // Pending
                'created_by' => $this->session->get('username')
            ];

            if ($this->permohonanModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => [
                        'id' => $this->permohonanModel->getInsertID(),
                        'nomor_permohonan' => $nomorPermohonan
                    ]
                ]);
            } else {
                throw new \Exception('Failed to save data');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $data = $this->permohonanModel->find($id);
        
        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Get details
        $details = $this->detailModel->getDetailWithKelompok($id);
        $data['details'] = $details;

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'departemen' => 'required',
            'keterangan_tujuan' => 'required',
            'pemohon' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $data = [
                'departemen' => $this->request->getPost('departemen'),
                'keterangan_tujuan' => $this->request->getPost('keterangan_tujuan'),
                'pemohon' => $this->request->getPost('pemohon'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->permohonanModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                throw new \Exception('Failed to update data');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            // Check if has details
            $details = $this->detailModel->where('permohonan_id', $id)->findAll();
            if (count($details) > 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus permohonan yang sudah memiliki detail'
                ]);
            }

            $this->permohonanModel->update($id, [
                'deleted_by' => $this->session->get('username')
            ]);

            if ($this->permohonanModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                throw new \Exception('Failed to delete data');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getOptions()
    {
        $departemen = $this->departemenModel->select('id, nama_departemen')->findAll();
        $kelompok = $this->kelompokModel->select('kelompok_id as id, nama_kelompok')->findAll();

        return $this->response->setJSON([
            'departemen' => $departemen,
            'kelompok' => $kelompok
        ]);
    }

    public function storeDetail()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'permohonan_id' => 'required|integer',
            'kelompok_id' => 'required|integer',
            'nama_asset' => 'required',
            'qty' => 'required|decimal',
            'harga' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $permohonanId = $this->request->getPost('permohonan_id');
            $permohonan = $this->permohonanModel->find($permohonanId);

            if (!$permohonan) {
                throw new \Exception('Permohonan tidak ditemukan');
            }

            $data = [
                'permohonan_id' => $permohonanId,
                'nomor_permohonan' => $permohonan['nomor_permohonan'],
                'kelompok_id' => $this->request->getPost('kelompok_id'),
                'nama_asset' => $this->request->getPost('nama_asset'),
                'keterangan' => $this->request->getPost('keterangan'),
                'harga' => $this->request->getPost('harga'),
                'qty' => $this->request->getPost('qty'),
            ];

            if ($this->detailModel->insert($data)) {
                // Update total anggaran
                $this->updateTotalAnggaran($permohonanId);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Detail berhasil ditambahkan'
                ]);
            } else {
                throw new \Exception('Failed to save detail');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteDetail($id)
    {
        try {
            $detail = $this->detailModel->find($id);
            if (!$detail) {
                throw new \Exception('Detail tidak ditemukan');
            }

            if ($this->detailModel->delete($id)) {
                // Update total anggaran
                $this->updateTotalAnggaran($detail['permohonan_id']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Detail berhasil dihapus'
                ]);
            } else {
                throw new \Exception('Failed to delete detail');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function updateTotalAnggaran($permohonanId)
    {
        $details = $this->detailModel->where('permohonan_id', $permohonanId)->findAll();
        $total = 0;
        
        foreach ($details as $detail) {
            $total += ($detail['harga'] ?? 0) * ($detail['qty'] ?? 0);
        }

        $this->permohonanModel->update($permohonanId, [
            'total_anggaran' => $total,
            'updated_by' => $this->session->get('username')
        ]);
    }
}
