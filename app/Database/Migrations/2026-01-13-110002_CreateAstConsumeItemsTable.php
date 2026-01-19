<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstConsumeItemsTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_consume_items')) {
            return;
        }

        $this->forge->addField([
            'citem_id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'item_id' => [
                'type' => 'INTEGER',
                'null' => true,
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
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
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

        $this->forge->addKey('citem_id', true);
        $this->forge->addForeignKey('item_id', 'ast_items', 'item_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_consume_items');
    }

    public function down()
    {
        $this->forge->dropTable('ast_consume_items');
    }
}
