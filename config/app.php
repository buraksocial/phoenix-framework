<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Uygulama Adı (Application Name)
    |--------------------------------------------------------------------------
    |
    | Bu değer uygulamanızın adıdır. Bildirimlerde, e-postalarda ve
    | arayüzün diğer kısımlarında kullanılır.
    |
    */
    'name' => env('APP_NAME', 'Phoenix'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama Ortamı (Application Environment)
    |--------------------------------------------------------------------------
    |
    | Geçerli değerler: 'development', 'production', 'testing'
    | Bu değer, hata gösterimi, debug araçları gibi birçok servisin
    | çalışma şeklini belirler. 'production' modunda güvenlik ön plandadır.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama URL'i (Application URL)
    |--------------------------------------------------------------------------
    |
    | Bu URL, CLI üzerinden çalıştırılan komutlarda (örn: e-posta linkleri)
    | veya asset'lerin yollarını oluştururken doğru adresi bulmak için kullanılır.
    | Sonunda slash (/) olmamalıdır.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Uygulama Zaman Dilimi (Application Timezone)
    |--------------------------------------------------------------------------
    |
    | PHP'nin tarih ve saat fonksiyonları için varsayılan zaman dilimi.
    |
    */
    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Uygulama Dili (Application Locale)
    |--------------------------------------------------------------------------
    |
    | Translator servisi tarafından varsayılan olarak kullanılacak dil.
    |
    */
    'locale' => 'tr',

    /*
    |--------------------------------------------------------------------------
    | Uygulama Güvenlik Anahtarı (Encryption Key)
    |--------------------------------------------------------------------------
    |
    | Bu 32 byte'lık rastgele dize, oturum (session) verileri ve diğer
    | şifrelenmiş değerler için kullanılır. İlk kurulumda mutlaka
    | `php artisan key:generate` komutu ile oluşturulmalıdır.
    |
    */
    'key' => env('APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Otomatik Yüklenen Servis Sağlayıcılar (Autoloaded Service Providers)
    |--------------------------------------------------------------------------
    |
    | Bu diziye eklenen servis sağlayıcıları, uygulama başlatılırken
    | DI konteynerine otomatik olarak kaydedilir.
    |
    */
    'providers' => [
        // App\Providers\EventServiceProvider::class,
        // App\Providers\RouteServiceProvider::class,
    ],

];
