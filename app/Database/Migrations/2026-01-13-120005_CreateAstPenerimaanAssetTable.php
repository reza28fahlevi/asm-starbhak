<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAstPenerimaanAssetTable extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if ($this->db->tableExists('ast_penerimaan_asset')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
            ],
            'id_pembelian' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'invoice_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'nomor_pembelian' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_pembelian', 'ast_pembelian_asset', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('ast_penerimaan_asset');
    }

    public function down()
    {
        $this->forge->dropTable('ast_penerimaan_asset');
    }
}
