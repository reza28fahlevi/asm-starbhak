<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstPermohonanAssetDetailTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_permohonan_asset_detail')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'permohonan_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'nomor_permohonan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'kelompok_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'nama_asset' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'harga' => [
                'type' => 'NUMERIC',
                'null' => true,
            ],
            'qty' => [
                'type' => 'NUMERIC',
                'null' => true,
            ],
            'lampiran_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('permohonan_id', 'ast_permohonan_asset', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kelompok_id', 'dt_kelompok_asset', 'kelompok_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_permohonan_asset_detail');
    }

    public function down()
    {
        $this->forge->dropTable('ast_permohonan_asset_detail');
    }
}
