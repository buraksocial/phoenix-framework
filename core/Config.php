<?php
namespace Core;

/**
 * Config Sınıfı
 *
 * Uygulama yapılandırma dosyalarını (config/*.php) yükler ve yönetir.
 * Nokta notasyonu ile (örn: 'app.name', 'database.connections.mysql')
 * yapılandırma değerlerine erişim sağlar.
 */
class Config
{
    /**
     * Yüklenmiş tüm yapılandırma değerlerini tutar.
     * @var array
     */
    protected $items = [];

    /**
     * Yapılandırma dosyalarının bulunduğu dizin.
     * @var string
     */
    protected $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
        $this->loadConfigurations();
    }

    /**
     * Tüm yapılandırma dosyalarını yükler.
     * Her dosya bir dizi döndürmelidir.
     */
    protected function loadConfigurations(): void
    {
        $files = glob($this->configPath . '/*.php');
        foreach ($files as $file) {
            $name = basename($file, '.php');
            $this->items[$name] = require $file;
        }
    }

    /**
     * Belirtilen anahtara ait yapılandırma değerini getirir.
     * Nokta notasyonu destekler (örn: 'app.name').
     *
     * @param string $key Getirilecek yapılandırma anahtarı.
     * @param mixed|null $default Anahtar bulunamazsa döndürülecek varsayılan değer.
     * @return mixed Yapılandırma değeri veya varsayılan değer.
     */
    public function get(string $key, $default = null)
    {
        $parts = explode('.', $key);
        $current = $this->items;

        foreach ($parts as $part) {
            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } else {
                return $default;
            }
        }

        return $current;
    }

    /**
     * Belirtilen anahtara bir yapılandırma değeri atar.
     * Nokta notasyonu destekler.
     *
     * @param string $key Ayarlanacak yapılandırma anahtarı.
     * @param mixed $value Atanacak değer.
     */
    public function set(string $key, $value): void
    {
        $parts = explode('.', $key);
        $current = &$this->items; // Referans ile çalış

        while (count($parts) > 1) {
            $part = array_shift($parts);
            if (!isset($current[$part]) || !is_array($current[$part])) {
                $current[$part] = [];
            }
            $current = &$current[$part];
        }

        $current[array_shift($parts)] = $value;
    }
}
