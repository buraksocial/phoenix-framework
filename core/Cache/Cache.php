<?php
namespace Core\Cache;

use Core\Config;

/**
 * Cache Yöneticisi
 *
 * Farklı önbellek sürücülerini (file, redis vb.) yöneten ve uygulama
 * genelinde önbellekleme işlemleri için basit bir arayüz sağlayan sınıftır.
 */
class Cache
{
    /**
     * @var Config Uygulama yapılandırması
     */
    protected $config;

    /**
     * @var array Oluşturulmuş sürücü örneklerini tutar (singleton).
     */
    protected $stores = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Belirtilen veya varsayılan önbellek sürücüsünü döndürür.
     * @param string|null $name Sürücü adı ('file', 'redis' vb.)
     * @return DriverInterface
     * @throws \Exception
     */
    public function store(string $name = null): DriverInterface
    {
        $name = $name ?? $this->getDefaultDriver();

        if (!isset($this->stores[$name])) {
            $this->stores[$name] = $this->resolve($name);
        }

        return $this->stores[$name];
    }

    /**
     * Yapılandırmaya göre istenen sürücü sınıfını oluşturur.
     * @param string $name
     * @return DriverInterface
     * @throws \Exception
     */
    protected function resolve(string $name): DriverInterface
    {
        $config = $this->config->get("cache.stores.{$name}");

        if (is_null($config)) {
            throw new \Exception("Önbellek deposu [{$name}] yapılandırılmamış.");
        }

        $driverClass = 'Core\\Cache\\Drivers\\' . ucfirst($config['driver']) . 'Driver';

        if (!class_exists($driverClass)) {
            throw new \Exception("Önbellek sürücüsü bulunamadı: {$driverClass}");
        }

        return new $driverClass($config);
    }

    /**
     * Önbellekten bir değeri getirir. Eğer değer yoksa, Closure'ı çalıştırır,
     * sonucu belirtilen süreyle önbelleğe alır ve sonucu döndürür.
     * @param string $key Önbellek anahtarı
     * @param int $minutes Önbellekte kalma süresi (dakika)
     * @param \Closure $callback Önbellekte veri yoksa çalıştırılacak fonksiyon
     * @return mixed
     */
    public function remember(string $key, int $minutes, \Closure $callback)
    {
        $value = $this->get($key);

        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();

        $this->set($key, $value, $minutes);

        return $value;
    }

    /**
     * Varsayılan sürücü üzerinde dinamik olarak metot çağırmayı sağlar.
     * Örnek: Cache::get('anahtar')
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->store()->$method(...$parameters);
    }

    /**
     * Varsayılan sürücü adını döndürür.
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('cache.default');
    }
}