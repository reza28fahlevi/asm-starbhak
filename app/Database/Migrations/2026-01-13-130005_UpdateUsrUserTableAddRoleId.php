<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsrUserTableAddRoleId extends Migration
{
    public function up()
    {
        // Add role_id column if it doesn't exist
        if (!$this->db->fieldExists('role_id', 'usr_user')) {
            $this->forge->addColumn('usr_user', [
                'role_id' => [
                    'type' => 'INTEGER',
                    'null' => true,
                    'after' => 'departemen_id',
                ],
            ]);
        }
        
        // Add token column if it doesn't exist
        if (!$this->db->fieldExists('token', 'usr_user')) {
            $this->forge->addColumn('usr_user', [
                'token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                    'after' => 'active',
                ],
            ]);
        }
        
        // Add foreign key for role_id if it doesn't exist
        $foreignKeyExists = $this->db->query("
            SELECT constraint_name 
            FROM information_schema.table_constraints 
            WHERE table_name = 'usr_user' 
            AND constraint_name = 'usr_user_role_id_foreign'
        ")->getRow();
        
        if (!$foreignKeyExists && $this->db->tableExists('usr_role')) {
            try {
                $this->db->query('ALTER TABLE usr_user ADD CONSTRAINT usr_user_role_id_foreign FOREIGN KEY (role_id) REFERENCES usr_role(role_id) ON DELETE SET NULL ON UPDATE CASCADE');
            } catch (\Exception $e) {
                // Skip if constraint cannot be added due to data inconsistency
                log_message('warning', 'Could not add usr_user_role_id_foreign constraint: ' . $e->getMessage());
            }
        }
        
        // Add foreign key for departemen_id if it doesn't exist
        $foreignKeyExists = $this->db->query("
            SELECT constraint_name 
            FROM information_schema.table_constraints 
            WHERE table_name = 'usr_user' 
            AND constraint_name = 'usr_user_departemen_id_foreign'
        ")->getRow();
        
        if (!$foreignKeyExists && $this->db->tableExists('dt_departemen')) {
            try {
                $this->db->query('ALTER TABLE usr_user ADD CONSTRAINT usr_user_departemen_id_foreign FOREIGN KEY (departemen_id) REFERENCES dt_departemen(id) ON DELETE SET NULL ON UPDATE CASCADE');
            } catch (\Exception $e) {
                // Skip if constraint cannot be added due to data inconsistency
                log_message('warning', 'Could not add usr_user_departemen_id_foreign constraint: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        // Drop foreign keys first
        $this->db->query('ALTER TABLE usr_user DROP CONSTRAINT IF EXISTS usr_user_role_id_foreign');
        $this->db->query('ALTER TABLE usr_user DROP CONSTRAINT IF EXISTS usr_user_departemen_id_foreign');
        
        // Drop columns
        $this->forge->dropColumn('usr_user', ['role_id', 'token']);
    }
}
