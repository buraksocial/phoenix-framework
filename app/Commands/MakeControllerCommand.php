<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';

    protected function configure()
    {
        $this->setDescription('Yeni bir controller sınıfı oluşturur.')
             ->addArgument('name', InputArgument::REQUIRED, 'Controller adı (örn: UserController).')
             ->addOption('module', null, InputOption::VALUE_OPTIONAL, 'Controller'ın ait olacağı modül (örn: Blog).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $module = $input->getOption('module');

        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $namespace = 'App\\Controllers';
        $path = BASE_PATH . '/app/Controllers/';

        if ($module) {
            $module = ucfirst($module);
            $namespace = 'Modules\\' . $module . '\\Controllers';
            $path = BASE_PATH . '/modules/' . $module . '/Controllers/';
            if (!is_dir($path)) {
                $output->writeln('<error>Belirtilen modül dizini bulunamadı: ' . $path . '</error>');
                return Command::FAILURE;
            }
        }

        $filePath = $path . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln('<error>Controller zaten mevcut: ' . $filePath . '</error>');
            return Command::FAILURE;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/controller.stub');
        $content = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $name], $stub);

        file_put_contents($filePath, $content);

        $output->writeln('<info>Controller başarıyla oluşturuldu: ' . $filePath . '</info>');

        return Command::SUCCESS;
    }
}
