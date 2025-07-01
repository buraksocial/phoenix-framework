# Phoenix Framework 🔥

**Saf PHP'nin ruhu, modern mimarinin gücüyle yeniden doğdu.**

Phoenix Framework, herhangi bir ana akım framework'ün getirdiği karmaşıklık ve öğrenme eğrisi olmadan, modern, ölçeklenebilir ve profesyonel web uygulamaları geliştirmek için tasarlanmış, modüler ve geliştirici dostu bir PHP iskeletidir. Bu proje, ham PHP'nin esnekliğini ve kontrolünü korurken, en iyi yazılım mühendisliği desenlerini ve araçlarını sunmayı hedefler.

[](https://opensource.org/licenses/MIT)
[](https://www.php.net/)
[](https://www.google.com/search?q=https://github.com/buraksocial/phoenix-framework)
[](https://www.google.com/search?q=https://github.com/buraksocial/phoenix-framework/stargazers)
[](https://www.google.com/search?q=https://github.com/buraksocial/phoenix-framework/network/members)

-----

## ✨ Phoenix Felsefesi

Günümüz web geliştirme dünyasında, Laravel ve Symfony gibi güçlü framework'ler harika çözümler sunmaktadır. Ancak bazen, bir projenin ihtiyaçları bu kadar büyük bir yapıyı gerektirmez veya geliştirici, projenin her bir parçasını tam olarak kontrol etmek isteyebilir.

Phoenix, bu boşluğu doldurmak için doğdu. Felsefemiz basit:

1.  **Kontrol Geliştiricide Kalsın:** Size sihirli, "perde arkasında" çalışan karmaşık yapılar dayatmak yerine, her bir parçanın nasıl çalıştığını görebileceğiniz, anlayabileceğiniz ve değiştirebileceğiniz şeffaf bir yapı sunar.
2.  **Modern Mimariden Ödün Verme:** Saf PHP kullanmak, eski usul kod yazmak anlamına gelmemelidir. Phoenix, Dependency Injection, Service Katmanı, Modüler Mimari, CQRS gibi en modern ve kanıtlanmış mimari desenleri temel alır.
3.  **Geliştirici Deneyimini (DX) Ön Planda Tut:** Tekrarlayan ve sıkıcı işleri otomatize eden güçlü bir komut satırı aracı (`artisan`), otomatik kod oluşturan `make` komutları ve bildirimsel (declarative) Ajax motoru gibi araçlarla geliştirme sürecini keyifli ve verimli hale getirir.

Bu proje, bir insanın vizyonu ile bir yapay zekanın iş birliğinin, sıfırdan ne kadar kapsamlı ve profesyonel bir sistem inşa edebileceğinin bir kanıtıdır.

## 🚀 Öne Çıkan Özellikler

Phoenix, kutudan çıktığı anda size güçlü bir araç seti sunar:

  * **Modüler Mimari:** Uygulamanızı, her biri kendi rotaları, controller'ları ve view'ları olan, kendi kendine yeten modüllere ayırın.
  * **Gelişmiş DI Konteyneri:** `php-di/php-di` entegrasyonu ile sınıflarınız ve bağımlılıklarınız otomatik olarak yönetilir, bu da esnek ve test edilebilir bir kod tabanı sağlar.
  * **Çoklu Veritabanı Desteği:** Projenizin farklı bölümlerini farklı veritabanlarına (MySQL, PostgreSQL, hatta MongoDB) bağlayabilen, sürücü tabanlı bir veritabanı soyutlama katmanı.
  * **Güçlü Komut Satırı Arayüzü (Artisan):** `make:controller`, `make:module`, `migrate`, `config:cache` gibi komutlarla geliştirme ve bakım süreçlerinizi otomatikleştirin.
  * **Deklaratif Ajax Motoru:** Neredeyse hiç JavaScript yazmadan, sadece HTML'e `data-ajax-*` attribute'ları ekleyerek dinamik formlar, butonlar ve canlı arama kutuları oluşturun.
  * **Güvenlik Kalkanı:** CSRF ve XSS korumaları için yerleşik, kullanımı kolay yardımcılar.
  * **Rol Tabanlı Erişim Kontrolü (RBAC):** `Auth::hasRole('admin')` gibi basit kontrollerle rotaları ve işlemleri kullanıcı rollerine göre koruyun.
  * **Olay & Dinleyici Sistemi:** Uygulamanızın farklı parçalarını birbirinden ayırarak, esnek ve bakımı kolay bir yapı kurun (`user.registered` olayı -\> `SendWelcomeEmailListener`).
  * **Arka Plan İşlemleri (Queue):** E-posta gönderme gibi yavaş işlemleri, kullanıcıyı bekletmeden arka planda çalışması için bir kuyruk sistemine atın.
  * **Ve Daha Fazlası:** Çoklu dil desteği, veritabanı migration ve seeder'ları, API Resource sınıfları, Pipeline deseni, dinamik View Component'leri ve gelişmiş hata ayıklama araçları.

## 📋 Gereksinimler

  * PHP \>= 8.1
  * Composer
  * NPM (veya Yarn)
  * PDO, MySQLi, Redis gibi veritabanları için ilgili PHP eklentileri.

## 🛠️ Kurulum

Phoenix Framework'ü kurmak ve çalıştırmak sadece birkaç dakika sürer.

1.  **Projeyi Klonlayın:**

    ```bash
    git clone https://github.com/buraksocial/phoenix-framework.git
    cd phoenix-framework
    ```

2.  **PHP Bağımlılıklarını Yükleyin:**

    ```bash
    composer install
    ```

3.  **JavaScript Bağımlılıklarını Yükleyin:**

    ```bash
    npm install
    ```

4.  **Ortam Değişkenleri Dosyasını Oluşturun:**
    `.env.example` dosyasını kopyalayarak `.env` adıyla yeni bir dosya oluşturun.

    ```bash
    cp .env.example .env
    ```

5.  **Uygulama Anahtarını Oluşturun:**
    Bu komut, `.env` dosyanızdaki `APP_KEY` değişkenini dolduracaktır.

    ```bash
    php artisan key:generate
    ```

6.  **.env Dosyasını Yapılandırın:**
    Oluşturduğunuz `.env` dosyasını açın ve özellikle `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` gibi veritabanı bağlantı bilgilerinizi kendi yerel ortamınıza göre düzenleyin.

7.  **Veritabanı Migration'larını Çalıştırın:**
    Bu komut, `users` ve `posts` gibi başlangıç tablolarını veritabanınızda oluşturacaktır.

    ```bash
    php artisan migrate
    ```

8.  **Frontend Geliştirme Sunucusunu Başlatın:**
    Bu komut, Vite'ı başlatır ve yaptığınız CSS/JS değişikliklerini anında tarayıcıya yansıtır.

    ```bash
    npm run dev
    ```

9.  **Web Sunucusunu Yapılandırın:**
    Nginx veya Apache gibi web sunucunuzun "Document Root" (kök dizin) ayarını projenin içindeki `/public` klasörüne yönlendirin. Bu, sadece `public` klasörünün dış dünyaya açık olmasını sağlayarak güvenliği artırır.

Artık projeniz `APP_URL`'de belirttiğiniz adreste çalışmaya hazır\!

## 命令行️ Artisan CLI Komutları

Phoenix, geliştirme sürecini hızlandırmak için güçlü bir komut satırı arayüzü ile birlikte gelir. İşte en sık kullanılan komutlardan bazıları:

| Komut | Açıklama |
| :--- | :--- |
| `key:generate` | `.env` dosyası için güvenli bir uygulama anahtarı oluşturur. |
| `make:controller <Ad>` | Yeni bir controller sınıfı oluşturur. (Örn: `PostController`) |
| `make:model <Ad>` | Yeni bir model sınıfı oluşturur. (Örn: `Product`) |
| `make:service <Ad>` | Yeni bir servis sınıfı oluşturur. (Örn: `PaymentService`) |
| `make:module <Ad>` | Kendi rotaları, controller'ları olan yeni bir modül oluşturur. (Örn: `Shop`) |
| `make:migration <Ad>` | Yeni bir veritabanı migration dosyası oluşturur. (Örn: `create_products_table`) |
| `migrate` | Bekleyen tüm veritabanı migration'larını çalıştırır. |
| `db:seed` | Veritabanını başlangıç verileriyle doldurur. |
| `cache:clear` | Uygulama önbelleğini temizler. |
| `config:cache` | Performans için konfigürasyon dosyalarını önbellekler. |
| `route:cache` | Performans için rota dosyalarını önbellekler. |

## 📦 Modüler Mimari

Phoenix'in en güçlü yanlarından biri modüler mimarisidir. Bu, büyük bir uygulamayı, her biri kendi sorumluluk alanına sahip, daha küçük ve yönetilebilir parçalara ayırmanıza olanak tanır.

Bir modül, `/modules` klasörü altında kendi adıyla yer alır ve tipik olarak şu yapıya sahiptir:

```
/modules
└── /Blog
    ├── /Config
    ├── /Controllers
    ├── /Database
    ├── /Models
    ├── /Routes
    └── /Views
```

Yeni bir `Shop` modülü oluşturmak için tek yapmanız gereken:

```bash
php artisan make:module Shop
```

Bu komut, `/modules/Shop` altında gerekli tüm klasörleri ve başlangıç dosyalarını sizin için otomatik olarak oluşturacaktır. Modülün rotaları, `/shop` öneki ile otomatik olarak ana uygulamaya dahil edilir. Modül view'larına ise `view('Shop::index')` gibi özel bir "namespace" ile erişebilirsiniz.

## 🤝 Katkıda Bulunma

Phoenix Framework açık kaynaklı bir projedir ve topluluğun katkılarıyla daha da gelişebilir. Katkıda bulunmak isterseniz:

1.  Projeyi fork'layın.
2.  Yeni bir özellik veya hata düzeltmesi için kendi branch'inizi oluşturun (`git checkout -b ozellik/yeni-bir-sey`).
3.  Değişikliklerinizi commit'leyin (`git commit -m 'Yeni bir özellik eklendi'`).
4.  Branch'inizi GitHub'a push'layın (`git push origin ozellik/yeni-bir-sey`).
5.  Bir Pull Request oluşturun.

Tüm katkılarınız için şimdiden teşekkürler\!

## 📜 Lisans

Phoenix Framework, [MIT Lisansı](https://opensource.org/licenses/MIT) altında lisanslanmıştır.
