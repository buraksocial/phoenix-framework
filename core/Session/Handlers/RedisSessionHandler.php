<?php
namespace Core\Session\Handlers;

use SessionHandlerInterface;
use Core\Config;
use Core\Database\Connection as DBConnection;

/**
 * RedisSessionHandler Sınıfı
 *
 * PHP oturum verilerini Redis'te saklamak için kullanılan
 * bir oturum işleyicisidir. SessionHandlerInterface'i uygular.
 */
class RedisSessionHandler implements SessionHandlerInterface
{
    /**
     * Redis bağlantısı.
     * @var \Redis
     */
    protected $redis;

    /**
     * Oturum ömrü (saniye cinsinden).
     * @var int
     */
    protected $lifetime;

    /**
     * Oturum anahtarları için önek.
     * @var string
     */
    protected $prefix;

    public function __construct(Config $config, DBConnection $dbConnection)
    {
        $this->lifetime = $config->get('session.lifetime', 120) * 60;
        $this->prefix = $config->get('session.cookie', 'phoenix_session') . ':';
        
        // Redis bağlantısını veritabanı bağlantı yöneticisinden al
        $this->redis = $dbConnection->redis($config->get('session.redis_connection', 'default'));
    }

    /**
     * Oturum açma işlemi.
     *
     * @param string $path Oturum dosyalarının yolu (Redis için kullanılmaz).
     * @param string $name Oturum adı.
     * @return bool
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }

    /**
     * Oturum verilerini kapatma işlemi.
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Oturum verilerini okuma işlemi.
     *
     * @param string $id Oturum ID'si.
     * @return string
     */
    public function read(string $id): string
    {
        return (string) $this->redis->get($this->prefix . $id);
    }

    /**
     * Oturum verilerini yazma işlemi.
     *
     * @param string $id Oturum ID'si.
     * @param string $data Oturum verileri.
     * @return bool
     */
    public function write(string $id, string $data): bool
    {
        return $this->redis->setex($this->prefix . $id, $this->lifetime, $data);
    }

    /**
     * Oturum verilerini silme işlemi.
     *
     * @param string $id Oturum ID'si.
     * @return bool
     */
    public function destroy(string $id): bool
    {
        return (bool) $this->redis->del($this->prefix . $id);
    }

    /**
     * Çöp toplama işlemi (Redis için genellikle gerekmez, Redis TTL ile yönetir).
     *
     * @param int $max_lifetime Oturumun maksimum ömrü (saniye cinsinden).
     * @return int|false
     */
    public function gc(int $max_lifetime): int|false
    {
        // Redis otomatik olarak TTL (Time To Live) ile süresi dolan anahtarları temizler.
        // Bu metodun Redis için özel bir işlevi yoktur.
        return 0;
    }
}
