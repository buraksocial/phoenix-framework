<?php

/**
 * ====================================================================
 * WEB ROTALARI
 * ====================================================================
 *
 * Bu dosya, uygulamanızın tarayıcı üzerinden erişilen tüm web sayfaları
 * için rotaları içerir. Her rota, bir URI'yi bir Controller metoduna
 * veya bir Closure'a (anonim fonksiyon) eşler.
 *
 * Buradaki `$router` değişkeni, `public/index.php` dosyasında
 * oluşturulan ve bu dosyanın içine dahil edilen ana router nesnesidir.
 */

use App\Controllers\PageController;
use App\Controllers\AuthController; // Örnek Auth Controller
use Core\Auth;
use DI\Container;

// DI Konteynerine erişim
$container = app();

// Ana Sayfa
// URL: /
$router->get('/', function() use ($container) {
    $container->call([PageController::class, 'home']);
});

// Hakkımızda Sayfası
// URL: /about
$router->get('/about', function() use ($container) {
    $container->call([PageController::class, 'about']);
});

/*
// Örnek Auth Rotaları
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegisterForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);
*/


// Rota Parametreleri Örneği
$router->get('/user/(\d+)', function($id) {
    echo 'Kullanıcı ID: ' . htmlspecialchars($id);
});

// Yetkilendirme Grubu Örneği (Admin Paneli)
// Bu grubun içindeki tüm rotalara erişmeden önce belirtilen Closure çalışır.
$router->before('GET|POST', '/admin/.*', function() {
    // Eğer kullanıcı giriş yapmamışsa veya rolü 'admin' değilse, engelle.
    if (!Auth::check() || !Auth::hasRole('admin')) {
        http_response_code(403);
        // `views/errors/403.php` dosyasını varsayıyoruz
        view('errors.403');
        exit();
    }
});

$router->get('/admin/dashboard', function() {
    echo 'Admin Paneline Hoş Geldiniz!';
    // view('admin.dashboard');
});