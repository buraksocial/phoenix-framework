<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteCacheCommand extends Command
{
    protected static $defaultName = 'route:cache';

    protected function configure()
    {
        $this->setDescription('Uygulama rotalarını önbelleğe alır.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cachedRoutesPath = BASE_PATH . '/bootstrap/cache/routes.php';

        // Önbellek dizinini oluştur
        if (!is_dir(dirname($cachedRoutesPath))) {
            mkdir(dirname($cachedRoutesPath), 0755, true);
        }

        // Bramus Router'ı başlat
        $router = new \Bramus\Router\Router();

        // Modül rotalarını yükle
        $moduleLoader = app(\Core\ModuleLoader::class);
        $moduleLoader->registerRoutes($router);

        // Ana uygulama rotalarını yükle
        $web_routes_path = BASE_PATH . '/routes/web.php';
        if (file_exists($web_routes_path)) {
            require $web_routes_path;
        }

        $api_routes_path = BASE_PATH . '/routes/api.php';
        if (file_exists($api_routes_path)) {
            require $api_routes_path;
        }

        // Rota koleksiyonunu al
        $routes = $router->getRoutes();

        // PHP kodu olarak önbelleğe kaydet
        file_put_contents(
            $cachedRoutesPath,
            '<?php return ' . var_export($routes, true) . ';'
        );

        $output->writeln('<info>Rotalar başarıyla önbelleğe alındı.</info>');

        return Command::SUCCESS;
    }
}
