<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KeyGenerateCommand extends Command
{
    protected static $defaultName = 'key:generate';

    protected function configure()
    {
        $this->setDescription('Uygulama anahtarını oluşturur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $this->generateRandomKey();

        $path = BASE_PATH . '/.env';

        if (!file_exists($path)) {
            $output->writeln('<error>.env dosyası bulunamadı. Lütfen .env.example dosyasını kopyalayarak başlayın.</error>');
            return Command::FAILURE;
        }

        if (str_contains(file_get_contents($path), 'APP_KEY=')) {
            file_put_contents($path, preg_replace(
                '/^APP_KEY=.*$/m',
                'APP_KEY=' . $key,
                file_get_contents($path)
            ));
        } else {
            file_put_contents($path, file_get_contents($path) . '\nAPP_KEY=' . $key);
        }

        $output->writeln('<info>Uygulama anahtarı başarıyla oluşturuldu:</info> ' . $key);

        return Command::SUCCESS;
    }

    /**
     * Rastgele bir 32 karakterli anahtar oluşturur.
     *
     * @return string
     */
    protected function generateRandomKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}
