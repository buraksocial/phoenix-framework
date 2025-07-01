<?php
namespace Core;

use Core\Session\SessionManager as Session;
use Core\Database\Connection as DB;

/**
 * Auth Sınıfı
 *
 * Kullanıcı kimlik doğrulama, oturum yönetimi ve rol tabanlı erişim
 * kontrolü (RBAC) gibi işlemleri yöneten statik bir yardımcı sınıftır.
 */
class Auth
{
    /**
     * Oturum açan kullanıcının veritabanından yüklenmiş nesnesini tutar.
     * Bu, her istekte tekrar tekrar veritabanı sorgusu yapılmasını engeller.
     * @var object|null
     */
    protected static ?object $user = null;

    /**
     * Kullanıcının yüklenip yüklenmediğini belirten flag.
     * @var bool
     */
    protected static bool $userLoaded = false;

    /**
     * Bir kullanıcının oturum açıp açmadığını kontrol eder.
     * @return bool
     */
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Oturum açmış kullanıcının ID'sini döndürür.
     * @return int|null
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Oturum açmış kullanıcının tüm bilgilerini veritabanından getirir.
     * Sonuç, performans için istek boyunca önbelleklenir.
     * @return object|null
     */
    public static function user(): ?object
    {
        if (!static::check()) {
            return null;
        }

        if (!static::$userLoaded) {
            // Veritabanı bağlantısını DI Konteynerinden alarak sorgu yap
            $db = app(DB::class)->connection();
            static::$user = $db->first("SELECT * FROM users WHERE id = ?", [static::id()]);
            static::$userLoaded = true;
        }

        return static::$user;
    }

    /**
     * Belirtilen kullanıcı için bir oturum başlatır.
     * Session fixation saldırılarını önlemek için session ID'sini yeniler.
     * @param object $user Giriş yapacak kullanıcının nesnesi (en az 'id' özelliğini içermelidir).
     */
    public static function login(object $user): void
    {
        // Oturumu yeniden oluşturarak güvenlik sağla
        Session::regenerate(true);
        Session::put('user_id', $user->id);

        // Kullanıcıyı statik önbelleğe al
        static::$user = $user;
        static::$userLoaded = true;
    }

    /**
     * Kullanıcının oturumunu sonlandırır.
     */
    public static function logout(): void
    {
        static::$user = null;
        static::$userLoaded = false;
        
        Session::flush();
        Session::regenerate(true);
    }
    
    /**
     * Oturum açmış kullanıcının belirtilen role sahip olup olmadığını kontrol eder.
     * Bu metodun çalışması için 'users' tablosunda 'role' adında bir sütun olmalıdır.
     * @param string|array $roles Kontrol edilecek tekil rol (string) veya roller (array).
     * @return bool
     */
    public static function hasRole($roles): bool
    {
        $user = static::user();

        if (!$user || !isset($user->role)) {
            return false;
        }
        
        if (is_array($roles)) {
            return in_array($user->role, $roles);
        }

        return $user->role === $roles;
    }
}
