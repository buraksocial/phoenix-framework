<?php 
// Header parçasını dahil et ve sayfa başlığını gönder
view('partials.header', ['pageTitle' => $pageTitle ?? 'Ana Sayfa']); 
?>

<div class="container">
    
    <div class="p-5 mb-4 bg-light rounded-3 text-center">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($welcomeMessage ?? 'Phoenix Framework\'e Hoş Geldiniz!') ?></h1>
            <p class="fs-4">Saf PHP üzerine inşa edilmiş, modern, modüler ve geliştirici dostu bu iskelet ile projelerinizi hayata geçirin.</p>
            <a href="/about" class="btn btn-primary btn-lg">Daha Fazla Bilgi</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Son Yazılar</h2>
        </div>
        
        <?php if (empty($posts)): ?>
            <div class="col-12">
                <p>Henüz gösterilecek bir yazı yok.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($post->title) ?></h5>
                            <p class="card-text flex-grow-1">
                                <?= htmlspecialchars(mb_substr($post->body, 0, 100)) ?>...
                            </p>
                            <a href="/blog/posts/<?= htmlspecialchars($post->slug) ?>" class="btn btn-secondary mt-auto">Devamını Oku</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php 
// Footer parçasını dahil et
view('partials.footer'); 
?>