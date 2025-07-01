<?php
namespace Core\Cache\Drivers;

use Core\Cache\DriverInterface;
use Core\Database\Connection as DBConnection;

class RedisDriver implements DriverInterface
{
    /**
     * Redis bağlantısı.
     * @var \Redis
     */
    protected $redis;

    /**
     * Önbellek anahtarları için önek.
     * @var string
     */
    protected $prefix;

    public function __construct(array $config)
    {
        // Redis bağlantısını DI konteynerinden al
        $dbConnection = app(DBConnection::class);
        $this->redis = $dbConnection->redis($config['connection'] ?? 'default');
        $this->prefix = config('cache.prefix', 'phoenix_cache') . ':';
    }

    /**
     * Önbellekten belirtilen anahtara ait değeri getirir.
     */
    public function get(string $key, $default = null)
    {
        $value = $this->redis->get($this->prefix . $key);
        if ($value === false) {
            return $default;
        }
        return unserialize($value);
    }

    /**
     * Bir veriyi belirtilen süreyle (dakika cinsinden) önbelleğe kaydeder.
     */
    public function set(string $key, $value, int $minutes): bool
    {
        $serializedValue = serialize($value);
        return $this->redis->setex($this->prefix . $key, $minutes * 60, $serializedValue);
    }

    /**
     * Belirtilen anahtarın önbellekte olup olmadığını kontrol eder.
     */
    public function has(string $key): bool
    {
        return $this->redis->exists($this->prefix . $key);
    }

    /**
     * Belirtilen anahtara ait veriyi önbellekten siler.
     */
    public function forget(string $key): bool
    {
        return (bool) $this->redis->del($this->prefix . $key);
    }

    /**
     * Tüm önbelleği temizler.
     */
    public function flush(): bool
    {
        // Sadece bu uygulamanın önbellek anahtarlarını temizlemek için dikkatli ol
        // FLUSHDB tüm veritabanını temizler, bu yüzden prefix ile filtreleme yapmalıyız.
        $keys = $this->redis->keys($this->prefix . '*');
        if (!empty($keys)) {
            return (bool) $this->redis->del($keys);
        }
        return true;
    }
}
