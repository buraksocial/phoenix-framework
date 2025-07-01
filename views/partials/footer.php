</main>

<footer class="py-4 mt-auto bg-light">
    <div class="container">
        <p class="m-0 text-center text-muted">
            Copyright &copy; <?= config('app.name', 'Phoenix Framework') ?> <?= date('Y') ?>
        </p>
    </div>
</footer>

<?php
// Sadece geliştirme ortamında Debug Bar'ı render et
if (config('app.env') === 'development' && class_exists(\App\Services\DebugService::class)) {
    try {
        echo app(\App\Services\DebugService::class)->render();
    } catch (\Throwable $e) {
        // Debugger başlatılamadıysa hatayı sessizce logla
        error_log('Debug Bar render edilemedi: ' . $e->getMessage());
    }
}
?>

</body>
</html>
