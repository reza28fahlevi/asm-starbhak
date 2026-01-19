<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembelianAssetModel;
use App\Models\PembelianAssetDetailModel;
use App\Models\PermohonanAssetModel;
use App\Models\PermohonanAssetDetailModel;
use App\Models\SupplierModel;
use App\Models\KategoriAssetModel;
use App\Models\KelompokAssetModel;
use CodeIgniter\HTTP\ResponseInterface;

class PembelianAsset extends BaseController
{
    protected $pembelianModel;
    protected $detailModel;
    protected $permohonanModel;
    protected $permohonanDetailModel;
    protected $supplierModel;
    protected $kategoriModel;
    protected $kelompokModel;
    protected $session;

    public function __construct()
    {
        $this->pembelianModel = new PembelianAssetModel();
        $this->detailModel = new PembelianAssetDetailModel();
        $this->permohonanModel = new PermohonanAssetModel();
        $this->permohonanDetailModel = new PermohonanAssetDetailModel();
        $this->supplierModel = new SupplierModel();
        $this->kategoriModel = new KategoriAssetModel();
        $this->kelompokModel = new KelompokAssetModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Pembelian Asset',
            'page_title' => 'Pembelian Asset',
            'breadcrumbs' => ['Asset Management', 'Pembelian Asset']
        ];

