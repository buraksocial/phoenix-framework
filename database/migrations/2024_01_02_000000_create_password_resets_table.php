<?php
namespace Database\Migrations;

use Core\Database\Migration;
use PDO;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Migration'ı çalıştır.
     * Tabloları ve sütunları oluştur.
     */
    public function up(): void
    {
        $this->schema->create('password_resets', function ($table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Migration'ı geri al.
     * Tabloları sil.
     */
    public function down(): void
    {
        $this->schema->dropIfExists('password_resets');
    }
}
