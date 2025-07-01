<?php 
// Header parçasını dahil et ve sayfa başlığını gönder
view('partials.header', ['pageTitle' => '403 - Erişim Engellendi']); 
?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 text-center py-5">
            
            <h1 class="display-1 fw-bold">403</h1>
            <p class="fs-3"> <span class="text-danger">Erişim Engellendi</span></p>
            <p class="lead">
                Bu sayfayı görüntülemek için gerekli yetkilere sahip değilsiniz.
            </p>
            <a href="/" class="btn btn-primary mt-3">Ana Sayfaya Dön</a>

        </div>
    </div>
</div>

<?php 
// Footer parçasını dahil et
view('partials.footer'); 
?>