        return view('asset/asset_purchase/index', $data);
    }

    public function getData()
    {
        $request = $this->request->getGet();
        
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        
        $builder = $this->pembelianModel->select('ast_pembelian_asset.*, dt_supplier.nama_supplier')
                                       ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left');
        
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('nomor_pembelian', $searchValue)
                    ->orLike('nomor_permohonan', $searchValue)
                    ->orLike('dt_supplier.nama_supplier', $searchValue)
                    ->groupEnd();
        }
        
        $totalRecords = $this->pembelianModel->countAllResults(false);
        $totalFiltered = $builder->countAllResults(false);
        
        $data = $builder->orderBy('ast_pembelian_asset.id', 'DESC')
                       ->limit($length, $start)
                       ->get()
                       ->getResultArray();
        
        // Format data
        foreach ($data as &$row) {
            $row['total_anggaran_formatted'] = 'Rp ' . number_format($row['total_anggaran'] ?? 0, 0, ',', '.');
            $row['tanggal_pembelian_formatted'] = $row['tanggal_pembelian'] ? date('d/m/Y', strtotime($row['tanggal_pembelian'])) : '-';
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
        try {
            $permohonanId = $this->request->getPost('permohonan_id');
            
            if (!$permohonanId) {
                throw new \Exception('Permohonan harus dipilih');
            }

            // Check permohonan
            $permohonan = $this->permohonanModel->find($permohonanId);
            if (!$permohonan) {
                throw new \Exception('Permohonan tidak ditemukan');
            }

            if ($permohonan['status'] != 1) {
                throw new \Exception('Permohonan belum disetujui');
            }

            // Generate nomor pembelian
            $nomorPembelian = $this->pembelianModel->generateNomorPembelian();
            
            $data = [
                'nomor_pembelian' => $nomorPembelian,
                'permohonan_id' => $permohonanId,
                'nomor_permohonan' => $permohonan['nomor_permohonan'],
                'supplier_id' => $this->request->getPost('supplier_id'),
                'tanggal_pembelian' => $this->request->getPost('tanggal_pembelian'),
                'keterangan' => $this->request->getPost('keterangan'),
                'total_anggaran' => 0,
                'created_by' => $this->session->get('username')
            ];

            if ($this->pembelianModel->insert($data)) {
                $pembelianId = $this->pembelianModel->getInsertID();

                // Copy details from permohonan
                $permohonanDetails = $this->permohonanDetailModel->where('permohonan_id', $permohonanId)->findAll();
                
                foreach ($permohonanDetails as $detail) {
                    $detailData = [
                        'pembelian_id' => $pembelianId,
                        'nomor_pembelian' => $nomorPembelian,
                        'permohonan_detail_id' => $detail['id'],
                        'kelompok_id' => $detail['kelompok_id'],
                        'nama_item' => $detail['nama_asset'],
                        'qty' => $detail['qty'],
                        'harga' => $detail['harga'],
                        'keterangan' => $detail['keterangan']
                    ];
                    $this->detailModel->insert($detailData);
                }

                // Update total
                $this->updateTotalAnggaran($pembelianId);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pembelian berhasil dibuat',
                    'data' => [
                        'id' => $pembelianId,
                        'nomor_pembelian' => $nomorPembelian
                    ]
                ]);
            } else {
                throw new \Exception('Gagal menyimpan data');
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
        $data = $this->pembelianModel->select('ast_pembelian_asset.*, dt_supplier.nama_supplier')
                                    ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left')
                                    ->find($id);
        
        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Get details
        $details = $this->detailModel->getDetailWithRelation($id);
        $data['details'] = $details;

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function update($id)
    {
        try {
            $data = [
                'supplier_id' => $this->request->getPost('supplier_id'),
                'tanggal_pembelian' => $this->request->getPost('tanggal_pembelian'),
                'keterangan' => $this->request->getPost('keterangan'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->pembelianModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                throw new \Exception('Gagal mengupdate data');
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
            $this->pembelianModel->update($id, [
                'deleted_by' => $this->session->get('username')
            ]);

            if ($this->pembelianModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                throw new \Exception('Gagal menghapus data');
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
        // Get approved permohonan yang belum ada pembeliannya
        $permohonan = $this->permohonanModel
                          ->select('id, nomor_permohonan, departemen, total_anggaran')
                          ->where('status', 1)
                          ->whereNotIn('id', function($builder) {
                              return $builder->select('permohonan_id')
                                           ->from('ast_pembelian_asset')
                                           ->where('is_deleted', false);
                          })
                          ->findAll();

        $supplier = $this->supplierModel->select('id, nama_supplier')->findAll();
        $kategori = $this->kategoriModel->select('id, nama_kategori')->findAll();
        $kelompok = $this->kelompokModel->select('kelompok_id as id, nama_kelompok')->findAll();

        return $this->response->setJSON([
            'permohonan' => $permohonan,
            'supplier' => $supplier,
            'kategori' => $kategori,
            'kelompok' => $kelompok
        ]);
    }

    public function storeDetail()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'pembelian_id' => 'required|integer',
            'kelompok_id' => 'required|integer',
            'nama_item' => 'required',
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
            $pembelianId = $this->request->getPost('pembelian_id');
            $pembelian = $this->pembelianModel->find($pembelianId);

            if (!$pembelian) {
                throw new \Exception('Pembelian tidak ditemukan');
            }

            $data = [
                'pembelian_id' => $pembelianId,
                'nomor_pembelian' => $pembelian['nomor_pembelian'],
                'kategori_id' => $this->request->getPost('kategori_id'),
                'kelompok_id' => $this->request->getPost('kelompok_id'),
                'nama_item' => $this->request->getPost('nama_item'),
                'keterangan' => $this->request->getPost('keterangan'),
                'harga' => $this->request->getPost('harga'),
                'qty' => $this->request->getPost('qty'),
            ];

            if ($this->detailModel->insert($data)) {
                $this->updateTotalAnggaran($pembelianId);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Detail berhasil ditambahkan'
                ]);
            } else {
                throw new \Exception('Gagal menyimpan detail');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateDetail($id)
    {
        try {
            $detail = $this->detailModel->find($id);
            if (!$detail) {
                throw new \Exception('Detail tidak ditemukan');
            }

            $data = [
                'kategori_id' => $this->request->getPost('kategori_id'),
                'kelompok_id' => $this->request->getPost('kelompok_id'),
                'nama_item' => $this->request->getPost('nama_item'),
                'keterangan' => $this->request->getPost('keterangan'),
                'harga' => $this->request->getPost('harga'),
                'qty' => $this->request->getPost('qty'),
            ];

            if ($this->detailModel->update($id, $data)) {
                $this->updateTotalAnggaran($detail['pembelian_id']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Detail berhasil diupdate'
                ]);
            } else {
                throw new \Exception('Gagal mengupdate detail');
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
                $this->updateTotalAnggaran($detail['pembelian_id']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Detail berhasil dihapus'
                ]);
            } else {
                throw new \Exception('Gagal menghapus detail');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function updateTotalAnggaran($pembelianId)
    {
        $details = $this->detailModel->where('pembelian_id', $pembelianId)->findAll();
        $total = 0;
        
        foreach ($details as $detail) {
            $total += ($detail['harga'] ?? 0) * ($detail['qty'] ?? 0);
        }

        $this->pembelianModel->update($pembelianId, [
            'total_anggaran' => $total,
            'updated_by' => $this->session->get('username')
        ]);
    }
}
