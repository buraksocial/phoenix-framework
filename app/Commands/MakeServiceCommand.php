<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeServiceCommand extends Command
{
    protected static $defaultName = 'make:service';

    protected function configure()
    {
        $this->setDescription('Yeni bir servis sınıfı oluşturur.')
             ->addArgument('name', InputArgument::REQUIRED, 'Servis adı (örn: UserService).')
             ->addOption('module', null, InputOption::VALUE_OPTIONAL, 'Servisin ait olacağı modül (örn: Blog).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $module = $input->getOption('module');

        if (!str_ends_with($name, 'Service')) {
            $name .= 'Service';
        }

        $namespace = 'App\\Services';
        $path = BASE_PATH . '/app/Services/';

        if ($module) {
            $module = ucfirst($module);
            $namespace = 'Modules\\' . $module . '\\Services';
            $path = BASE_PATH . '/modules/' . $module . '/Services/';
            if (!is_dir($path)) {
                $output->writeln('<error>Belirtilen modül dizini bulunamadı: ' . $path . '</error>');
                return Command::FAILURE;
            }
        }

        $filePath = $path . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln('<error>Servis zaten mevcut: ' . $filePath . '</error>');
            return Command::FAILURE;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/service.stub');
        $content = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $name], $stub);

        file_put_contents($filePath, $content);

        $output->writeln('<info>Servis başarıyla oluşturuldu: ' . $filePath . '</info>');

        return Command::SUCCESS;
    }
}
