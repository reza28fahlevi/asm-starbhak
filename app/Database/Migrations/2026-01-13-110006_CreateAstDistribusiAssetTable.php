<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstDistribusiAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_distribusi_asset')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'asset_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'lokasi_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'tgldistribusi' => [
                'type' => 'TIMESTAMP',
                'null' => true,
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('asset_id', 'ast_asset', 'asset_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('lokasi_id', 'dt_lokasi', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_distribusi_asset');
    }

    public function down()
    {
        $this->forge->dropTable('ast_distribusi_asset');
    }
}
