<?php
namespace Core\Cache;

/**
 * DriverInterface
 *
 * Tüm önbellek sürücüsü sınıflarının uygulaması gereken metotları
 * tanımlayan bir kontrattır (interface). Bu arayüz sayesinde CacheManager,
 * hangi sürücüyü kullandığını bilmeden tüm sürücülerle aynı şekilde
 * konuşabilir.
 */
interface DriverInterface
{
    /**
     * Önbellekten belirtilen anahtara ait değeri getirir.
     *
     * @param string $key Getirilecek öğenin benzersiz anahtarı.
     * @param mixed|null $default Anahtar bulunamazsa döndürülecek varsayılan değer.
     * @return mixed Önbellekteki veri veya varsayılan değer.
     */
    public function get(string $key, $default = null);

    /**
     * Bir veriyi belirtilen süreyle (dakika cinsinden) önbelleğe kaydeder.
     *
     * @param string $key Kaydedilecek öğenin benzersiz anahtarı.
     * @param mixed $value Kaydedilecek veri. Serialize edilebilir olmalıdır.
     * @param int $minutes Verinin önbellekte kalma süresi (dakika).
     * @return bool Başarılı ise true, değilse false.
     */
    public function set(string $key, $value, int $minutes): bool;

    /**
     * Belirtilen anahtarın önbellekte olup olmadığını kontrol eder.
     *
     * @param string $key Kontrol edilecek anahtar.
     * @return bool Anahtar varsa true, yoksa false.
     */
    public function has(string $key): bool;

    /**
     * Belirtilen anahtara ait veriyi önbellekten siler.
     *
     * @param string $key Silinecek anahtar.
     * @return bool Başarılı ise true, değilse false.
     */
    public function forget(string $key): bool;

    /**
     * Tüm önbelleği temizler.
     *
     * @return bool Başarılı ise true, değilse false.
     */
    public function flush(): bool;
}
