<?php 
use Core\Form;

// Ana layout'un header'ını dahil et
view('partials.header', ['pageTitle' => $pageTitle ?? 'Yeni Yazı Ekle']); 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1><?= htmlspecialchars($pageTitle ?? 'Yeni Yazı Ekle') ?></h1>
                </div>
                <div class="card-body">
                    
                    <?php
                    // Formu aç. Ajax ile gönderim için data attribute'ları ekliyoruz.
                    Form::open('/blog/posts', 'POST', [
                        'data-ajax-trigger' => 'submit',
                        'data-ajax-success-action' => 'toast|redirect',
                        // Sunucudan gelen yanıtta yönlendirme URL'si olmalı
                        // Örnek: json_success('Yazı oluşturuldu', ['redirect_url' => '/blog']);
                        'data-ajax-redirect-url' => '/blog', 
                    ]);
                    ?>

                    <?php 
                    // Form::text() yardımcısı:
                    // - <label> oluşturur.
                    // - Hata durumunda eski değeri (old('title')) otomatik olarak value'ya yazar.
                    // - Validasyon hatası varsa 'is-invalid' class'ı ekler ve hata mesajını gösterir.
                    Form::text('title', 'Yazı Başlığı', null, ['required' => true]); 
                    ?>

                    <?php 
                    // Form::textarea() yardımcısı da aynı akıllı özelliklere sahiptir.
                    Form::textarea('body', 'Yazı İçeriği', null, ['rows' => 10, 'required' => true]);
                    ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/blog" class="btn btn-secondary">İptal</a>
                        <?php 
                        // Form::submit() yardımcısı, butonu ve loading metnini oluşturur.
                        Form::submit('Yazıyı Kaydet', [
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