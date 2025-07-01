<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModuleCommand extends Command
{
    protected static $defaultName = 'make:module';

    protected function configure()
    {
        $this->setDescription('Yeni bir modül oluşturur.')
             ->addArgument('name', InputArgument::REQUIRED, 'Modülün adı (örn: Blog, User).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = ucfirst($input->getArgument('name'));
        $modulePath = BASE_PATH . '/modules/' . $moduleName;

        if (is_dir($modulePath)) {
            $output->writeln('<error>Modül zaten mevcut: ' . $moduleName . '</error>');
            return Command::FAILURE;
        }

        // Modül dizin yapısını oluştur
        mkdir($modulePath, 0755, true);
        mkdir($modulePath . '/Controllers', 0755, true);
        mkdir($modulePath . '/Services', 0755, true);
        mkdir($modulePath . '/Views', 0755, true);

        // config.php dosyası
        $configContent = "<?php\n\nreturn [\n    'name' => '{$moduleName}',\n    'description' => '{$moduleName} modülü',\n    // Diğer modül ayarları\n];\n";
        file_put_contents($modulePath . '/config.php', $configContent);

        // routes.php dosyası
        $routesContent = "<?php\n\nuse Bramus\\Router\\Router;\nuse Modules\\{$moduleName}\\Controllers\\{$moduleName}Controller;\n\n/** @var Router $router */\n\n$router->group('/{$moduleName}', function () use ($router) {\n    $router->get('/', [{$moduleName}Controller::class, 'index']);\n});\n";
        file_put_contents($modulePath . '/routes.php', $routesContent);

        // ServiceProvider.php dosyası
        $serviceProviderContent = "<?php\nnamespace Modules\\{$moduleName};\n\nuse DI\\Container;\n\nclass ServiceProvider\n{\n    protected $container;\n\n    public function __construct(Container $container)\n    {\n        $this->container = $container;\n    }\n\n    public function register()\n    {\n        // Modüle özel servisleri burada kaydedin\n        // $this->container->set(SomeService::class, \DI\autowire(SomeService::class));\n    }\n}\n";
        file_put_contents($modulePath . '/ServiceProvider.php', $serviceProviderContent);

        // Varsayılan Controller dosyası
        $controllerContent = "<?php\nnamespace Modules\\{$moduleName}\\Controllers;\n\nuse Core\\Controller;\n\nclass {$moduleName}Controller extends Controller\n{\n    public function index()\n    {\n        return view('{$moduleName}::index', ['moduleName' => '{$moduleName}']);\n    }\n}\n";
        file_put_contents($modulePath . '/Controllers/' . $moduleName . 'Controller.php', $controllerContent);

        // Varsayılan View dosyası
        $viewContent = "<h1>Hoş Geldiniz {$moduleName} Modülü!</h1>\n<p>Bu, {$moduleName} modülünün varsayılan görünümüdür.</p>\n";
        file_put_contents($modulePath . '/Views/index.php', $viewContent);

        $output->writeln('<info>Modül başarıyla oluşturuldu: ' . $moduleName . '</info>');

        return Command::SUCCESS;
    }
}
