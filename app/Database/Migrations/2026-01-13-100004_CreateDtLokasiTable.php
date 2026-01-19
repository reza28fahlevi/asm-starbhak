<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDtLokasiTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('dt_lokasi')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'level' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'induk' => [
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
        $this->forge->createTable('dt_lokasi');
    }

    public function down()
    {
        $this->forge->dropTable('dt_lokasi');
    }
}
