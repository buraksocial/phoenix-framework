<?php 
// Ana layout'un header'ını dahil et
// $post->title'ı sayfa başlığı olarak gönderiyoruz
view('partials.header', ['pageTitle' => $post->title ?? 'Blog Yazısı']); 
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <article>
                <header class="mb-4">
                    <h1 class="fw-bolder mb-1"><?= htmlspecialchars($post->title) ?></h1>
                    <div class="text-muted fst-italic mb-2">
                        Yayınlanma tarihi: <?= date('d F Y', strtotime($post->created_at)) ?>
                        </div>
                    </header>
                <section class="mb-5">
                    <p class="fs-5 mb-4">
                        <?= nl2br(htmlspecialchars($post->body)) ?>
                    </p>
                </section>
            </article>

            </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Eylemler</div>
                <div class="card-body">
                    <a href="/blog/posts/<?= $post->id ?>/edit" class="btn btn-warning">Bu Yazıyı Düzenle</a>
                    <form action="/blog/posts/<?= $post->id ?>/delete" method="POST" class="d-inline mt-2"
                          data-ajax-trigger="submit"
                          data-ajax-confirm="Bu yazıyı kalıcı olarak silmek istediğinizden emin misiniz?"
                          data-ajax-success-action="toast|redirect"
                          data-ajax-redirect-url="/blog">
                        <button type="submit" class="btn btn-danger" data-ajax-loading-text="Siliniyor...">
                            Yazıyı Sil
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Ara</div>
                <div class="card-body">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Arama yap..." />
                        <button class="btn btn-primary" type="button">Ara!</button>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Kategoriler</div>
                <div class="card-body">
                    </div>
            </div>
        </div>
    </div>
    
    <div class="my-4">
        <a href="/blog" class="btn btn-secondary">&larr; Tüm Yazılara Geri Dön</a>
    </div>
</div>

<?php 
// Ana layout'un footer'ını dahil et
view('partials.footer'); 
?>