<?php
namespace Core\Queue;

/**
 * Job Sınıfı
 *
 * Kuyruğa eklenecek her bir işin (job) temel yapısını tanımlar.
 * Bu sınıf, işin nasıl işleneceğini (handle metodu) ve
 * işlenirken hangi bağımlılıklara ihtiyaç duyduğunu belirtir.
 */
abstract class Job
{
    /**
     * İşin işlenmesi için gerekli mantığı içerir.
     * Bu metod, iş kuyruktan çekildiğinde çağrılır.
     *
     * @param mixed ...$args İşin ihtiyaç duyduğu argümanlar.
     * @return mixed
     */
    abstract public function handle(...$args);

    /**
     * İşin kuyruğa eklendiği zamanı tutar.
     * @var int
     */
    public $queuedAt;

    /**
     * İşin kaç kez yeniden denendiğini tutar.
     * @var int
     */
    public $attempts = 0;

    /**
     * İşin maksimum deneme sayısı.
     * @var int
     */
    public $maxAttempts = 3;

    /**
     * İşin başarısız olması durumunda yeniden denemeden önce beklenecek süre (saniye).
     * @var int
     */
    public $retryAfter = 60;

    public function __construct()
    {
        $this->queuedAt = time();
    }

    /**
     * İşin yeniden deneme sayısını artırır.
     */
    public function incrementAttempts(): void
    {
        $this->attempts++;
    }

    /**
     * İşin maksimum deneme sayısını aşıp aşmadığını kontrol eder.
     * @return bool
     */
    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= $this->maxAttempts;
    }
}
