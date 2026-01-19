<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenerimaanAssetModel;
use App\Models\PenerimaanAssetDetailModel;
use App\Models\PembelianAssetModel;
use App\Models\PembelianAssetDetailModel;
use CodeIgniter\HTTP\ResponseInterface;

class PenerimaanAsset extends BaseController
{
    protected $penerimaanModel;
    protected $detailModel;
    protected $pembelianModel;
    protected $pembelianDetailModel;
    protected $session;

    public function __construct()
    {
        $this->penerimaanModel = new PenerimaanAssetModel();
        $this->detailModel = new PenerimaanAssetDetailModel();
        $this->pembelianModel = new PembelianAssetModel();
        $this->pembelianDetailModel = new PembelianAssetDetailModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Penerimaan Barang',
            'page_title' => 'Penerimaan Barang',
            'breadcrumbs' => ['Asset Management', 'Penerimaan Barang']
        ];

        return view('asset/goods_receive/index', $data);
    }

    public function getData()
    {
        $request = $this->request->getGet();
        
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        
        $builder = $this->penerimaanModel->select('ast_penerimaan_asset.*, 
                                                   ast_pembelian_asset.nomor_pembelian,
                                                   dt_supplier.nama_supplier')
                                        ->join('ast_pembelian_asset', 'ast_pembelian_asset.id = ast_penerimaan_asset.id_pembelian', 'left')
                                        ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left');
        
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('invoice_number', $searchValue)
                    ->orLike('ast_pembelian_asset.nomor_pembelian', $searchValue)
                    ->orLike('dt_supplier.nama_supplier', $searchValue)
                    ->groupEnd();
        }
        
        $totalRecords = $this->penerimaanModel->countAllResults(false);
        $totalFiltered = $builder->countAllResults(false);
        
        $data = $builder->orderBy('ast_penerimaan_asset.id', 'DESC')
                       ->limit($length, $start)
                       ->get()
                       ->getResultArray();
        
        // Format data
        foreach ($data as &$row) {
            $row['tanggal_penerimaan_formatted'] = $row['tanggal_penerimaan'] ? date('d/m/Y', strtotime($row['tanggal_penerimaan'])) : '-';
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
            'id_pembelian' => 'required|integer',
            'invoice_number' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $pembelianId = $this->request->getPost('id_pembelian');
            
            // Check pembelian
            $pembelian = $this->pembelianModel->find($pembelianId);
            if (!$pembelian) {
                throw new \Exception('Purchase Order tidak ditemukan');
            }

            $data = [
                'id_pembelian' => $pembelianId,
                'invoice_number' => $this->request->getPost('invoice_number'),
                'nomor_pembelian' => $pembelian['nomor_pembelian'],
                'tanggal_penerimaan' => $this->request->getPost('tanggal_penerimaan'),
                'keterangan' => $this->request->getPost('keterangan'),
                'created_by' => $this->session->get('username')
            ];

            if ($this->penerimaanModel->insert($data)) {
                $penerimaanId = $this->penerimaanModel->getInsertID();

                // Copy details from pembelian
                $pembelianDetails = $this->pembelianDetailModel->where('pembelian_id', $pembelianId)->findAll();
                
                foreach ($pembelianDetails as $detail) {
                    $detailData = [
                        'id_penerimaan' => $penerimaanId,
                        'invoice_number' => $data['invoice_number'],
                        'pembelian_detail_id' => $detail['id'],
                        'kategori_id' => $detail['kategori_id'],
                        'kelompok_id' => $detail['kelompok_id'],
                        'nama_item' => $detail['nama_item'],
                        'qty' => $detail['qty'],
                        'qty_gr' => 0, // Will be filled later
                    ];
                    $this->detailModel->insert($detailData);
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Penerimaan berhasil dibuat',
                    'data' => [
                        'id' => $penerimaanId
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
        $data = $this->penerimaanModel->select('ast_penerimaan_asset.*, 
                                               ast_pembelian_asset.nomor_pembelian,
                                               dt_supplier.nama_supplier')
                                     ->join('ast_pembelian_asset', 'ast_pembelian_asset.id = ast_penerimaan_asset.id_pembelian', 'left')
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
        $validation = \Config\Services::validation();
        
        $rules = [
            'invoice_number' => 'required|max_length[100]',
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
                'invoice_number' => $this->request->getPost('invoice_number'),
                'tanggal_penerimaan' => $this->request->getPost('tanggal_penerimaan'),
                'keterangan' => $this->request->getPost('keterangan'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->penerimaanModel->update($id, $data)) {
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
            $this->penerimaanModel->update($id, [
                'deleted_by' => $this->session->get('username')
            ]);

            if ($this->penerimaanModel->delete($id)) {
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
        // Get pembelian yang belum ada penerimaannya
        $pembelian = $this->pembelianModel
                          ->select('ast_pembelian_asset.id, ast_pembelian_asset.nomor_pembelian, dt_supplier.nama_supplier')
                          ->join('dt_supplier', 'dt_supplier.id = ast_pembelian_asset.supplier_id', 'left')
                          ->whereNotIn('ast_pembelian_asset.id', function($builder) {
                              return $builder->select('id_pembelian')
                                           ->from('ast_penerimaan_asset')
                                           ->where('is_deleted', false);
                          })
                          ->findAll();

        return $this->response->setJSON([
            'pembelian' => $pembelian
        ]);
    }

    public function updateQtyGr($id)
    {
        try {
            $qtyGr = $this->request->getPost('qty_gr');
            
            if ($qtyGr === null || $qtyGr === '') {
                throw new \Exception('Qty Terima harus diisi');
            }

            if ($this->detailModel->update($id, ['qty_gr' => $qtyGr])) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Qty Terima berhasil diupdate'
                ]);
            } else {
                throw new \Exception('Gagal mengupdate Qty Terima');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
