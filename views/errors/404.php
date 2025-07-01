<?php 
// Header parçasını dahil et ve sayfa başlığını gönder
view('partials.header', ['pageTitle' => '404 - Sayfa Bulunamadı']); 
?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 text-center py-5">
            
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3"> <span class="text-danger">Eyvah!</span> Sayfa Bulunamadı.</p>
            <p class="lead">
                Aradığınız sayfa mevcut değil, taşınmış veya silinmiş olabilir.
            </p>
            <a href="/" class="btn btn-primary mt-3">Ana Sayfaya Dön</a>

        </div>
    </div>
</div>

<?php 
// Footer parçasını dahil et
view('partials.footer'); 
?>
