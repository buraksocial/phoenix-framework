<?php

// Blog modülüne özel ayar dosyası.
// Buradaki ayarlara `config('Blog.main.posts_per_page')` gibi erişilebilir.
// (Bunun için Config sınıfının namespace'leri desteklemesi gerekir.)

return [

    /*
    |--------------------------------------------------------------------------
    | Gönderi Ayarları
    |--------------------------------------------------------------------------
    */

    // Blog ana sayfasında sayfa başına gösterilecek gönderi sayısı.
    'posts_per_page' => 10,

    // Yeni gönderilerde yorumlar varsayılan olarak açık mı?
    'comments_enabled_by_default' => true,


    /*
    |--------------------------------------------------------------------------
    | Modül Durumu
    |--------------------------------------------------------------------------
    */

    // Modülü tamamen devre dışı bırakmak için kullanılabilir.
    'enabled' => true,
];