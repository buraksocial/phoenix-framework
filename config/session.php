<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Oturum Sürücüsü (Default Session Driver)
    |--------------------------------------------------------------------------
    |
    | Uygulamanızda oturum verilerini saklamak için kullanılacak varsayılan
    | yöntem. 'file' sürücüsü en yaygın olanıdır. Yüksek trafikli siteler
    | için 'redis' daha iyi performans sunar.
    | Desteklenenler: "file", "redis"
    |
    */
    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Oturum Ömrü (Session Lifetime)
    |--------------------------------------------------------------------------
    |
    | Kullanıcı etkileşimde bulunmazsa, oturumun ne kadar süre (dakika
    | cinsinden) aktif kalacağını belirtir. Bu süre dolduğunda, kullanıcı
    | tekrar giriş yapmak zorunda kalacaktır.
    |
    */
    'lifetime' => env('SESSION_LIFETIME', 120),

    /*
    |--------------------------------------------------------------------------
    | Oturum Süresinin Tarayıcı Kapatılınca Dolması
    |--------------------------------------------------------------------------
    |
    | Bu ayar `true` ise, `lifetime` ayarı göz ardı edilir ve oturum,
    | tarayıcı kapatıldığında sona erer.
    |
    */
    'expire_on_close' => false,

    /*
    |--------------------------------------------------------------------------
    | Oturum Dosya Yolu (Session File Path)
    |--------------------------------------------------------------------------
    |
    | 'file' sürücüsü kullanıldığında, oluşturulacak oturum dosyalarının
    | saklanacağı dizin. Bu dizinin sunucu tarafından yazılabilir olması gerekir.
    |
    */
    'files' => BASE_PATH . '/storage/framework/sessions',

    /*
    |--------------------------------------------------------------------------
    | Oturum Çerezi (Session Cookie)
    |--------------------------------------------------------------------------
    |
    | Oturum ID'sini saklayan çerezin adı.
    |
    */
    'cookie' => 'phoenix_session',

    /*
    |--------------------------------------------------------------------------
    | Çerez Yolu (Cookie Path)
    |--------------------------------------------------------------------------
    |
    | Çerezin geçerli olacağı yol. Genellikle '/' olarak ayarlanır.
    |
    */
    'path' => '/',

    /*
    |--------------------------------------------------------------------------
    | Çerez Alan Adı (Cookie Domain)
    |--------------------------------------------------------------------------
    |
    | Uygulamanızın tüm alt alan adlarında (subdomain) oturumun geçerli
    | olmasını istiyorsanız, burayı '.your-domain.com' olarak ayarlayın.
    |
    */
    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Sadece HTTPS (Secure Cookie)
    |--------------------------------------------------------------------------
    |
    | Bu ayar `true` ise, çerez sadece HTTPS bağlantısı üzerinden
    | gönderilir. Canlı sunucularda `true` olmalıdır.
    |
    */
    'secure' => env('APP_ENV') === 'production',

    /*
    |--------------------------------------------------------------------------
    | Sadece HTTP (HTTPOnly Cookie)
    |--------------------------------------------------------------------------
    |
    | Bu ayar `true` ise, çereze JavaScript üzerinden erişim engellenir.
    | Bu, XSS saldırılarına karşı önemli bir güvenlik önlemidir.
    |
    */
    'http_only' => true,

    /*
    |--------------------------------------------------------------------------
    | SameSite Çerez Ayarı
    |--------------------------------------------------------------------------
    |
    | CSRF saldırılarına karşı koruma sağlar.
    | Değerler: 'lax', 'strict', 'none'
    |
    */
    'same_site' => 'lax',

];
