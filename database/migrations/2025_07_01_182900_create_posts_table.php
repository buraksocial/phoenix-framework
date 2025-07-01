<?php

use Core\Database\DriverInterface;

/**
 * CreatePostsTable Migration
 *
 * Blog gönderileri için `posts` tablosunu oluşturur.
 */
class CreatePostsTable
{
    /**
     * Migration'ı çalıştırır.
     * @param DriverInterface $db
     */
    public function up(DriverInterface $db)
    {
        $query = "
            CREATE TABLE IF NOT EXISTS `posts` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `slug` VARCHAR(255) NOT NULL UNIQUE,
                `body` TEXT NOT NULL,
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `deleted_at` TIMESTAMP NULL,
                INDEX `posts_user_id_index` (`user_id`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            );
        ";
        
        $db->execute($query);
    }

    /**
     * Migration'ı geri alır.
     * @param DriverInterface $db
     */
    public function down(DriverInterface $db)
    {
        $db->execute("DROP TABLE IF EXISTS `posts`;");
    }
}
