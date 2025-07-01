<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Controllers\PageController;

/**
 * HomepageTest
 *
 * "Feature" testleri, uygulamanın belirli bir özelliğini, genellikle bir
 * kullanıcının bakış açısından test eder. Bu test, anasayfanın
 * doğru bir şekilde yüklenip yüklenmediğini kontrol eder.
 */
class HomepageTest extends TestCase
{
    /**
     * Bu, PHPUnit tarafından bir test metodu olarak tanınır.
     * @test
     */
    public function anasayfa_basariyla_yukleniyor_ve_dogru_icerigi_gosteriyor(): void
    {
        /**
         * Gerçek bir HTTP isteği yapmak yerine (bu daha karmaşık bir altyapı gerektirir),
         * isteğin yönlendirileceği Controller'ı doğrudan DI konteynerinden
         * çözerek test etmek, saf birim/özellik testi için daha hızlı ve basittir.
         */
        
        // 1. Hazırlık (Arrange)
        // PageController'ı tüm bağımlılıklarıyla birlikte konteynerden al.
        $controller = $this->app(PageController::class);

        // Controller metodunun çıktısını yakalamak için çıktı tamponlamayı başlat.
        ob_start();
        
        // 2. Eylem (Act)
        // Controller'ın home metodunu çalıştır.
        $controller->home();
        
        // Tampondaki HTML çıktısını bir değişkene al ve tamponu temizle.
        $output = ob_get_clean();

        // 3. Doğrulama (Assert)
        // Dönen HTML çıktısının içinde beklenen metinlerin olup olmadığını kontrol et.
        $this->assertStringContainsString(
            'Phoenix Framework\'e Hoş Geldiniz!',
            $output,
            "Anasayfa, beklenen 'Hoş Geldiniz' mesajını içermiyor."
        );

        $this->assertStringContainsString(
            '<title>Ana Sayfa<\/title>', // Bu, `partials/header.php`'de render edilir.
            $output,
            "Anasayfanın başlığı doğru ayarlanmamış."
        );
    }
}
