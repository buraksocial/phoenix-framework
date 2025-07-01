<?php
namespace Core;

/**
 * ViewManager Sınıfı
 *
 * Uygulamanın görünüm (view) dosyalarını yönetir.
 * Görünüm dosyalarını render ederken verilere erişim sağlar
 * ve layout (ana şablon) kullanımını destekler.
 */
class ViewManager
{
    /**
     * Görünüm dosyalarının bulunduğu dizin.
     * @var string
     */
    protected $viewsPath;

    /**
     * Görünüm dosyalarına aktarılacak global veriler.
     * @var array
     */
    protected $data = [];

    /**
     * Kullanılacak ana şablon (layout) dosyası.
     * @var string|null
     */
    protected $layout = null;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
    }

    /**
     * Görünüm dosyasına veri aktarır.
     *
     * @param string|array $key Tek bir anahtar veya anahtar-değer çiftleri dizisi.
     * @param mixed|null $value Eğer $key string ise, aktarılacak değer.
     * @return self
     */
    public function with($key, $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Görünüm için bir ana şablon (layout) belirler.
     *
     * @param string $layoutName Şablon dosyasının adı (uzantısız).
     * @return self
     */
    public function layout(string $layoutName): self
    {
        $this->layout = $layoutName;
        return $this;
    }

    /**
     * Bir görünüm dosyasını render eder.
     *
     * @param string $viewName Görünüm dosyasının adı (uzantısız, örn: 'home.index').
     * @param array $data Görünüme aktarılacak ek veriler.
     * @return string Render edilmiş HTML içeriği.
     * @throws \Exception Görünüm dosyası bulunamazsa.
     */
    public function render(string $viewName, array $data = []): string
    {
        $viewPath = $this->resolveViewPath($viewName);

        if (!file_exists($viewPath)) {
            throw new \Exception("Görünüm dosyası bulunamadı: {$viewPath}");
        }

        // Global verileri ve metoda özel verileri birleştir
        $data = array_merge($this->data, $data);

        // Verileri yerel değişkenler olarak çıkar
        extract($data);

        // Görünüm içeriğini tampona al
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Eğer bir layout belirlenmişse, içeriği layout'a yerleştir
        if ($this->layout) {
            $layoutPath = $this->resolveViewPath($this->layout);
            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout dosyası bulunamadı: {$layoutPath}");
            }
            ob_start();
            require $layoutPath;
            $content = ob_get_clean();
            $this->layout = null; // Layout'u sıfırla
        }

        return $content;
    }

    /**
     * Görünüm dosyasının tam yolunu çözer.
     * Nokta notasyonunu dizin ayırıcıya çevirir.
     *
     * @param string $viewName
     * @return string
     */
    protected function resolveViewPath(string $viewName): string
    {
        return $this->viewsPath . '/' . str_replace('.', '/', $viewName) . '.php';
    }
}
