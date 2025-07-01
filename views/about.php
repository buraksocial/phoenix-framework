<?php 
// Header parçasını dahil et ve sayfa başlığını gönder
view('partials.header', ['pageTitle' => $pageTitle ?? 'Hakkımızda']); 
?>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Phoenix Framework Hakkında</h1>
                <p class="lead text-muted">Saf PHP'nin gücünü modern mimari desenlerle birleştiren bir başlangıç noktası.</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2>Felsefemiz</h2>
                    <p>Phoenix Framework, büyük ve karmaşık framework'lerin getirdiği öğrenme eğrisi ve ağırlık olmadan, profesyonel ve ölçeklenebilir web uygulamaları geliştirmeyi mümkün kılmak amacıyla doğmuştur. Temel felsefemiz, geliştiriciye tam kontrol sunarken, tekrar eden ve sıkıcı işleri otomatize ederek geliştirme sürecini keyifli hale getirmektir.</p>
                    <p>Bu proje, bir yapay zeka ve bir insanın iş birliğiyle, sıfırdan, her bir parçasının mantığı tartışılarak inşa edilmiştir.</p>
                </div>
                <div class="col-md-6">
                    <h2>Temel Özellikler</h2>
                    <ul class="list-unstyled">
                        <li><strong class="text-primary">✓</strong> Modüler Mimari</li>
                        <li><strong class="text-primary">✓</strong> Dependency Injection Konteyneri</li>
                        <li><strong class="text-primary">✓</strong> Çoklu Veritabanı Desteği</li>
                        <li><strong class="text-primary">✓</strong> Gelişmiş Rota ve Oturum Yönetimi</li>
                        <li><strong class="text-primary">✓</strong> Bildirimsel Ajax Motoru</li>
                        <li><strong class="text-primary">✓</strong> Komut Satırı Arayüzü (Artisan)</li>
                    </ul>
                </div>
            </div>

            <hr class="my-5">

            <div class="text-center">
                <h2>Proje Ekibi</h2>
                <p class="text-muted">Bu projenin hayata geçmesini sağlayanlar.</p>
                <div class="row mt-4">
                    <?php if (!empty($teamMembers)): ?>
                        <?php foreach ($teamMembers as $member): ?>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?= htmlspecialchars($member['name']) ?></h5>
                                        <p class="card-text text-muted"><?= htmlspecialchars($member['role']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
// Footer parçasını dahil et
view('partials.footer'); 
?>