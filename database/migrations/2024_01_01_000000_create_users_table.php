<?php
namespace Database\Migrations;

use Core\Database\Migration;
use PDO;

class CreateUsersTable extends Migration
{
    /**
     * Migration'ı çalıştır.
     * Tabloları ve sütunları oluştur.
     */
    public function up(): void
    {
        $this->schema->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('user'); // 'admin', 'editor', 'user' vb.
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps(); // created_at ve updated_at
        });
    }

    /**
     * Migration'ı geri al.
     * Tabloları sil.
     */
    public function down(): void
    {
        $this->schema->dropIfExists('users');
    }
}
