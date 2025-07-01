<?php

use DI\Container;
use Core\Config;
use Core\Cache\Cache;
use Core\Session\SessionManager;
use Core\Bus;
use Core\ViewManager;

if (!function_exists('app')) {
    /**
     * DI konteynerinden bir servis örneği döndürür.
     * Eğer $abstract verilmezse, konteynerin kendisini döndürür.
     *
     * @param string|null $abstract
     * @return mixed|Container
     */
    function app(?string $abstract = null)
    {
        static $container = null;

        if (is_null($container)) {
            // bootstrap/app.php'den yüklenen konteyneri al
            $container = require BASE_PATH . '/bootstrap/app.php';
        }

        if (is_null($abstract)) {
            return $container;
        }

        return $container->get($abstract);
    }
}

if (!function_exists('env')) {
    /**
     * Ortam değişkeni değerini döndürür.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        // $_ENV süper globalinden oku
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        // getenv() fonksiyonundan oku
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        // Boolean ve null değerleri dönüştür
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Yapılandırma değerini döndürür.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        return app(Config::class)->get($key, $default);
    }
}

if (!function_exists('cache')) {
    /**
     * Cache yöneticisi örneğini döndürür veya önbelleğe bir değer ekler/alır.
     *
     * @param string|array|null $key
     * @param mixed|null $value
     * @param int $minutes
     * @return mixed|Cache
     */
    function cache($key = null, $value = null, int $minutes = 60)
    {
        $cache = app(Cache::class);

        if (is_null($key)) {
            return $cache;
        }

        if (is_array($key)) {
            // cache(['key' => 'value'], 10) şeklinde kullanım
            foreach ($key as $k => $v) {
                $cache->set($k, $v, $minutes);
            }
            return true;
        }

        if (is_null($value)) {
            return $cache->get($key);
        }

        return $cache->set($key, $value, $minutes);
    }
}

if (!function_exists('session')) {
    /**
     * Session yöneticisi örneğini döndürür veya oturumdan bir değer alır/ekler.
     *
     * @param string|array|null $key
     * @param mixed|null $value
     * @return mixed|SessionManager
     */
    function session($key = null, $value = null)
    {
        $session = app(SessionManager::class);

        if (is_null($key)) {
            return $session;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $session->put($k, $v);
            }
            return true;
        }

        if (is_null($value)) {
            return $session->get($key);
        }

        $session->put($key, $value);
        return true;
    }
}

if (!function_exists('dispatch')) {
    /**
     * Bir komut veya sorguyu Bus üzerinden gönderir.
     *
     * @param object $dispatchable
     * @return mixed
     */
    function dispatch(object $dispatchable)
    {
        return app(Bus::class)->dispatch($dispatchable);
    }
}

if (!function_exists('view')) {
    /**
     * Bir görünüm (view) render eder.
     *
     * @param string $viewName
     * @param array $data
     * @return string
     */
    function view(string $viewName, array $data = []): string
    {
        return app(ViewManager::class)->render($viewName, $data);
    }
}

if (!function_exists('redirect')) {
    /**
     * Belirtilen URL'ye yönlendirme yapar.
     *
     * @param string $url
     * @param int $status HTTP durum kodu
     */
    function redirect(string $url, int $status = 302): void
    {
        header("Location: {$url}", true, $status);
        exit();
    }
}

if (!function_exists('back')) {
    /**
     * Kullanıcıyı bir önceki sayfaya yönlendirir.
     *
     * @param int $status HTTP durum kodu
     */
    function back(int $status = 302): void
    {
        redirect($_SERVER['HTTP_REFERER'] ?? '/', $status);
    }
}

if (!function_exists('asset')) {
    /**
     * Asset (CSS, JS, resim vb.) dosyalarının URL'sini döndürür.
     * Vite ile entegrasyonu destekler.
     *
     * @param string $path Asset'in public dizinine göre yolu.
     * @return string
     */
    function asset(string $path): string
    {
        static $manifest = null;
        $publicPath = '/public'; // Public dizininin kök dizine göre yolu

        // Geliştirme ortamında Vite hot reload sunucusunu kullan
        if (env('APP_ENV') === 'development' && file_exists(BASE_PATH . '/public/hot')) {
            $hotUrl = file_get_contents(BASE_PATH . '/public/hot');
            return rtrim($hotUrl, '/') . '/' . $path;
        }

        // Production ortamında veya hot dosyası yoksa manifest'i kullan
        if (is_null($manifest)) {
            $manifestPath = BASE_PATH . '/public/build/manifest.json';
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
            } else {
                $manifest = [];
            }
        }

        // Manifest'te varsa build edilmiş yolu döndür
        if (isset($manifest[$path]['file'])) {
            return $publicPath . '/build/' . $manifest[$path]['file'];
        }

        // Manifest'te yoksa doğrudan yolu döndür (fallback)
        return $publicPath . '/' . $path;
    }
}

if (!function_exists('dd')) {
    /**
     * Değişkenleri döker ve betiği sonlandırır (Dump and Die).
     *
     * @param mixed ...$vars
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        exit(1);
    }
}

if (!function_exists('request')) {
    /**
     * HTTP isteği ile ilgili verilere erişim sağlar.
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|array
     */
    function request(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $_REQUEST; // GET, POST ve COOKIE verilerini içerir
        }
        return $_REQUEST[$key] ?? $default;
    }
}

if (!function_exists('is_ajax')) {
    /**
     * İsteğin bir AJAX isteği olup olmadığını kontrol eder.
     *
     * @return bool
     */
    function is_ajax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

if (!function_exists('response_json')) {
    /**
     * JSON yanıtı döndürür.
     *
     * @param array $data
     * @param int $status HTTP durum kodu
     */
    function response_json(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }
}
