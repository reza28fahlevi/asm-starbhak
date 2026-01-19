<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDtKelompokAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('dt_kelompok_asset')) {
            return;
        }

        $this->forge->addField([
            'kelompok_id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'nama_kelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
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

        $this->forge->addKey('kelompok_id', true);
        $this->forge->createTable('dt_kelompok_asset');
    }

    public function down()
    {
        $this->forge->dropTable('dt_kelompok_asset');
    }
}
