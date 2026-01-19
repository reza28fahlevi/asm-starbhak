<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribusiAssetModel;
use App\Models\AssetModel;
use App\Models\LokasiModel;
use App\Models\PenerimaanAssetDetailModel;
use CodeIgniter\HTTP\ResponseInterface;

class DistribusiAsset extends BaseController
{
    protected $distribusiModel;
    protected $assetModel;
    protected $lokasiModel;
    protected $penerimaanDetailModel;
    protected $session;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiAssetModel();
        $this->assetModel = new AssetModel();
        $this->lokasiModel = new LokasiModel();
        $this->penerimaanDetailModel = new PenerimaanAssetDetailModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Distribusi Asset',
            'page_title' => 'Distribusi Asset',
            'breadcrumbs' => ['Asset Management', 'Distribusi Asset']
        ];

        return view('asset/assets_distribution/index', $data);
    }

    public function getData()
    {
        $request = $this->request->getGet();
        
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        
        $builder = $this->distribusiModel->select('ast_distribusi_asset.*, 
                                                   ast_asset.kode_asset,
                                                   ast_asset.nama_asset,
                                                   dt_lokasi.nama_lokasi')
                                        ->join('ast_asset', 'ast_asset.asset_id = ast_distribusi_asset.asset_id', 'left')
                                        ->join('dt_lokasi', 'dt_lokasi.id = ast_distribusi_asset.lokasi_id', 'left');
        
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('ast_asset.kode_asset', $searchValue)
                    ->orLike('ast_asset.nama_asset', $searchValue)
                    ->orLike('dt_lokasi.nama_lokasi', $searchValue)
                    ->groupEnd();
        }
        
        $totalRecords = $this->distribusiModel->countAllResults(false);
        $totalFiltered = $builder->countAllResults(false);
        
        $data = $builder->orderBy('ast_distribusi_asset.id', 'DESC')
                       ->limit($length, $start)
                       ->get()
                       ->getResultArray();
        
        // Format data
        foreach ($data as &$row) {
            $row['tgldistribusi_formatted'] = $row['tgldistribusi'] ? date('d/m/Y', strtotime($row['tgldistribusi'])) : '-';
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
            'asset_id' => 'required|integer',
            'lokasi_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $assetId = $this->request->getPost('asset_id');
            
            // Check if asset already distributed
            $existing = $this->distribusiModel->where('asset_id', $assetId)->first();
            if ($existing) {
                throw new \Exception('Asset sudah didistribusikan');
            }

            $data = [
                'asset_id' => $assetId,
                'lokasi_id' => $this->request->getPost('lokasi_id'),
                'tgldistribusi' => $this->request->getPost('tgldistribusi') ?: date('Y-m-d'),
                'keterangan' => $this->request->getPost('keterangan'),
                'created_by' => $this->session->get('username')
            ];

            if ($this->distribusiModel->insert($data)) {
                // Update asset status to distributed
                $this->assetModel->update($assetId, [
                    'status' => 'distributed',
                    'updated_by' => $this->session->get('username')
                ]);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Distribusi berhasil disimpan'
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
        $data = $this->distribusiModel->select('ast_distribusi_asset.*, 
                                               ast_asset.kode_asset,
                                               ast_asset.nama_asset,
                                               ast_asset.merk,
                                               ast_asset.tipe,
                                               ast_asset.serial_number,
                                               dt_lokasi.nama_lokasi,
                                               dt_kategori_asset.nama_kategori')
                                     ->join('ast_asset', 'ast_asset.asset_id = ast_distribusi_asset.asset_id', 'left')
                                     ->join('dt_lokasi', 'dt_lokasi.id = ast_distribusi_asset.lokasi_id', 'left')
                                     ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_asset.kategori_id', 'left')
                                     ->find($id);
        
        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'lokasi_id' => 'required|integer',
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
                'lokasi_id' => $this->request->getPost('lokasi_id'),
                'tgldistribusi' => $this->request->getPost('tgldistribusi'),
                'keterangan' => $this->request->getPost('keterangan'),
                'updated_by' => $this->session->get('username')
            ];

            if ($this->distribusiModel->update($id, $data)) {
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
            $distribusi = $this->distribusiModel->find($id);
            if (!$distribusi) {
                throw new \Exception('Data tidak ditemukan');
            }

            $this->distribusiModel->update($id, [
                'deleted_by' => $this->session->get('username')
            ]);

            if ($this->distribusiModel->delete($id)) {
                // Update asset status back to available
                $this->assetModel->update($distribusi['asset_id'], [
                    'status' => 'available',
                    'updated_by' => $this->session->get('username')
                ]);

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
        // Get penerimaan details dengan qty_gr > 0 yang belum dijadikan asset
        $penerimaanDetails = $this->penerimaanDetailModel
            ->select('ast_penerimaan_asset_detail.*, 
                     ast_penerimaan_asset.invoice_number,
                     dt_kelompok_asset.nama_kelompok,
                     dt_kategori_asset.nama_kategori')
            ->join('ast_penerimaan_asset', 'ast_penerimaan_asset.id = ast_penerimaan_asset_detail.id_penerimaan', 'left')
            ->join('dt_kelompok_asset', 'dt_kelompok_asset.kelompok_id = ast_penerimaan_asset_detail.kelompok_id', 'left')
            ->join('dt_kategori_asset', 'dt_kategori_asset.id = ast_penerimaan_asset_detail.kategori_id', 'left')
            ->where('ast_penerimaan_asset_detail.qty_gr >', 0)
            ->findAll();

        // Get available assets (already created but not distributed)
        $assets = $this->assetModel->getAvailableAssets();

        $lokasi = $this->lokasiModel->select('id, nama_lokasi, parent_id')->findAll();

        return $this->response->setJSON([
            'penerimaan_details' => $penerimaanDetails,
            'assets' => $assets,
            'lokasi' => $lokasi
        ]);
    }

    public function createAssetFromPenerimaan()
    {
        try {
            $penerimaanDetailId = $this->request->getPost('penerimaan_detail_id');
            $qty = (int) $this->request->getPost('qty');

            if (!$penerimaanDetailId || $qty <= 0) {
                throw new \Exception('Data tidak valid');
            }

            // Get penerimaan detail
            $detail = $this->penerimaanDetailModel->find($penerimaanDetailId);
            if (!$detail) {
                throw new \Exception('Detail penerimaan tidak ditemukan');
            }

            if ($qty > $detail['qty_gr']) {
                throw new \Exception('Jumlah melebihi qty yang diterima');
            }

            // Create assets
            $createdAssets = [];
            for ($i = 0; $i < $qty; $i++) {
                $kodeAsset = $this->assetModel->generateKodeAsset();
                
                $assetData = [
                    'kode_asset' => $kodeAsset,
                    'nama_asset' => $detail['nama_item'],
                    'kategori_id' => $detail['kategori_id'],
                    'penerimaan_detail_id' => $penerimaanDetailId,
                    'status' => 'available',
                    'kondisi' => 'baik',
                    'created_by' => $this->session->get('username')
                ];

                if ($this->assetModel->insert($assetData)) {
                    $createdAssets[] = $kodeAsset;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => count($createdAssets) . ' asset berhasil dibuat',
                'data' => $createdAssets
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
