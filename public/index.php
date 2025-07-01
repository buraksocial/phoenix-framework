<?php

/**
 * Phoenix Framework
 *
 * Projenin tek HTTP giriş noktası (Single Point of Entry).
 * Tüm web istekleri bu dosya üzerinden yönlendirilir.
 */

// Performans ölçümü ve diğer global ihtiyaçlar için başlangıç zamanını kaydet
define('PHOENIX_START', microtime(true));

// Uygulamayı başlatan, DI konteynerini ve temel servisleri
// yapılandıran ana bootstrap dosyasını dahil et.
// Bu işlem, bize tamamen yapılandırılmış bir DI konteyneri döndürür.
$container = require_once __DIR__.'/../bootstrap/app.php';


// --- Yönlendirme (Routing) ---

// Router sınıfını DI konteynerinden al.
// `bramus/router` kullandığımız için onu örnekliyoruz.
$router = new \Bramus\Router\Router();

// Herhangi bir rota bulunamazsa çalışacak olan 404 handler'ını ayarla.
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    // Burada daha şık bir 404 view'ı çağrılabilir.
    echo '404 - Sayfa Bulunamadı';
});

// Modül Yükleyicisi'ni konteynerden al ve tüm modül rotalarını kaydet.
$moduleLoader = $container->get(Core\ModuleLoader::class);
$moduleLoader->registerRoutes($router);


// Ana uygulama rotalarını yükle. Modül rotalarından sonra yüklenirler
// böylece gerekirse bir modül rotasını ezebilirler (override).
$web_routes_path = BASE_PATH . '/routes/web.php';
if (file_exists($web_routes_path)) {
    require $web_routes_path;
}

$api_routes_path = BASE_PATH . '/routes/api.php';
if (file_exists($api_routes_path)) {
    require $api_routes_path;
}


// Router'ı çalıştırarak gelen isteği yakala ve ilgili rota ile eşleştir.
$router->run();
