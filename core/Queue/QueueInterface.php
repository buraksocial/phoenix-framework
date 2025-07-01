<?php
namespace Core\Queue;

/**
 * QueueInterface
 *
 * Tüm kuyruk sürücülerinin (sync, file, redis vb.) uygulaması gereken metotları
 * tanımlayan bir kontrattır (interface). Bu arayüz sayesinde QueueManager,
 * hangi sürücüyü kullandığını bilmeden tüm sürücülerle aynı şekilde
 * konuşabilir.
 */
interface QueueInterface
{
    /**
     * Bir işi kuyruğa ekler.
     *
     * @param Job $job Kuyruğa eklenecek iş nesnesi.
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return bool İşlem başarılı ise true, değilse false.
     */
    public function push(Job $job, string $queue = null): bool;

    /**
     * Kuyruktan bir işi çeker ve döndürür.
     *
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return Job|null İş nesnesi veya kuyruk boşsa null.
     */
    public function pop(string $queue = null): ?Job;

    /**
     * Kuyruktaki bir işi siler.
     *
     * @param Job $job Silinecek iş nesnesi.
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return bool İşlem başarılı ise true, değilse false.
     */
    public function delete(Job $job, string $queue = null): bool;

    /**
     * Başarısız olan bir işi yeniden kuyruğa ekler.
     *
     * @param Job $job Yeniden denenecek iş nesnesi.
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return bool İşlem başarılı ise true, değilse false.
     */
    public function release(Job $job, string $queue = null): bool;

    /**
     * Belirtilen kuyruktaki tüm işleri temizler.
     *
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return bool İşlem başarılı ise true, değilse false.
     */
    public function clear(string $queue = null): bool;

    /**
     * Belirtilen kuyruktaki iş sayısını döndürür.
     *
     * @param string|null $queue Kuyruk adı (isteğe bağlı).
     * @return int Kuyruktaki iş sayısı.
     */
    public function size(string $queue = null): int;
}
