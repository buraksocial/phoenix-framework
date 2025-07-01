#!/usr/bin/env php
<?php

// Bu script, cron job veya supervisor gibi bir süreç yöneticisi ile
// sürekli çalıştırılarak kuyruktaki (queue) işleri sırayla işler.

$container = require_once __DIR__ . '/bootstrap/app.php';

// Kuyruk yöneticisini konteynerden al
$queue = $container->get(Core\Queue\QueueManager::class);
$connectionName = $argv[1] ?? config('queue.default');

echo "========================================\n";
echo "  Phoenix Framework Kuyruk İşçisi\n";
echo "========================================\n";
echo "Tarih: " . date('Y-m-d H:i:s') . "\n";
echo "Bağlantı: " . $connectionName . "\n";
echo "İşler dinleniyor. Çıkmak için CTRL+C.\n\n";


// Bu basit bir "daemon" döngüsüdür.
// Production ortamında supervisor gibi bir araçla yönetilmesi,
// çökme durumunda otomatik yeniden başlatma için şiddetle tavsiye edilir.
while (true) {
    try {
        // Belirtilen veya varsayılan kuyruktan bir işi işlemeye çalış
        $processed = $queue->work($connectionName);

        // Eğer hiç işlenmemiş iş yoksa, tekrar denemeden önce bekle.
        // Bu, boş döngüde CPU kullanımını engeller.
        if ($processed === 0) {
            sleep(5); // 5 saniye bekle
        }

    } catch (Throwable $e) {
        // Bir iş sırasında kritik bir hata olursa logla ve devam et.
        // Bu, işçinin tek bir hatalı iş yüzünden çökmesini engeller.
        error_log("Kuyruk Hatası: " . $e->getMessage() . " Dosya: " . $e->getFile() . " Satır: " . $e->getLine());
        // Hatalı denemeler arasında daha uzun bekle
        sleep(10);
    }
}
