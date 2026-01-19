<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsrUserTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('usr_user')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'nohp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'nomor_registrasi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'departemen_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
            'active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
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
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('usr_user');
    }

    public function down()
    {
        $this->forge->dropTable('usr_user');
    }
}
