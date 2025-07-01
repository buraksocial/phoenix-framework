<?php
namespace Modules\Blog\Models;

use Core\Database\Connection as DB;
use Database\Traits\Sluggable;
use Database\Traits\SoftDeletes;

/**
 * Post Modeli
 *
 * `posts` tablosunu temsil eder. Veritabanı işlemleri için bir arayüz sağlar
 * ve `Sluggable` ile `SoftDeletes` gibi yeniden kullanılabilir yetenekleri
 * Trait'ler aracılığıyla kendine dahil eder.
 */
class Post
{
    // Trait'leri bu sınıfa dahil et
    use Sluggable;
    use SoftDeletes;

    /**
     * @var string Bu modelin ilişkili olduğu veritabanı tablosu.
     */
    protected string $table = 'posts';

    // Veritabanı sütunlarına karşılık gelen özellikler
    public ?int $id = null;
    public int $user_id;
    public string $title;
    public string $slug;
    public string $body;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $deleted_at = null;


    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Modeli veritabanına kaydeder (yeni ise insert, mevcutsa update).
     * @return bool
     */
    public function save(): bool
    {
        // Slug'ı başlığa göre otomatik oluştur veya güncelle
        $this->slug = $this->generateUniqueSlug($this->title, $this->id);

        $data = [
            'user_id' => $this->user_id,
            'title'   => $this->title,
            'slug'    => $this->slug,
            'body'    => $this->body,
        ];

        $db = DB::connection();

        if ($this->id) {
            // Update
            return $db->update($this->table, $this->id, $data) > 0;
        } else {
            // Insert
            $result = $db->insert($this->table, $data);
            if ($result) {
                $this->id = $db->lastInsertId();
            }
            return $result > 0;
        }
    }
    
    /**
     * ID'ye göre bir post bulur.
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        $data = DB::connection()->first("SELECT * FROM posts WHERE id = ? AND deleted_at IS NULL", [$id]);
        return $data ? new static((array)$data) : null;
    }

    /**
     * Slug'a göre bir post bulur.
     * @param string $slug
     * @return static|null
     */
    public static function findBySlug(string $slug): ?static
    {
        $data = DB::connection()->first("SELECT * FROM posts WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        return $data ? new static((array)$data) : null;
    }
    
    /**
     * Bu gönderinin yazarını (User) döndürür.
     * Bu, basit bir ilişki (relationship) metodudur.
     * @return \App\Models\User|object|null
     */
    public function author()
    {
        // Gerçek ORM'de bu daha zarif olurdu. Bu basit bir versiyondur.
        return DB::connection()->first("SELECT id, name, email FROM users WHERE id = ?", [$this->user_id]);
    }
}