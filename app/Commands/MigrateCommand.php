<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Core\Database\Connection as DBConnection;

class MigrateCommand extends Command
{
    protected static $defaultName = 'migrate';

    protected function configure()
    {
        $this->setDescription('Veritabanı migrationlarını çalıştırır.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = app(DBConnection::class)->connection();

        // migrations tablosunu oluştur (yoksa)
        $db->statement("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $migratedFiles = $db->select("SELECT migration FROM migrations");
        $migratedFiles = array_column($migratedFiles, 'migration');

        $migrationsPath = BASE_PATH . '/database/migrations';
        $files = glob($migrationsPath . '/*.php');
        sort($files); // Dosyaları isme göre sırala

        $batch = $db->first("SELECT MAX(batch) as max_batch FROM migrations")->max_batch ?? 0;
        $batch++;

        $newMigrationsCount = 0;

        foreach ($files as $file) {
            $fileName = basename($file, '.php');

            if (!in_array($fileName, $migratedFiles)) {
                require_once $file;
                $className = 'Database\\Migrations\\' . $fileName;
                $migration = new $className();
                $migration->up();

                $db->insert('migrations', [
                    'migration' => $fileName,
                    'batch' => $batch,
                ]);

                $output->writeln('<info>Migrated:</info> ' . $fileName);
                $newMigrationsCount++;
            }
        }

        if ($newMigrationsCount === 0) {
            $output->writeln('<comment>Hiçbir yeni migration bulunamadı.</comment>');
        } else {
            $output->writeln('<info>Migration tamamlandı. Toplam ' . $newMigrationsCount . ' yeni migration çalıştırıldı.</info>');
        }

        return Command::SUCCESS;
    }
}