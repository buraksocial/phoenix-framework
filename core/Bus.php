<?php
namespace Core;

use DI\Container;

/**
 * Bus Sınıfı (Command/Query Bus)
 *
 * CQRS (Command Query Responsibility Segregation) mimari desenini uygulamak için
 * kullanılan merkezi bir otobüstür. Gelen "Command" (yazma/değiştirme isteği) veya
 * "Query" (okuma isteği) nesnelerini, onlara karşılık gelen "Handler"
 * (işleyici) sınıflarına yönlendirir.
 */
class Bus
{
    /**
     * @var Container Bağımlılıkları çözmek için kullanılacak DI Konteyneri.
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Verilen komut veya sorguyu ilgili handler'a yönlendirir ve çalıştırır.
     * @param object $dispatchable Command veya Query nesnesi
     * @return mixed Handler'ın handle() metodundan dönen sonuç.
     * @throws \Exception Handler bulunamazsa veya handle metodu yoksa.
     */
    public function dispatch(object $dispatchable)
    {
        $handlerClass = $this->getHandlerClass($dispatchable);

        // Handler'ı DI konteynerinden çözerek kendi bağımlılıklarını
        // (örn: veritabanı, servisler) almasını sağla.
        $handler = $this->container->get($handlerClass);

        if (!method_exists($handler, 'handle')) {
             throw new \Exception("handle() metodu bulunamadı: {$handlerClass}");
        }

        return $handler->handle($dispatchable);
    }

    /**
     * Gönderilen sınıfa göre Handler sınıfının adını tahmin eder.
     * Bu, projenizde tutarlı bir isimlendirme kuralı olmasını gerektirir.
     *
     * Örnekler:
     * App\Commands\CreateUserCommand -> App\Handlers\Commands\CreateUserCommandHandler
     * App\Queries\GetActiveUsersQuery -> App\Handlers\Queries\GetActiveUsersQueryHandler
     *
     * @param object $dispatchable
     * @return string
     * @throws \Exception
     */
    protected function getHandlerClass(object $dispatchable): string
    {
        $className = get_class($dispatchable);
        
        $baseName = substr($className, strrpos($className, '\') + 1);
        
        if (str_ends_with($baseName, 'Command')) {
            $handlerBaseName = str_replace('Command', 'CommandHandler', $baseName);
            $handlerNamespace = 'App\\Handlers\\Commands';
        } elseif (str_ends_with($baseName, 'Query')) {
            $handlerBaseName = str_replace('Query', 'QueryHandler', $baseName);
            $handlerNamespace = 'App\\Handlers\\Queries';
        } else {
            // Eğer isimlendirme kuralına uymuyorsa, manuel bir harita (map) kullanılabilir
            // veya bir istisna fırlatılabilir.
            throw new \Exception("Handler sınıfı türetilemedi: {$className}. Sınıf 'Command' veya 'Query' ile bitmelidir.");
        }

        $handlerClass = $handlerNamespace . '\\' . $handlerBaseName;

        if (!class_exists($handlerClass)) {
            throw new \Exception("Eşleşen Handler sınıfı bulunamadı: {$handlerClass}");
        }

        return $handlerClass;
    }
}
