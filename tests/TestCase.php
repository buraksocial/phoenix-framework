<?php
namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use DI\Container;
use Core\Auth;
use App\Models\User;

/**
 * TestCase Sınıfı
 *
 * Projedeki tüm test sınıflarının miras alacağı temel sınıftır.
 * Uygulamayı testler için başlatmak ve testler sırasında kullanılabilecek
 * ortak yardımcı metotları sağlamak gibi görevleri vardır.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var Container|null DI Konteyneri
     */
    protected static ?Container $container = null;

    /**
     * Tüm test sınıfı çalıştırılmadan önce bir kez çalışır.
     * Uygulamayı başlatır.
     */
    public static function setUpBeforeClass(): void
    {
        if (is_null(static::$container)) {
            // Test ortamını başlatan bootstrap dosyasını dahil et
            // ve DI konteynerini al.
            static::$container = require __DIR__ . '/../bootstrap/app.php';
        }
    }

    /**
     * Her bir test metodu çalıştırılmadan önce çağrılır.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Gerekirse, veritabanını her testten önce sıfırlamak gibi
        // işlemler burada yapılabilir (In-memory SQLite ile çok hızlıdır).
        // `php artisan migrate:fresh --seed` gibi bir komut simüle edilebilir.
    }

    /**
     * DI Konteynerine veya içindeki bir servise kolayca erişim sağlar.
     * @param string|null $abstract
     * @return mixed|Container
     */
    public function app(string $abstract = null)
    {
        if (is_null($abstract)) {
            return static::$container;
        }
        return static::$container->get($abstract);
    }
    
    /**
     * Belirtilen kullanıcı olarak oturum açmış gibi davranır.
     * @param User $user Oturum açacak kullanıcı nesnesi
     * @return $this
     */
    protected function actingAs(User $user): self
    {
        Auth::login($user);
        return $this;
    }
}
