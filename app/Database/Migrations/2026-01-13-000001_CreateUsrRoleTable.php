<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsrRoleTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('usr_role')) {
            return;
        }

        $this->forge->addField([
            'role_id' => [
                'type'       => 'SERIAL',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('role_id', true);
        $this->forge->createTable('usr_role');
    }

    public function down()
    {
        $this->forge->dropTable('usr_role');
    }
}
