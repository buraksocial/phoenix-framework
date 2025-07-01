<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Veritabanı Bağlantısı Adı
    |--------------------------------------------------------------------------
    |
    | Uygulamanızda, özel olarak belirtilmediği sürece kullanılacak olan
    | varsayılan veritabanı bağlantısıdır. Bu isim, aşağıdaki 'connections'
    | dizisindeki anahtarlardan biri olmalıdır.
    |
    */
    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Veritabanı Bağlantıları
    |--------------------------------------------------------------------------
    |
    | Burada, uygulamanızın kullanabileceği tüm veritabanı bağlantıları
    | tanımlanır. Farklı veritabanı sistemleri (MySQL, PostgreSQL, SQLite)
    | için farklı yapılandırmalar oluşturabilirsiniz.
    |
    */
    'connections' => [

        'mysql' => [
            'driver' => 'pdo', // Sürücü: 'pdo' veya 'mysqli'
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'phoenix_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pdo', // PDO, PostgreSQL'i de destekler
            'host' => env('PG_DB_HOST', '127.0.0.1'),
            'port' => env('PG_DB_PORT', '5432'),
            'database' => env('PG_DB_DATABASE', 'phoenix_db'),
            'username' => env('PG_DB_USERNAME', 'postgres'),
            'password' => env('PG_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],
        
        'sqlite' => [
            'driver' => 'pdo',
            'database' => BASE_PATH . '/database/database.sqlite', // Dosya yolu
            'prefix' => '',
        ],

    ],
    
    /*
    |--------------------------------------------------------------------------
    | Redis Veritabanı Bağlantıları
    |--------------------------------------------------------------------------
    |
    | Redis, önbellekleme ve oturum yönetimi için kullanılsa da, temelinde
    | bir veritabanıdır. Bağlantı ayarları burada merkezileştirilmiştir.
    |
    */
    'redis' => [

        'client' => 'phpredis', // Kullanılacak PHP eklentisi

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0, // Varsayılan veritabanı
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1), // Önbellek için ayrı bir veritabanı
        ],

    ],

];
