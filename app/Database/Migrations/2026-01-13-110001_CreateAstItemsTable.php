<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstItemsTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_items')) {
            return;
        }

        $this->forge->addField([
            'item_id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'merk' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'qty' => [
                'type' => 'NUMERIC',
                'null' => true,
            ],
            'satuan_qty' => [
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

        $this->forge->addKey('item_id', true);
        $this->forge->createTable('ast_items');
    }

    public function down()
    {
        $this->forge->dropTable('ast_items');
    }
}
