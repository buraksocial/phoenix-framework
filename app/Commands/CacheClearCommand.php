<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected static $defaultName = 'cache:clear';

    protected function configure()
    {
        $this->setDescription('Uygulama önbelleğini temizler.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Config önbelleğini temizle
        $cachedConfigPath = BASE_PATH . '/bootstrap/cache/config.php';
        if (file_exists($cachedConfigPath)) {
            unlink($cachedConfigPath);
            $output->writeln('<info>Yapılandırma önbelleği temizlendi.</info>');
        }

        // Rota önbelleğini temizle
        $cachedRoutesPath = BASE_PATH . '/bootstrap/cache/routes.php';
        if (file_exists($cachedRoutesPath)) {
            unlink($cachedRoutesPath);
            $output->writeln('<info>Rota önbelleği temizlendi.</info>');
        }

        // Uygulama önbelleğini temizle (Core\Cache\Cache sınıfı üzerinden)
        try {
            app(\Core\Cache\Cache::class)->flush();
            $output->writeln('<info>Uygulama önbelleği temizlendi.</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>Uygulama önbelleği temizlenirken hata oluştu: ' . $e->getMessage() . '</error>');
        }

        $output->writeln('<info>Tüm önbellekler temizlendi.</info>');

        return Command::SUCCESS;
    }
}
