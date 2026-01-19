<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsrOtorisasiMenuTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('usr_otorisasi_menu')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'role_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'menu_id' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'usr_role', 'role_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('menu_id', 'menus', 'menu_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('usr_otorisasi_menu');
    }

    public function down()
    {
        $this->forge->dropTable('usr_otorisasi_menu');
    }
}
