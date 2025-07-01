<?php
namespace Core\Database;

use PDO;
use PDOStatement;

/**
 * QueryBuilder Sınıfı
 *
 * PDO bağlantısı üzerinden veritabanı sorgularını daha güvenli ve kolay
 * bir şekilde oluşturmak ve yürütmek için bir arayüz sağlar.
 * SQL enjeksiyonuna karşı koruma için parametreli sorguları kullanır.
 */
class QueryBuilder
{
    /**
     * @var PDO Veritabanı bağlantısı.
     */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Bir SELECT sorgusu yürütür ve tüm sonuçları döndürür.
     *
     * @param string $query SQL sorgusu.
     * @param array $params Sorgu parametreleri.
     * @return array<object> Sorgu sonuçları nesne dizisi olarak.
     */
    public function select(string $query, array $params = []): array
    {
        $stmt = $this->execute($query, $params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Bir SELECT sorgusu yürütür ve tek bir satır döndürür.
     *
     * @param string $query SQL sorgusu.
     * @param array $params Sorgu parametreleri.
     * @return object|null Tek bir sonuç nesnesi veya null.
     */
    public function first(string $query, array $params = []): ?object
    {
        $stmt = $this->execute($query, $params);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result !== false ? $result : null;
    }

    /**
     * Bir INSERT sorgusu yürütür.
     *
     * @param string $table Eklenecek tablo adı.
     * @param array $data Eklenecek veriler (anahtar-değer çiftleri).
     * @return int Eklenen son kaydın ID'si.
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->execute($query, array_values($data));

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Bir UPDATE sorgusu yürütür.
     *
     * @param string $table Güncellenecek tablo adı.
     * @param array $data Güncellenecek veriler (anahtar-değer çiftleri).
     * @param string $where WHERE koşulu (örn: 'id = ?').
     * @param array $whereParams WHERE koşulunun parametreleri.
     * @return int Etkilenen satır sayısı.
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
        }
        $setClause = implode(', ', $setParts);

        $query = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $params = array_merge(array_values($data), $whereParams);

        $stmt = $this->execute($query, $params);
        return $stmt->rowCount();
    }

    /**
     * Bir DELETE sorgusu yürütür.
     *
     * @param string $table Silinecek tablo adı.
     * @param string $where WHERE koşulu (örn: 'id = ?').
     * @param array $whereParams WHERE koşulunun parametreleri.
     * @return int Etkilenen satır sayısı.
     */
    public function delete(string $table, string $where, array $whereParams = []): int
    {
        $query = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->execute($query, $whereParams);
        return $stmt->rowCount();
    }

    /**
     * Ham bir SQL sorgusu yürütür (INSERT, UPDATE, DELETE vb. için).
     *
     * @param string $query SQL sorgusu.
     * @param array $params Sorgu parametreleri.
     * @return int Etkilenen satır sayısı.
     */
    public function statement(string $query, array $params = []): int
    {
        $stmt = $this->execute($query, $params);
        return $stmt->rowCount();
    }

    /**
     * Bir sorguyu hazırlar ve yürütür.
     *
     * @param string $query SQL sorgusu.
     * @param array $params Sorgu parametreleri.
     * @return PDOStatement
     * @throws \PDOException
     */
    protected function execute(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Veritabanı bağlantısını döndürür.
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
