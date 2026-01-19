<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstPembelianAssetDetailTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_pembelian_asset_detail')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'kategori_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'kelompok_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'nama_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'dt_kategori_asset', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('kelompok_id', 'dt_kelompok_asset', 'kelompok_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_pembelian_asset_detail');
    }

    public function down()
    {
        $this->forge->dropTable('ast_pembelian_asset_detail');
    }
}
