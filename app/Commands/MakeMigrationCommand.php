<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * MakeMigrationCommand
 *
 * `php artisan make:migration create_products_table` komutunu çalıştırır.
 * Bu komut, veritabanı şeması değişiklikleri için yeni bir migration dosyası oluşturur.
 */
class MakeMigrationCommand extends Command
{
    protected static $defaultName = 'make:migration';
    protected static $defaultDescription = 'Yeni bir veritabanı migration dosyası oluşturur.';

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Migration\'ın adı (örn: create_products_table).')
            ->setHelp('Bu komut, /database/migrations klasöründe yeni bir migration dosyası oluşturur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        
        $className = implode('', array_map('ucfirst', explode('_', $name)));

        $stubPath = BASE_PATH . '/stubs/migration.stub';
        if (!file_exists($stubPath)) {
            $io->error("Şablon dosyası bulunamadı: {$stubPath}");
            return Command::FAILURE;
        }

        $timestamp = date('Y_m_d_His');
        $filePath = BASE_PATH . "/database/migrations/{$timestamp}_{$name}.php";

        if (file_exists($filePath)) {
            $io->error("Migration dosyası zaten mevcut: {$filePath}");
            return Command::FAILURE;
        }

        $stub = file_get_contents($stubPath);
        $content = str_replace('{{className}}', $className, $stub);

        if (file_put_contents($filePath, $content) === false) {
            $io->error("Migration dosyası oluşturulamadı. Lütfen dosya izinlerini kontrol edin.");
            return Command::FAILURE;
        }

        $io->success("Migration başarıyla oluşturuldu: {$filePath}");
        return Command::SUCCESS;
    }
}
