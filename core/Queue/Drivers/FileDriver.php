<?php
namespace Core\Queue\Drivers;

use Core\Queue\Job;
use Core\Queue\QueueInterface;
use DI\Container;

/**
 * FileDriver Sınıfı
 *
 * Kuyruk işlerini dosya sisteminde saklayarak işleyen bir kuyruk sürücüsüdür.
 * Basit uygulamalar veya geliştirme ortamları için uygundur.
 */
class FileDriver implements QueueInterface
{
    /**
     * @var string İş dosyalarının saklanacağı dizin.
     */
    protected $path;

    /**
     * @var Container DI Konteyneri.
     */
    protected $container;

    public function __construct(array $config, Container $container)
    {
        $this->path = $config['path'] ?? BASE_PATH . '/storage/jobs';
        $this->container = $container;

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Bir işi kuyruğa ekler.
     */
    public function push(Job $job, string $queue = null): bool
    {
        $queuePath = $this->getQueuePath($queue);
        if (!is_dir($queuePath)) {
            mkdir($queuePath, 0755, true);
        }

        $jobId = uniqid('job_', true);
        $filePath = $queuePath . '/' . $jobId . '.job';

        $job->queuedAt = time(); // Kuyruğa eklenme zamanını güncelle
        $job->attempts = 0; // Deneme sayısını sıfırla

        return file_put_contents($filePath, serialize($job)) !== false;
    }

    /**
     * Kuyruktan bir işi çeker ve döndürür.
     */
    public function pop(string $queue = null): ?Job
    {
        $queuePath = $this->getQueuePath($queue);
        $files = glob($queuePath . '/*.job');

        if (empty($files)) {
            return null;
        }

        // En eski işi bul (queuedAt değerine göre sıralama yapılabilir, şimdilik ilk bulunan)
        // Daha gelişmiş bir sistemde, işlerin önceliği veya gecikmeli işler de dikkate alınabilir.
        $filePath = $files[0];
        $content = file_get_contents($filePath);
        $job = unserialize($content);

        if (!$job instanceof Job) {
            // Geçersiz iş dosyası, sil ve devam et
            unlink($filePath);
            return $this->pop($queue);
        }

        // İşlenmek üzere çekilen işi geçici olarak sil
        // Başarılı olursa tamamen silinecek, başarısız olursa yeniden eklenecek
        unlink($filePath);

        return $job;
    }

    /**
     * Kuyruktaki bir işi siler.
     */
    public function delete(Job $job, string $queue = null): bool
    {
        // FileDriver'da pop metodu işi zaten sildiği için bu metod genellikle boş kalır.
        // Ancak, işin yeniden deneme mekanizması için release metodu kullanılır.
        // Bu metod, işin tamamen bittiği anlamına gelir.
        return true;
    }

    /**
     * Başarısız olan bir işi yeniden kuyruğa ekler.
     */
    public function release(Job $job, string $queue = null): bool
    {
        $queuePath = $this->getQueuePath($queue);
        if (!is_dir($queuePath)) {
            mkdir($queuePath, 0755, true);
        }

        // Yeniden deneme için yeni bir dosya adı oluştur
        $jobId = uniqid('job_retry_', true);
        $filePath = $queuePath . '/' . $jobId . '.job';

        // İşin deneme sayısını artır ve yeniden kuyruğa ekle
        $job->incrementAttempts();
        // Yeniden deneme süresi kadar gecikme ekle
        $job->queuedAt = time() + $job->retryAfter;

        return file_put_contents($filePath, serialize($job)) !== false;
    }

    /**
     * Belirtilen kuyruktaki tüm işleri temizler.
     */
    public function clear(string $queue = null): bool
    {
        $queuePath = $this->getQueuePath($queue);
        if (!is_dir($queuePath)) {
            return true;
        }

        $files = glob($queuePath . '/*.job');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }

    /**
     * Belirtilen kuyruktaki iş sayısını döndürür.
     */
    public function size(string $queue = null): int
    {
        $queuePath = $this->getQueuePath($queue);
        if (!is_dir($queuePath)) {
            return 0;
        }
        return count(glob($queuePath . '/*.job'));
    }

    /**
     * Kuyruk dizininin tam yolunu döndürür.
     * @param string|null $queue
     * @return string
     */
    protected function getQueuePath(string $queue = null): string
    {
        return $this->path . '/' . ($queue ?? 'default');
    }
}
