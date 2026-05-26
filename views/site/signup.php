<?php
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Buat Akun Baru - LAJARUS';

// Load Lucide Icons untuk kebutuhan ikon dinamis
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);
?>

<div class="vh-100 d-flex align-items-center justify-content-center p-3" style="background: linear-gradient(135deg, #f0f4f8 0%, #ffffff 50%, #fff4e6 100%)">
    <div class="w-100" style="max-width: 450px;">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="<?= Url::to(['site/index']) ?>" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
                <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center shadow" style="width: 48px; height: 48px">
                    <span class="text-white fs-4 fw-bold">L</span>
                </div>
                <span class="fs-4 fw-bold text-dark">LAJARUS</span>
            </a>
            <h1 class="h3 fw-bold text-dark mb-1">Buat Akun Baru</h1>
            <p class="text-secondary">Bergabung untuk melaporkan jalan rusak</p>
        </div>

        <!-- Register Form Card -->
        <div class="bg-white rounded-4 shadow-lg p-4 p-sm-5">
            <!-- Tempat menampilkan alert status via JavaScript -->
            <div id="alert-container"></div>

            <form id="register-form" method="post">
                 <!-- CSRF Token -->
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <div class="mb-3">
                    <label class="form-label small fw-medium text-dark">Nama Lengkap</label>
                    <div class="position-relative">
                        <i data-lucide="user" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="text" name="name" placeholder="John Doe" class="form-control py-2 bg-white" style="padding-left: 2.75rem" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium text-dark">Email</label>
                    <div class="position-relative">
                        <i data-lucide="mail" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="email" name="email" placeholder="nama@email.com" class="form-control py-2 bg-white" style="padding-left: 2.75rem" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium text-dark">Nomor Telepon</label>
                    <div class="position-relative">
                        <i data-lucide="phone" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="tel" name="phone" placeholder="08123456789" class="form-control py-2 bg-white" style="padding-left: 2.75rem" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium text-dark">Password</label>
                    <div class="position-relative">
                        <i data-lucide="lock" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="password" name="password" placeholder="••••••••" class="form-control py-2 bg-white" style="padding-left: 2.75rem" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium text-dark">Konfirmasi Password</label>
                    <div class="position-relative">
                        <i data-lucide="lock" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                        <input type="password" name="confirmPassword" placeholder="••••••••" class="form-control py-2 bg-white" style="padding-left: 2.75rem" required>
                    </div>
                </div>

                <div class="form-check d-flex align-items-start gap-2 mb-4">
                    <input type="checkbox" class="form-check-input mt-1" id="terms" required>
                    <label class="form-check-label small text-secondary" htmlFor="terms">
                        Saya setuju dengan <a href="#" class="text-decoration-none">syarat & ketentuan</a> dan <a href="#" class="text-decoration-none">kebijakan privasi</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                    Daftar <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-secondary mb-0">
                    Sudah punya akun? <a href="<?= Url::to(['site/login']) ?>" class="text-decoration-none fw-medium">Masuk di sini</a>
                </p>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="<?= Url::to(['site/index']) ?>" class="text-secondary text-decoration-none">&larr; Kembali ke beranda</a>
        </div>
    </div>
</div>

<?php
// Ambil URL Endpoint API dan URL Redirect setelah sukses
$apiUrl = Url::to(['api/register']);
$dashboardUrl = Url::to(['user/dashboard']); 

$js = <<<JS
$('#register-form').on('submit', function(e) {
    // 1. WAJIB: Cegat form agar tidak melakukan submit normal / refresh halaman
    e.preventDefault();
    
    var form = $(this);
    var alertContainer = $('#alert-container');
    alertContainer.html(''); // Bersihkan pesan error sebelumnya

    // 2. Ambil semua data dari inputan form dan ubah menjadi objek
    var formDataArray = form.serializeArray();
    var data = {};
    $.each(formDataArray, function(i, field) {
        data[field.name] = field.value;
    });

    // 3. Kirim data menggunakan JQuery AJAX ke REST API
    $.ajax({
        url: '$apiUrl',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        headers: {
            // Mengambil CSRF Token dari meta tag utama Yii2
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(result) {
            if (result.status === 'success') {
                alertContainer.html('<div class="alert alert-success">' + result.message + '</div>');
                // Redirect ke beranda/dashboard setelah 1.5 detik
                setTimeout(function() {
                    window.location.href = '$dashboardUrl';
                }, 1500);
            }
        },
        error: function(xhr) {
            var errorMessages = '';
            var response = xhr.responseJSON;

            // Jika ada pesan error validasi dari model Yii2
            if (response && response.errors) {
                Object.values(response.errors).forEach(function(err) {
                    errorMessages += '<li>' + err + '</li>';
                });
            } else {
                errorMessages = '<li>' + (response ? response.message : 'Terjadi kesalahan sistem eksternal.') + '</li>';
            }
            
            alertContainer.html('<div class="alert alert-danger"><ul class="mb-0">' + errorMessages + '</ul></div>');
        }
    });
});
JS;
// Registrasi script agar ditaruh Yii di akhir dokumen sebelum </body>
$this->registerJs($js);
?>