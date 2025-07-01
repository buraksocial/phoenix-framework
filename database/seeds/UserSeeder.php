<?php
namespace Database\Seeds;

use Core\Database\Seeder;
use Core\Database\Connection as DBConnection;

class UserSeeder extends Seeder
{
    /**
     * Veritabanını tohumla.
     */
    public function run(): void
    {
        $db = app(DBConnection::class)->connection();

        // Örnek bir kullanıcı oluştur
        $db->insert('users', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT), // Şifreyi hash'le
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Daha fazla kullanıcı eklemek isterseniz buraya ekleyebilirsiniz.
        $db->insert('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
