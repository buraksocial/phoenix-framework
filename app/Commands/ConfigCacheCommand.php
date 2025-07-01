<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCacheCommand extends Command
{
    protected static $defaultName = 'config:cache';

    protected function configure()
    {
        $this->setDescription('Yapılandırma dosyalarını önbelleğe alır.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cachedConfigPath = BASE_PATH . '/bootstrap/cache/config.php';

        // Önbellek dizinini oluştur
        if (!is_dir(dirname($cachedConfigPath))) {
            mkdir(dirname($cachedConfigPath), 0755, true);
        }

        // Mevcut tüm yapılandırmayı yükle
        $config = app(\Core\Config::class);
        $allConfig = $config->get(''); // Tüm yapılandırmayı al

        // PHP kodu olarak önbelleğe kaydet
        file_put_contents(
            $cachedConfigPath,
            '<?php return ' . var_export($allConfig, true) . ';'
        );

        $output->writeln('<info>Yapılandırma başarıyla önbelleğe alındı.</info>');

        return Command::SUCCESS;
    }
}
