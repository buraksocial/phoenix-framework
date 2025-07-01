<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Önbellek Deposu (Default Cache Store)
    |--------------------------------------------------------------------------
    |
    | Kod içinde özel olarak belirtilmediği sürece kullanılacak olan
    | varsayılan önbellek yapılandırmasıdır.
    | Desteklenenler: "file", "redis"
    |
    */
    'default' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Önbellek Depoları (Cache Stores)
    |--------------------------------------------------------------------------
    |
    | Uygulamanızda kullanabileceğiniz tüm önbellek sürücüleri ve
    | onların yapılandırmaları burada tanımlanır.
    |
    */
    'stores' => [

        'file' => [
            'driver' => 'file',
            // Önbellek dosyalarının saklanacağı dizin.
            // Bu dizinin sunucu tarafından yazılabilir olması gerekir.
            'path' => BASE_PATH . '/storage/cache/data',
        ],

        'redis' => [
            'driver' => 'redis',
            // `config/database.php` içinde tanımlanan hangi redis
            // bağlantısının kullanılacağını belirtir.
            'connection' => 'cache',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Önbellek Anahtar Öneki (Cache Key Prefix)
    |--------------------------------------------------------------------------
    |
    | Aynı sunucuda bu framework'ün birden fazla kopyası çalıştırıldığında,
    | önbellek anahtarlarının birbiriyle çakışmasını önlemek için kullanılır.
    | Her uygulama için benzersiz bir önek belirlemek iyi bir pratiktir.
    |
    */
    'prefix' => env('CACHE_PREFIX', 'phoenix_cache'),

];
