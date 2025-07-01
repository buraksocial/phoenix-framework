<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Commands\CreateUserCommand;
use Core\Bus;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * UserServiceTest
 *
 * "Birim" testleri, bir sınıfın veya metodun kendi iç mantığını,
 * dış bağımlılıklarından (veritabanı, API'lar vb.) izole ederek test eder.
 * Bu, "mocking" (taklit etme) tekniği ile yapılır.
 */
class UserServiceTest extends TestCase
{
    /**
     * @var Bus|MockObject Taklit edilmiş Bus nesnesi
     */
    private $busMock;

    /**
     * Her testten önce, bağımlılıkları taklit etmek (mock) için bu metot çalışır.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // PHPUnit'in `createMock` metodunu kullanarak Bus sınıfının bir taklidini oluştur.
        $this->busMock = $this->createMock(Bus::class);

        // DI Konteynerine, ne zaman Bus sınıfı istenirse, bizim oluşturduğumuz
        // bu sahte nesneyi vermesini söylüyoruz.
        $this->app()->set(Bus::class, $this->busMock);
    }
    
    /** @test */
    public function register_metodu_dogru_komutu_bus_uzerinden_gondermelidir(): void
    {
        // 1. Hazırlık (Arrange)
        
        $userData = [
            'name' => 'Test Kullanıcısı',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Sahte Bus'ımıza talimat veriyoruz:
        // - `dispatch` metodunun tam olarak 1 kez çağrılmasını bekle.
        // - Bu çağrı sırasında, parametre olarak gelen nesnenin `CreateUserCommand`
        //   türünde olduğunu ve doğru e-posta adresini içerdiğini doğrula.
        $this->busMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) use ($userData) {
                return $command instanceof CreateUserCommand &&
                       $command->email === $userData['email'];
            }));
            
        // UserService'i konteynerden al. Artık bu servis, bizim sahte Bus'ımızı kullanıyor.
        $userService = $this->app(UserService::class);


        // 2. Eylem (Act)
        // Test edeceğimiz metodu çağır.
        $userService->register($userData);


        // 3. Doğrulama (Assert)
        // Bu testte, asıl doğrulama `expects($this->once())` ile yukarıda yapıldı.
        // Eğer `dispatch` metodu beklendiği gibi çağrılmazsa, PHPUnit testi
        // otomatik olarak başarısız sayacaktır.
    }
}
