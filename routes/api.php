<?php

/**
 * ====================================================================
 * API ROTALARI
 * ====================================================================
 *
 * Bu dosya, uygulamanızın dış dünyaya açılan API'si için tüm
 * endpoint'leri içerir. Genellikle, bu rotalar bir "prefix" (ön ek)
 * ile gruplanır (örn: /api/v1) ve JSON formatında yanıt dönerler.
 *
 * Buradaki `$router` değişkeni, `public/index.php` dosyasında
 * oluşturulan ve bu dosyanın içine dahil edilen ana router nesnesidir.
 */

use App\Controllers\ApiController;
use Core\Auth;
use DI\Container;

// DI Konteynerine erişim
$container = app();

// Tüm API rotalarını `/api/v1` öneki ile grupla
$router->mount('/api/v1', function() use ($router, $container) {

    // Herkese açık endpoint'ler
    $router->get('/health', function() use ($container) {
        $container->call([ApiController::class, 'healthCheck']);
    });
    
    /*
    // Örnek: Yeni kullanıcı kaydı
    $router->post('/register', function() use ($container) {
        // FormRequest'i manuel olarak çözmek gerekir, çünkü router'ın DI entegrasyonu basit.
        // Daha gelişmiş bir router entegrasyonu bunu otomatik yapabilir.
        $request = $container->get(\App\Requests\StoreUserRequest::class);
        $container->call([ApiController::class, 'storeUser'], ['request' => $request]);
    });
    */


    // Sadece kimlik doğrulaması yapmış kullanıcıların erişebileceği rota grubu.
    $router->before('GET|POST|PUT|DELETE', '/auth/.*', function() {
        /**
         * NOT: Gerçek bir API için burada Session kontrolü yerine
         * gelen request'teki `Authorization: Bearer <token>` başlığını
         * kontrol eden bir JWT veya API Token doğrulama mekanizması olmalıdır.
         * Bu örnekte, web arayüzü ile aynı oturumu paylaştığı varsayılmıştır.
         */
        if (!Auth::check()) {
            json_error('Yetkisiz erişim.', [], 401);
        }
    });

    // Örnek: Oturum açmış kullanıcının bilgilerini döndürür
    // URL: /api/v1/auth/me
    $router->get('/auth/me', function() use ($container) {
        $user = Auth::user();
        $resource = \App\Resources\UserResource::make($user);
        json_success('Kullanıcı bilgileri getirildi.', $resource->toArray());
    });
    
    // Örnek: Admin'e özel bir endpoint
    $router->get('/auth/admin/stats', function() {
        if (!Auth::hasRole('admin')) {
            json_error('Bu işlemi yapmak için yetkiniz yok.', [], 403);
        }
        json_success('Admin istatistikleri.', ['users' => 150, 'posts' => 789]);
    });

});