<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_asset')) {
            return;
        }

        $this->forge->addField([
            'asset_id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'kode_asset' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'nama_asset' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'merk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'spesifikasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'kategori_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'mobile' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'lampiran' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'kondisi' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'is_deleted' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('asset_id', true);
        $this->forge->addForeignKey('kategori_id', 'dt_kategori_asset', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_asset');
    }

    public function down()
    {
        $this->forge->dropTable('ast_asset');
    }
}
