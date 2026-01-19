<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstMaintenanceAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_maintenance_asset')) {
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
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'tglperbaikan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tglselesaiperbaikan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'invoice' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'anggaran' => [
                'type' => 'NUMERIC',
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
        $this->forge->createTable('ast_maintenance_asset');
    }

    public function down()
    {
        $this->forge->dropTable('ast_maintenance_asset');
    }
}
