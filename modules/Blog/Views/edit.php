<?php 
use Core\Form;

// Ana layout'un header'ını dahil et
view('partials.header', ['pageTitle' => $pageTitle ?? 'Yazıyı Düzenle']); 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1><?= htmlspecialchars($pageTitle ?? 'Yazıyı Düzenle') ?></h1>
                </div>
                <div class="card-body">
                    
                    <?php 
                    // Flash mesajları göster (örn: "Yazı başarıyla güncellendi.")
                    display_flash_messages(); 
                    ?>

                    <?php
                    // Formu aç. Action olarak post'un ID'sini de gönderiyoruz.
                    Form::open('/blog/posts/' . $post->id, 'POST', [
                        'data-ajax-trigger' => 'submit',
                        'data-ajax-success-action' => 'toast', // Sadece bildirim göster, yönlendirme yapma
                    ]);
                    ?>

                    <?php 
                    // Form::text() yardımcısının üçüncü parametresine, veritabanından gelen
                    // mevcut değeri veriyoruz. Eğer form hatalı bir şekilde tekrar
                    // gönderilirse, `old()` fonksiyonu bu değere göre öncelikli olacaktır.
                    Form::text('title', 'Yazı Başlığı', $post->title, ['required' => true]); 
                    ?>

                    <?php 
                    Form::textarea('body', 'Yazı İçeriği', $post->body, ['rows' => 10, 'required' => true]);
                    ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/blog/posts/<?= htmlspecialchars($post->slug) ?>" class="btn btn-secondary">İptal / Görüntüle</a>
                        <?php 
                        Form::submit('Değişiklikleri Kaydet', [
                            'data-ajax-loading-text' => 'Kaydediliyor...'
                        ]); 
                        ?>
                    </div>

                    <?php Form::close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Ana layout'un footer'ını dahil et
view('partials.footer'); 
?>