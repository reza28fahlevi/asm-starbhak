<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin user already exists
        $existing = $this->db->table('usr_user')
                             ->where('username', 'admin')
                             ->get()
                             ->getRow();
        
        if ($existing) {
            echo "User 'admin' sudah ada.\n";
            return;
        }
        
        $data = [
            'username'         => 'admin',
            'password'         => password_hash('admin123', PASSWORD_DEFAULT),
            'email'            => 'admin@pkm-asm.com',
            'nama'             => 'Administrator',
            'nohp'             => '08123456789',
            'nomor_registrasi' => 'ADM001',
            'departemen_id'    => 1,
            'active'           => true,
            'created_at'       => date('Y-m-d H:i:s'),
            'created_by'       => 'system',
            'is_deleted'       => false,
        ];

        $this->db->table('usr_user')->insert($data);
        
        echo "User 'admin' berhasil dibuat.\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    }
}
