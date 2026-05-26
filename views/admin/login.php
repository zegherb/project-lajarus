<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Admin Login - LAJARUS';

$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
    nav.navbar, footer { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; }
    .admin-bg { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0f172a 100%); min-height: 100vh; }
    .text-orange-custom { color: #f97316; }
    .bg-orange-custom { background-color: #f97316; }
    .bg-orange-custom:hover { background-color: #ea580c; }
    .border-orange-custom { border-color: rgba(249, 115, 22, 0.2); }
    .bg-input-dark { background-color: #f8f9fa; }
    .bg-input-dark:focus { border-color: #f97316; box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25); }
CSS;
$this->registerCss($css);
?>

<div class="admin-bg d-flex align-items-center justify-content-center p-3">
    <div class="w-100" style="max-width: 450px;">
        
        <div class="text-center mb-5">
            <a href="<?= Url::to(['/site/index']) ?>" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
                <div class="bg-orange-custom rounded-3 shadow-lg d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i data-lucide="shield" class="text-white" style="width: 28px; height: 28px;"></i>
                </div>
                <span class="fs-4 fw-bold text-white">LAJARUS Admin</span>
            </a>
            <h1 class="fs-2 fw-bold text-white mb-2">Admin Panel</h1>
            <p class="text-white-50">Masuk ke dashboard manajemen</p>
        </div>

        <div id="alert-container"></div>

        <div class="bg-white rounded-4 shadow-lg p-4 p-md-5" style="backdrop-filter: blur(8px);">
            
            <div class="d-flex align-items-start gap-3 border border-orange-custom rounded-3 p-3 mb-4" style="background-color: rgba(249, 115, 22, 0.05);">
                <i data-lucide="shield" class="text-orange-custom mt-1" style="width: 20px; height: 20px;"></i>
                <div>
                    <p class="small fw-bold text-dark mb-0">Area Khusus Admin</p>
                    <p class="text-secondary mb-0" style="font-size: 0.75rem;">Halaman ini hanya untuk administrator sistem.</p>
                </div>
            </div>

            <form id="admin-login-form" class="space-y-4">
                <div class="mb-4">
                    <label class="form-label small fw-medium text-dark">Email Admin</label>
                    <div class="position-relative">
                        <i data-lucide="mail" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="email" name="email" id="email" class="form-control bg-input-dark py-3" style="padding-left: 2.75rem;" placeholder="admin@lajarus.id" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-medium text-dark">Password</label>
                    <div class="position-relative">
                        <i data-lucide="lock" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="password" name="password" id="password" class="form-control bg-input-dark py-3" style="padding-left: 2.75rem;" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small text-secondary" for="rememberMe">Ingat saya</label>
                    </div>
                    <a href="#" class="text-decoration-none small text-orange-custom fw-medium">Lupa password?</a>
                </div>

                <button type="submit" class="btn bg-orange-custom text-white w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                    Masuk ke Admin Panel
                    <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                </button>
            </form>

            <div class="mt-5 pt-4 border-top text-center">
                <p class="small text-secondary mb-2">Bukan admin?</p>
                <a href="<?= Url::to(['/site/login']) ?>" class="text-primary text-decoration-none fw-medium small">Masuk sebagai Pengguna Biasa</a>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= Url::to(['/site/index']) ?>" class="text-white-50 text-decoration-none small hover-white">
                <i data-lucide="arrow-left" style="width: 14px; height: 14px; display: inline; margin-top: -2px;"></i> Kembali ke beranda
            </a>
        </div>
        
    </div>
</div>

<?php
$apiUrl = Url::to(['api/admin-login']);
$dashboardUrl = Url::to(['admin/dashboard']);

$js = <<<JS
$('#admin-login-form').on('submit', function(e) {
    e.preventDefault();
    
    var alertContainer = $('#alert-container');
    var submitBtn = $(this).find('button[type="submit"]');
    var originalBtnHtml = submitBtn.html();
    
    // Siapkan JSON data
    var dataPayload = {
        email: $('#email').val(),
        password: $('#password').val(),
        rememberMe: $('#rememberMe').is(':checked') ? true : false
    };

    alertContainer.empty();
    submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Memverifikasi...').prop('disabled', true);

    $.ajax({
        url: '$apiUrl',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(dataPayload),
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                alertContainer.html('<div class="alert alert-success d-flex align-items-center gap-2 shadow-sm mb-4"><i data-lucide="check-circle" style="width:20px;height:20px;"></i>' + response.message + '</div>');
                lucide.createIcons();
                setTimeout(function() {
                    window.location.href = '$dashboardUrl';
                }, 1000);
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
            alertContainer.html('<div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm mb-4"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i>' + msg + '</div>');
            lucide.createIcons();
            submitBtn.html(originalBtnHtml).prop('disabled', false);
        }
    });
});
JS;
$this->registerJs($js);
?>