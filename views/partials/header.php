<!DOCTYPE html>
<html lang="<?= config('app.locale', 'tr') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?><?= config('app.name', 'Phoenix') ?></title>
    
    <meta name="description" content="Phoenix Framework ile oluşturulmuş bir web sayfası.">
    
    <?= vite('public/assets/css/style.css', 'public/assets/js/app.js') ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4">
    <div class="container">
        <a class="navbar-brand" href="/"><?= config('app.name') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Hakkımızda</a>
                </li>
                <?php if (\Core\Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Kontrol Paneli</a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST">
                            <?php csrf_field(); ?>
                            <button type="submit" class="btn btn-link nav-link">Çıkış Yap (<?= \Core\Auth::user()->name ?>)</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Kayıt Ol</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
