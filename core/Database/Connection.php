<?php
namespace Core\Database;

use Core\Config;
use PDO;
use Redis;

/**
 * Veritabanı Bağlantı Yöneticisi
 *
 * Uygulamanın veritabanı bağlantılarını (MySQL, PostgreSQL, SQLite) ve
 * Redis bağlantılarını yönetir. Tek bir merkezden bağlantıların
 * oluşturulmasını ve erişilmesini sağlar.
 */
class Connection
{
    /**
     * @var Config Uygulama yapılandırması.
     */
    protected $config;

    /**
     * Oluşturulmuş PDO bağlantılarını tutar.
     * @var array<string, PDO>
     */
    protected $connections = [];

    /**
     * Oluşturulmuş Redis bağlantılarını tutar.
     * @var array<string, Redis>
     */
    protected $redisConnections = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Varsayılan veritabanı bağlantısını başlatır.
     * Bu metod, bootstrap/app.php içinde çağrılır.
     */
    public function boot(): void
    {
        // Varsayılan veritabanı bağlantısını yükle
        $this->connection($this->config->get('database.default'));
    }

    /**
     * Belirtilen veya varsayılan veritabanı bağlantısını döndürür.
     *
     * @param string|null $name Bağlantı adı (örn: 'mysql', 'pgsql').
     * @return QueryBuilder
     * @throws \Exception
     */
    public function connection(string $name = null): QueryBuilder
    {
        $name = $name ?? $this->config->get('database.default');

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolveConnection($name);
        }

        return new QueryBuilder($this->connections[$name]);
    }

    /**
     * Belirtilen veritabanı bağlantısını oluşturur.
     *
     * @param string $name Bağlantı adı.
     * @return PDO
     * @throws \Exception
     */
    protected function resolveConnection(string $name): PDO
    {
        $config = $this->config->get("database.connections.{$name}");

        if (is_null($config)) {
            throw new \Exception("Veritabanı bağlantısı [{$name}] yapılandırılmamış.");
        }

        $driver = $config['driver'] ?? 'pdo';

        switch ($driver) {
            case 'pdo':
                return $this->createPdoConnection($config);
            case 'mysqli':
                // mysqli sürücüsü için ayrı bir implementasyon gerekebilir.
                // Şimdilik sadece PDO destekleniyor.
                throw new \Exception("MySQLi sürücüsü henüz desteklenmiyor. Lütfen 'pdo' kullanın.");
            default:
                throw new \Exception("Desteklenmeyen veritabanı sürücüsü: {$driver}");
        }
    }

    /**
     * PDO bağlantısını oluşturur.
     *
     * @param array $config Bağlantı yapılandırması.
     * @return PDO
     */
    protected function createPdoConnection(array $config): PDO
    {
        $dsn = '';
        switch ($config['driver']) {
            case 'mysql':
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                break;
            case 'pgsql':
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                break;
            case 'sqlite':
                $dsn = "sqlite:{$config['database']}";
                break;
            default:
                throw new \InvalidArgumentException("Bilinmeyen PDO sürücüsü: {$config['driver']}");
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Varsayılan olarak nesne döndür
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO(
            $dsn,
            $config['username'] ?? null,
            $config['password'] ?? null,
            $options
        );
    }

    /**
     * Belirtilen veya varsayılan Redis bağlantısını döndürür.
     *
     * @param string|null $name Bağlantı adı (örn: 'default', 'cache').
     * @return Redis
     * @throws \Exception
     */
    public function redis(string $name = null): Redis
    {
        $name = $name ?? 'default'; // Redis için varsayılan 'default'

        if (!isset($this->redisConnections[$name])) {
            $this->redisConnections[$name] = $this->resolveRedisConnection($name);
        }

        return $this->redisConnections[$name];
    }

    /**
     * Belirtilen Redis bağlantısını oluşturur.
     *
     * @param string $name Bağlantı adı.
     * @return Redis
     * @throws \Exception
     */
    protected function resolveRedisConnection(string $name): Redis
    {
        $config = $this->config->get("database.redis.{$name}");

        if (is_null($config)) {
            throw new \Exception("Redis bağlantısı [{$name}] yapılandırılmamış.");
        }

        $redis = new Redis();
        $redis->connect($config['host'], $config['port']);

        if (isset($config['password']) && $config['password']) {
            $redis->auth($config['password']);
        }

        if (isset($config['database'])) {
            $redis->select($config['database']);
        }

        return $redis;
    }
}
