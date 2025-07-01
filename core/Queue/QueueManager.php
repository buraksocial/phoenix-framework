<?php
namespace Core\Queue;

use Core\Config;
use DI\Container;

/**
 * QueueManager Sınıfı
 *
 * Uygulamanın kuyruk sistemini yönetir. Farklı kuyruk sürücüleri (sync, file, redis)
 * arasında geçiş yapmayı sağlar ve işlerin kuyruğa eklenmesi, işlenmesi gibi
 * temel operasyonları koordine eder.
 */
class QueueManager
{
    /**
     * @var Config Uygulama yapılandırması.
     */
    protected $config;

    /**
     * @var Container DI Konteyneri.
     */
    protected $container;

    /**
     * Oluşturulmuş sürücü örneklerini tutar (singleton).
     * @var array<string, QueueInterface>
     */
    protected $drivers = [];

    public function __construct(Config $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * Belirtilen veya varsayılan kuyruk sürücüsünü döndürür.
     *
     * @param string|null $name Sürücü adı (örn: 'sync', 'file', 'redis').
     * @return QueueInterface
     * @throws \Exception
     */
    public function connection(string $name = null): QueueInterface
    {
        $name = $name ?? $this->config->get('queue.default');

        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->resolveDriver($name);
        }

        return $this->drivers[$name];
    }

    /**
     * Yapılandırmaya göre istenen sürücü sınıfını oluşturur.
     *
     * @param string $name Sürücü adı.
     * @return QueueInterface
     * @throws \Exception
     */
    protected function resolveDriver(string $name): QueueInterface
    {
        $config = $this->config->get("queue.connections.{$name}");

        if (is_null($config)) {
            throw new \Exception("Kuyruk bağlantısı [{$name}] yapılandırılmamış.");
        }

        $driverClass = 'Core\\Queue\\Drivers\\' . ucfirst($config['driver']) . 'Driver';

        if (!class_exists($driverClass)) {
            throw new \Exception("Kuyruk sürücüsü bulunamadı: {$driverClass}");
        }

        // Sürücüye config ve container'ı geçir
        return new $driverClass($config, $this->container);
    }

    /**
     * Bir işi kuyruğa ekler.
     *
     * @param Job $job Kuyruğa eklenecek iş nesnesi.
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @param string|null $connection Bağlantı adı (isteğe bağlı).
     * @return bool
     */
    public function push(Job $job, string $queue = null, string $connection = null): bool
    {
        return $this->connection($connection)->push($job, $queue);
    }

    /**
     * Kuyruktan bir işi çeker ve işler.
     *
     * @param string|null $connection Bağlantı adı (isteğe bağlı).
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return int İşlenen iş sayısı (0 veya 1).
     */
    public function work(string $connection = null, string $queue = null): int
    {
        $driver = $this->connection($connection);
        $job = $driver->pop($queue);

        if (!$job) {
            return 0; // İş yok
        }

        try {
            // İşin bağımlılıklarını DI konteyneri ile çözerek handle metodunu çağır
            $this->container->call([$job, 'handle']);
            $driver->delete($job, $queue); // Başarılı ise sil
            return 1;
        } catch (\Throwable $e) {
            error_log("Kuyruk işi başarısız oldu: " . get_class($job) . " - " . $e->getMessage());
            $job->incrementAttempts();

            if ($job->hasExceededMaxAttempts()) {
                error_log("Kuyruk işi maksimum deneme sayısını aştı ve silindi: " . get_class($job));
                $driver->delete($job, $queue); // Maksimum deneme aşıldı, sil
            } else {
                $driver->release($job, $queue); // Yeniden denemek için geri bırak
            }
            return 0;
        }
    }

    /**
     * Varsayılan sürücü üzerinde dinamik olarak metot çağırmayı sağlar.
     * Örnek: Queue::push($job)
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}