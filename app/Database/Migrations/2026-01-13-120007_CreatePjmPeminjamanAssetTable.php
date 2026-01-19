<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePjmPeminjamanAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('pjm_peminjaman_asset')) {
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
            'peminjam' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tglpinjam' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'tglpengembalian' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'durasipinjam' => [
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
        $this->forge->createTable('pjm_peminjaman_asset');
    }

    public function down()
    {
        $this->forge->dropTable('pjm_peminjaman_asset');
    }
}
