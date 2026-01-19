<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermohonanAssetModel;
use App\Models\PermohonanAssetDetailModel;
use CodeIgniter\HTTP\ResponseInterface;

class ApprovalPermohonan extends BaseController
{
    protected $permohonanModel;
    protected $detailModel;
    protected $session;

    public function __construct()
    {
        $this->permohonanModel = new PermohonanAssetModel();
        $this->detailModel = new PermohonanAssetDetailModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Approval Permohonan Asset',
            'page_title' => 'Approval Permohonan Asset',
            'breadcrumbs' => ['Asset Management', 'Approval Permohonan']
        ];

        return view('asset/approval_application/index', $data);
    }

    public function getData()
    {
        $request = $this->request->getGet();
        
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        $status = $request['status'] ?? 'pending'; // pending, approved, rejected, all
        
        $builder = $this->permohonanModel->builder();
        
        // Filter by status
        if ($status === 'pending') {
            $builder->where('status', 0);
        } elseif ($status === 'approved') {
            $builder->where('status', 1);
        } elseif ($status === 'rejected') {
            $builder->where('status', 2);
        }
        
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
            
            // Format dates
            $row['approved_at_formatted'] = $row['approved_at'] ? date('d/m/Y H:i', strtotime($row['approved_at'])) : '-';
            $row['rejected_at_formatted'] = $row['rejected_at'] ? date('d/m/Y H:i', strtotime($row['rejected_at'])) : '-';
        }
        
        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
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

    public function approve($id)
    {
        try {
            $permohonan = $this->permohonanModel->find($id);
            
            if (!$permohonan) {
                throw new \Exception('Permohonan tidak ditemukan');
            }

            if ($permohonan['status'] != 0) {
                throw new \Exception('Permohonan sudah diproses sebelumnya');
            }

            // Check if has details
            $details = $this->detailModel->where('permohonan_id', $id)->findAll();
            if (count($details) === 0) {
                throw new \Exception('Permohonan belum memiliki detail item');
            }

            $catatan = $this->request->getPost('catatan');

            $updateData = [
                'status' => 1,
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => $this->session->get('username'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->permohonanModel->update($id, $updateData)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Permohonan berhasil disetujui'
                ]);
            } else {
                throw new \Exception('Gagal menyetujui permohonan');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject($id)
    {
        try {
            $permohonan = $this->permohonanModel->find($id);
            
            if (!$permohonan) {
                throw new \Exception('Permohonan tidak ditemukan');
            }

            if ($permohonan['status'] != 0) {
                throw new \Exception('Permohonan sudah diproses sebelumnya');
            }

            $catatan = $this->request->getPost('catatan');
            
            if (empty($catatan)) {
                throw new \Exception('Catatan penolakan harus diisi');
            }

            $updateData = [
                'status' => 2,
                'rejected_at' => date('Y-m-d H:i:s'),
                'rejected_by' => $this->session->get('username'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->permohonanModel->update($id, $updateData)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Permohonan berhasil ditolak'
                ]);
            } else {
                throw new \Exception('Gagal menolak permohonan');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
