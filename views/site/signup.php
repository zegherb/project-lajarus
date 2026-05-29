<?php
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Buat Akun Baru - LAJARUS';

// LOAD LUCIDE
$this->registerJsFile(
    'https://unpkg.com/lucide@latest',
    ['position' => View::POS_HEAD]
);

$this->registerJs(
    'lucide.createIcons();',
    View::POS_READY
);

// CUSTOM CSS
$this->registerCss("
.register-wrapper{
    min-height: calc(100vh - 160px);

    background: linear-gradient(
        135deg,
        #f0f4f8 0%,
        #ffffff 50%,
        #fff4e6 100%
    );
}

.register-container{
    width: 100%;
    max-width: 460px;
}

.register-card{
    background: #fff;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
}

.form-control{
    height: 48px;
    border-radius: 12px;
    border: 1px solid #dee2e6;
}

.form-control:focus{
    box-shadow: none;
    border-color: #0d6efd;
}

.btn-primary{
    height: 48px;
    border-radius: 12px;
    font-weight: 600;
}

.logo-box{
    width: 48px;
    height: 48px;
}

@media (max-width: 576px){

    .register-wrapper{
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .register-card{
        padding: 1.5rem;
        border-radius: 20px;
    }

    .register-container{
        max-width: 100%;
    }
}
");
?>

<div class="register-wrapper d-flex align-items-center justify-content-center py-5 px-3">

    <div class="register-container">

        <!-- LOGO -->
        <div class="text-center mb-4">

            <a href="<?= Url::to(['site/index']) ?>"
               class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">

                <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center shadow logo-box">

                    <span class="text-white fs-4 fw-bold">
                        L
                    </span>

                </div>

                <span class="fs-4 fw-bold text-dark">
                    LAJARUS
                </span>

            </a>

            <h1 class="h3 fw-bold text-dark mb-1">
                Buat Akun Baru
            </h1>

            <p class="text-secondary mb-0">
                Bergabung untuk melaporkan jalan rusak
            </p>

        </div>

        <!-- CARD -->
        <div class="register-card">

            <!-- ALERT -->
            <div id="alert-container"></div>

            <form id="register-form" method="post">

                <!-- CSRF -->
                <input
                    type="hidden"
                    name="_csrf"
                    value="<?= Yii::$app->request->getCsrfToken() ?>">

                <!-- NAMA -->
                <div class="mb-3">

                    <label class="form-label small fw-medium text-dark">
                        Nama Lengkap
                    </label>

                    <div class="position-relative">

                        <i
                            data-lucide="user"
                            class="position-absolute top-50 translate-middle-y text-secondary"
                            style="
                                left: 1rem;
                                width: 20px;
                                height: 20px;
                            ">
                        </i>

                        <input
                            type="text"
                            name="name"
                            placeholder="John Doe"
                            class="form-control bg-white"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- EMAIL -->
                <div class="mb-3">

                    <label class="form-label small fw-medium text-dark">
                        Email
                    </label>

                    <div class="position-relative">

                        <i
                            data-lucide="mail"
                            class="position-absolute top-50 translate-middle-y text-secondary"
                            style="
                                left: 1rem;
                                width: 20px;
                                height: 20px;
                            ">
                        </i>

                        <input
                            type="email"
                            name="email"
                            placeholder="nama@email.com"
                            class="form-control bg-white"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- PHONE -->
                <div class="mb-3">

                    <label class="form-label small fw-medium text-dark">
                        Nomor Telepon
                    </label>

                    <div class="position-relative">

                        <i
                            data-lucide="phone"
                            class="position-absolute top-50 translate-middle-y text-secondary"
                            style="
                                left: 1rem;
                                width: 20px;
                                height: 20px;
                            ">
                        </i>

                        <input
                            type="tel"
                            name="phone"
                            placeholder="08123456789"
                            class="form-control bg-white"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- PASSWORD -->
                <div class="mb-3">

                    <label class="form-label small fw-medium text-dark">
                        Password
                    </label>

                    <div class="position-relative">

                        <i
                            data-lucide="lock"
                            class="position-absolute top-50 translate-middle-y text-secondary"
                            style="
                                left: 1rem;
                                width: 20px;
                                height: 20px;
                            ">
                        </i>

                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="form-control bg-white"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="mb-3">

                    <label class="form-label small fw-medium text-dark">
                        Konfirmasi Password
                    </label>

                    <div class="position-relative">

                        <i
                            data-lucide="lock"
                            class="position-absolute top-50 translate-middle-y text-secondary"
                            style="
                                left: 1rem;
                                width: 20px;
                                height: 20px;
                            ">
                        </i>

                        <input
                            type="password"
                            name="confirmPassword"
                            placeholder="••••••••"
                            class="form-control bg-white"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- TERMS -->
                <div class="form-check d-flex align-items-start gap-2 mb-4">

                    <input
                        type="checkbox"
                        class="form-check-input mt-1"
                        id="terms"
                        required>

                    <label
                        class="form-check-label small text-secondary"
                        for="terms">

                        Saya setuju dengan

                        <a href="#"
                           class="text-decoration-none">

                            syarat & ketentuan

                        </a>

                        dan

                        <a href="#"
                           class="text-decoration-none">

                            kebijakan privasi

                        </a>

                    </label>

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">

                    Daftar

                    <i
                        data-lucide="arrow-right"
                        style="
                            width: 20px;
                            height: 20px;
                        ">
                    </i>

                </button>

            </form>

            <!-- LOGIN -->
            <div class="mt-4 text-center">

                <p class="text-secondary mb-0">

                    Sudah punya akun?

                    <a href="<?= Url::to(['site/login']) ?>"
                       class="text-decoration-none fw-medium">

                        Masuk di sini

                    </a>

                </p>

            </div>

        </div>

        <!-- BACK -->
        <div class="mt-4 text-center">

            <a href="<?= Url::to(['site/index']) ?>"
               class="text-secondary text-decoration-none">

                &larr; Kembali ke beranda

            </a>

        </div>

    </div>

</div>

<?php

// API URL
$apiUrl = Url::to(['api/register']);
$dashboardUrl = Url::to(['user/dashboard']);

$js = <<<JS

$('#register-form').on('submit', function(e) {

    e.preventDefault();

    var form = $(this);

    var alertContainer =
        $('#alert-container');

    alertContainer.html('');

    var formDataArray =
        form.serializeArray();

    var data = {};

    $.each(formDataArray, function(i, field) {

        data[field.name] = field.value;

    });

    $.ajax({

        url: '$apiUrl',

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify(data),

        headers: {

            'X-CSRF-Token':
                $('meta[name="csrf-token"]').attr('content')
        },

        success: function(result) {

            if(result.status === 'success') {

                alertContainer.html(
                    '<div class="alert alert-success">'
                    + result.message +
                    '</div>'
                );

                setTimeout(function(){

                    window.location.href =
                        '$dashboardUrl';

                }, 1500);
            }
        },

        error: function(xhr) {

            var errorMessages = '';

            var response =
                xhr.responseJSON;

            if(response && response.errors){

                Object.values(response.errors)
                    .forEach(function(err){

                    errorMessages +=
                        '<li>' + err + '</li>';

                });

            } else {

                errorMessages =
                    '<li>' +
                    (
                        response
                        ? response.message
                        : 'Terjadi kesalahan sistem eksternal.'
                    ) +
                    '</li>';
            }

            alertContainer.html(
                '<div class="alert alert-danger">' +
                '<ul class="mb-0">' +
                errorMessages +
                '</ul></div>'
            );
        }
    });
});

JS;

$this->registerJs($js);

?>