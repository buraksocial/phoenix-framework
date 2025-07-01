<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    protected static $defaultName = 'make:model';

    protected function configure()
    {
        $this->setDescription('Yeni bir model sınıfı oluşturur.')
             ->addArgument('name', InputArgument::REQUIRED, 'Model adı (örn: User).')
             ->addOption('module', null, InputOption::VALUE_OPTIONAL, 'Modelin ait olacağı modül (örn: Blog).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $module = $input->getOption('module');

        if (!str_ends_with($name, 'Model')) {
            $name .= 'Model';
        }

        $namespace = 'App\\Models';
        $path = BASE_PATH . '/app/Models/';

        if ($module) {
            $module = ucfirst($module);
            $namespace = 'Modules\\' . $module . '\\Models';
            $path = BASE_PATH . '/modules/' . $module . '/Models/';
            if (!is_dir($path)) {
                $output->writeln('<error>Belirtilen modül dizini bulunamadı: ' . $path . '</error>');
                return Command::FAILURE;
            }
        }

        $filePath = $path . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln('<error>Model zaten mevcut: ' . $filePath . '</error>');
            return Command::FAILURE;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/model.stub');
        $content = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $name], $stub);

        file_put_contents($filePath, $content);

        $output->writeln('<info>Model başarıyla oluşturuldu: ' . $filePath . '</info>');

        return Command::SUCCESS;
    }
}
