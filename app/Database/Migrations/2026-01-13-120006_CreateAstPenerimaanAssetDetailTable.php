<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstPenerimaanAssetDetailTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_penerimaan_asset_detail')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'id_penerimaan' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'invoice_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
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
            'qty' => [
                'type' => 'NUMERIC',
                'null' => true,
            ],
            'qty_gr' => [
                'type' => 'NUMERIC',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_penerimaan', 'ast_penerimaan_asset', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kategori_id', 'dt_kategori_asset', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('kelompok_id', 'dt_kelompok_asset', 'kelompok_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_penerimaan_asset_detail');
    }

    public function down()
    {
        $this->forge->dropTable('ast_penerimaan_asset_detail');
    }
}
