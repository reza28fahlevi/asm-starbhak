<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaBeritaAcaraTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ba_berita_acara')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'jenis_berita' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'asset_id' => [
                'type' => 'INTEGER',
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
        $this->forge->createTable('ba_berita_acara');
    }

    public function down()
    {
        $this->forge->dropTable('ba_berita_acara');
    }
}
