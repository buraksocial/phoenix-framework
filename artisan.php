#!/usr/bin/env php
<?php

// Bu dosya, projenin Komut Satırı Arayüzü'nün (CLI) giriş noktasıdır.

use Symfony\Component\Console\Application;
use App\Commands\KeyGenerateCommand;
use App\Commands\MakeModuleCommand;
use App\Commands\MakeControllerCommand;
use App\Commands\MakeServiceCommand;
use App\Commands\MakeModelCommand;
use App\Commands\MigrateCommand;
use App\Commands\ConfigCacheCommand;
use App\Commands\RouteCacheCommand;
use App\Commands\CacheClearCommand;

// Composer autoloader'ını ve DI Konteynerini başlatan ana uygulama dosyasını dahil et
$container = require_once __DIR__ . '/bootstrap/app.php';

// Symfony Console kullanarak profesyonel bir CLI uygulaması oluştur
$cli = new Application('Phoenix Artisan', '1.0.0');

try {
    // Komutları DI konteynerinden çözerek ekle.
    // Bu, komutlarımızın da kendi bağımlılıklarını (servisler vb.) alabilmesini sağlar.
    $cli->add($container->get(KeyGenerateCommand::class));
    $cli->add($container->get(MakeModuleCommand::class));
    $cli->add($container->get(MakeControllerCommand::class));
    $cli->add($container->get(MakeServiceCommand::class));
    $cli->add($container->get(MakeModelCommand::class));
    
    // Uygulama yönetimi komutları
    $cli->add($container->get(MigrateCommand::class));
    $cli->add($container->get(ConfigCacheCommand::class));
    $cli->add($container->get(RouteCacheCommand::class));
    $cli->add($container->get(CacheClearCommand::class));


    // CLI uygulamasını çalıştır
    $cli->run();

} catch (Exception $e) {
    // Hata durumunda konsola daha anlaşılır bir mesaj yazdır
    // Bu, Whoops gibi HTML formatlı hata ayıklayıcıların CLI'da çalışmamasını sağlar.
    echo "\n";
    echo "--- HATA ---\n";
    echo "Mesaj: " . $e->getMessage() . "\n";
    echo "Dosya: " . $e->getFile() . "\n";
    echo "Satır: " . $e->getLine() . "\n";
    echo "------------\n";
    exit(1);
}