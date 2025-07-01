<?php

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use Core\Config;
use Core\ViewManager;
use Core\Database\Connection as DBConnection;
use Core\Session\SessionManager;
use Core\ModuleLoader;
use App\Services\DebugService;

/**
 * --------------------------------------------------------------------------
 * Projenin Ana Dizini Tanımla
 * --------------------------------------------------------------------------
 *
 * Diğer tüm dosya yollarının göreceli olarak hesaplanabilmesi için
 * projenin kök dizinini bir sabite atıyoruz.
 */
define('BASE_PATH', dirname(__DIR__));


/**
 * --------------------------------------------------------------------------
 * Composer Autoloader'ı Yükle
 * --------------------------------------------------------------------------
 *
 * Tüm PHP sınıflarımızın ve harici kütüphanelerimizin otomatik olarak
 * yüklenebilmesi için Composer'ın oluşturduğu autoloader'ı dahil ediyoruz.
 */
require_once BASE_PATH . '/vendor/autoload.php';


/**
 * --------------------------------------------------------------------------
 * Ortam Değişkenlerini Yükle
 * --------------------------------------------------------------------------
 *
 * Projenin kök dizinindeki .env dosyasını okuyarak, veritabanı şifreleri
 * gibi hassas bilgileri $_ENV süper global dizisine yükler.
 */
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();


/**
 * --------------------------------------------------------------------------
 * Hata Gösterimini Yapılandır
 * --------------------------------------------------------------------------
 *
 * Geliştirme ortamında, hataların daha okunaklı ve detaylı gösterilmesi
 * için Whoops kütüphanesini devreye alıyoruz. Production'da ise hatalar
 * loglanır ve kullanıcıya genel bir hata mesajı gösterilir.
 */
if (env('APP_ENV') === 'development') {
    $whoops = new Whoops;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}


/**
 * --------------------------------------------------------------------------
 * Dependency Injection Konteynerini Oluştur
 * --------------------------------------------------------------------------
 *
 * Uygulamamızın temelini oluşturan DI Konteynerini burada oluşturuyoruz.
 * Bu konteyner, sınıfların ve bağımlılıklarının yönetiminden sorumludur.
 */
$containerBuilder = new ContainerBuilder();

// Performans için production'da derlenmiş bir konteyner kullan
if (env('APP_ENV') === 'production') {
    $containerBuilder->enableCompilation(BASE_PATH . '/storage/cache/container');
}

// --- Konteyner Bağlamaları (Bindings) ---
// Konteynere, bir sınıf istendiğinde nasıl oluşturulacağını öğretiyoruz.
$containerBuilder->addDefinitions([
    
    // Çekirdek Servisler (Singleton - Her istekte tek örnek)
    Config::class => \DI\create(Config::class)->constructor(BASE_PATH . '/config'),
    
    ViewManager::class => \DI\create(ViewManager::class)->constructor(BASE_PATH . '/views'),
    
    DBConnection::class => function (Config $config) {
        $connection = new DBConnection($config);
        $connection->boot();
        return $connection;
    },

    ModuleLoader::class => \DI\autowire(ModuleLoader::class),
    
    // Debug servisini sadece geliştirme ortamında bağla
    DebugService::class => function(ContainerInterface $c) {
        if (config('app.env') === 'development') {
            return new DebugService($c->get(\DebugBar\StandardDebugBar::class));
        }
        // Production'da boş bir obje veya null döndürerek hatayı engelle
        return new class { public function boot() {} public function render() { return ''; } };
    },
    \DebugBar\StandardDebugBar::class => \DI\create(\DebugBar\StandardDebugBar::class),
    
    // Diğer servisler
    \App\Services\UserService::class => \DI\autowire(\App\Services\UserService::class),
    \App\Services\ImageProcessorService::class => \DI\autowire(\App\Services\ImageProcessorService::class),

    // Artisan Komutları
    \App\Commands\KeyGenerateCommand::class => \DI\autowire(\App\Commands\KeyGenerateCommand::class),
    \App\Commands\MakeModuleCommand::class => \DI\autowire(\App\Commands\MakeModuleCommand::class),
    // ... diğer komutlar
]);


$container = $containerBuilder->build();


/**
 * --------------------------------------------------------------------------
 * Çekirdek Servisleri Başlat (Boot)
 * --------------------------------------------------------------------------
 *
 * Konteyner oluşturulduktan sonra, her istekte çalışması gereken
 * temel servisleri burada başlatıyoruz.
 */

// Oturum Yöneticisini Başlat
SessionManager::boot($container);

// Modül Yöneticisini Başlat ve modülleri yükle
$moduleLoader = $container->get(ModuleLoader::class);
$moduleLoader->boot();

// Debug Bar'ı başlat (sadece dev'de)
$container->get(DebugService::class)->boot();


/**
 * --------------------------------------------------------------------------
 * Konteyneri Geri Döndür
 * --------------------------------------------------------------------------
 *
 * Tamamen yapılandırılmış ve başlatılmış DI konteynerini,
 * onu isteyen (örn: public/index.php) dosyaya geri döndürüyoruz.
 */
return $container;
