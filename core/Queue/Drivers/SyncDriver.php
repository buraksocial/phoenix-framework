<?php
namespace Core\Queue\Drivers;

use Core\Queue\Job;
use Core\Queue\QueueInterface;
use DI\Container;

/**
 * SyncDriver Sınıfı
 *
 * İşleri kuyruğa eklemek yerine anında işleyen bir kuyruk sürücüsüdür.
 * Genellikle geliştirme ortamında veya basit, anında yürütülmesi gereken
 * görevler için kullanılır.
 */
class SyncDriver implements QueueInterface
{
    /**
     * @var Container DI Konteyneri.
     */
    protected $container;

    public function __construct(array $config, Container $container)
    {
        $this->container = $container;
    }

    /**
     * Bir işi kuyruğa ekler (anında işler).
     */
    public function push(Job $job, string $queue = null): bool
    {
        try {
            // İşin bağımlılıklarını DI konteyneri ile çözerek handle metodunu çağır
            $this->container->call([$job, 'handle']);
            return true;
        } catch (\Throwable $e) {
            error_log("Sync kuyruk işi başarısız oldu: " . get_class($job) . " - " . $e->getMessage());
            // Sync sürücüsünde yeniden deneme veya bırakma mekanizması yoktur,
            // çünkü iş anında işlenir. Hata durumunda sadece loglanır.
            return false;
        }
    }

    /**
     * Sync sürücüsünde işler anında işlendiği için pop metodu her zaman null döndürür.
     */
    public function pop(string $queue = null): ?Job
    {
        return null;
    }

    /**
     * Sync sürücüsünde işler anında işlendiği için delete metodu her zaman true döndürür.
     */
    public function delete(Job $job, string $queue = null): bool
    {
        return true;
    }

    /**
     * Sync sürücüsünde işler anında işlendiği için release metodu her zaman true döndürür.
     */
    public function release(Job $job, string $queue = null): bool
    {
        return true;
    }

    /**
     * Sync sürücüsünde kuyruk kavramı olmadığı için clear metodu her zaman true döndürür.
     */
    public function clear(string $queue = null): bool
    {
        return true;
    }

    /**
     * Sync sürücüsünde kuyruk kavramı olmadığı için size metodu her zaman 0 döndürür.
     */
    public function size(string $queue = null): int
    {
        return 0;
    }
}
