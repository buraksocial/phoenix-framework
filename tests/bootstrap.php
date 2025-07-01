<?php

/**
 * Test Başlatıcı (Bootstrap)
 *
 * Bu dosya, PHPUnit testleri çalıştırılmadan önce bir kez çalıştırılır.
 * Görevi, testlerin çalışması için gerekli olan tüm uygulama ortamını
 * (autoloader, DI konteyneri, yardımcı fonksiyonlar vb.) yüklemektir.
 * Bu dosyanın yolu, `phpunit.xml` dosyasında belirtilmiştir.
 */

// Composer autoloader'ını ve ana uygulama başlatıcısını dahil et.
// Bu, DI Konteynerini ve tüm servisleri test ortamı için hazırlar.
require_once __DIR__ . '/../bootstrap/app.php';

// `phpunit.xml` içinde tanımlanan test ortamı değişkenlerinin
// (`APP_ENV=testing`, `DB_CONNECTION=sqlite` vb.)
// uygulama tarafından kullanıldığından emin olun.
// `bootstrap/app.php` içindeki `Dotenv::createImmutable` bu değişkenleri
// okuyup yükleyecektir.
