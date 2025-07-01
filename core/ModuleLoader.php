<?php
namespace Core;

use DI\Container;
use Bramus\Router\Router;

/**
 * ModuleLoader Sınıfı
 *
 * Uygulama içindeki modülleri (Modules/) otomatik olarak keşfeder,
 * yapılandırmalarını yükler, servislerini kaydeder ve rotalarını tanımlar.
 * Bu, uygulamanın modüler ve genişletilebilir olmasını sağlar.
 */
class ModuleLoader
{
    /**
     * @var Container DI Konteyneri.
     */
    protected $container;

    /**
     * @var string Modüllerin bulunduğu ana dizin.
     */
    protected $modulesPath;

    /**
     * Yüklenmiş modüllerin listesi.
     * @var array<string, array>
     */
    protected $loadedModules = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->modulesPath = BASE_PATH . '/modules';
    }

    /**
     * Modülleri keşfeder ve başlatır.
     * Bu metod, bootstrap/app.php içinde çağrılır.
     */
    public function boot(): void
    {
        if (!is_dir($this->modulesPath)) {
            return;
        }

        $moduleDirs = array_filter(glob($this->modulesPath . '/*'), 'is_dir');

        foreach ($moduleDirs as $modulePath) {
            $moduleName = basename($modulePath);
            $this->loadModule($moduleName, $modulePath);
        }
    }

    /**
     * Belirtilen modülü yükler.
     *
     * @param string $moduleName Modül adı.
     * @param string $modulePath Modülün tam yolu.
     */
    protected function loadModule(string $moduleName, string $modulePath): void
    {
        $configPath = $modulePath . '/config.php';
        $serviceProviderPath = $modulePath . '/ServiceProvider.php';

        $config = [];
        if (file_exists($configPath)) {
            $config = require $configPath;
        }

        $this->loadedModules[$moduleName] = [
            'path' => $modulePath,
            'config' => $config,
        ];

        // Servis Sağlayıcıyı kaydet
        if (file_exists($serviceProviderPath)) {
            $serviceProviderClass = 'Modules\\' . $moduleName . '\\ServiceProvider';
            if (class_exists($serviceProviderClass)) {
                $provider = $this->container->get($serviceProviderClass);
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }
            }
        }
    }

    /**
     * Tüm modüllerin rotalarını kaydeder.
     *
     * @param Router $router Bramus Router örneği.
     */
    public function registerRoutes(Router $router): void
    {
        foreach ($this->loadedModules as $moduleName => $moduleData) {
            $routesPath = $moduleData['path'] . '/routes.php';
            if (file_exists($routesPath)) {
                // Modül rotalarını yüklerken, router nesnesini de kullanılabilir hale getir.
                require $routesPath;
            }
        }
    }

    /**
     * Yüklenmiş bir modülün yapılandırma verisini döndürür.
     *
     * @param string $moduleName Modül adı.
     * @param string|null $key Yapılandırma anahtarı (isteğe bağlı).
     * @param mixed|null $default Varsayılan değer.
     * @return mixed
     */
    public function getModuleConfig(string $moduleName, string $key = null, $default = null)
    {
        if (!isset($this->loadedModules[$moduleName])) {
            return $default;
        }

        $config = $this->loadedModules[$moduleName]['config'];

        if ($key === null) {
            return $config;
        }

        $parts = explode('.', $key);
        $current = $config;

        foreach ($parts as $part) {
            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } else {
                return $default;
            }
        }

        return $current;
    }
}