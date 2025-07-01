<?php
namespace Core\Session\Handlers;

use SessionHandlerInterface;
use Core\Config;

/**
 * FileSessionHandler Sınıfı
 *
 * PHP oturum verilerini dosya sisteminde saklamak için kullanılan
 * bir oturum işleyicisidir. SessionHandlerInterface'i uygular.
 */
class FileSessionHandler implements SessionHandlerInterface
{
    /**
     * Oturum dosyalarının saklanacağı dizin.
     * @var string
     */
    protected $path;

    public function __construct(Config $config)
    {
        $this->path = $config->get('session.files', BASE_PATH . '/storage/framework/sessions');

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * Oturum açma işlemi.
 *
     * @param string $path Oturum dosyalarının yolu.
     * @param string $name Oturum adı.
     * @return bool
     */
    public function open(string $path, string $name): bool
    {
        // PHP'nin varsayılan oturum kaydetme yolunu kullanmak yerine kendi yolumuzu kullanıyoruz.
        // Bu metod genellikle bir şey yapmaz, çünkü yol zaten constructor'da ayarlanmıştır.
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
        $filePath = $this->getSessionFilePath($id);
        if (file_exists($filePath)) {
            return (string) file_get_contents($filePath);
        }
        return '';
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
        $filePath = $this->getSessionFilePath($id);
        return file_put_contents($filePath, $data, LOCK_EX) !== false;
    }

    /**
     * Oturum verilerini silme işlemi.
 *
     * @param string $id Oturum ID'si.
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $filePath = $this->getSessionFilePath($id);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }

    /**
     * Çöp toplama işlemi.
 *
     * @param int $max_lifetime Oturumun maksimum ömrü (saniye cinsinden).
     * @return int|false
     */
    public function gc(int $max_lifetime): int|false
    {
        $count = 0;
        foreach (glob($this->path . '/*') as $file) {
            if (filemtime($file) + $max_lifetime < time() && file_exists($file)) {
                unlink($file);
                $count++;
            }
        }
        return $count;
    }

    /**
     * Oturum dosyasının tam yolunu döndürür.
 *
     * @param string $id Oturum ID'si.
     * @return string
     */
    protected function getSessionFilePath(string $id): string
    {
        return $this->path . '/sess_' . $id;
    }
}
