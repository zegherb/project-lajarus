<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Masuk - LAJARUS';

// Load Lucide Icons
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
.login-wrapper{
    min-height: calc(100vh - 160px);
    background: linear-gradient(
        135deg,
        #f0f4f8 0%,
        #ffffff 50%,
        #fff4e6 100%
    );
}

.login-container{
    width: 100%;
    max-width: 430px;
}

.login-card{
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

    .login-wrapper{
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .login-card{
        padding: 1.5rem;
        border-radius: 20px;
    }

    .login-container{
        max-width: 100%;
    }
}
");
?>

<div class="login-wrapper d-flex align-items-center justify-content-center py-5 px-3">

    <div class="login-container">

        <!-- LOGO -->
        <div class="text-center mb-4">

            <?= Html::a(
                '
                <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center shadow logo-box">
                    <span class="text-white fs-4 fw-bold">L</span>
                </div>

                <span class="fs-4 fw-bold text-dark ms-2">
                    LAJARUS
                </span>
                ',
                ['site/index'],
                [
                    'class' =>
                        'd-inline-flex align-items-center text-decoration-none mb-3'
                ]
            ) ?>

            <h1 class="h3 fw-bold text-dark mb-1">
                Selamat Datang
            </h1>

            <p class="text-secondary mb-0">
                Masuk ke akun Anda
            </p>

        </div>

        <!-- LOGIN CARD -->
        <div class="login-card">

            <div id="alert-container"></div>

            <form id="login-form">

                <!-- EMAIL -->
                <div class="mb-4">

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
                            class="form-control bg-white text-dark"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- PASSWORD -->
                <div class="mb-4">

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
                            class="form-control bg-white text-dark"
                            style="padding-left: 2.75rem"
                            required>

                    </div>

                </div>

                <!-- REMEMBER -->
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div class="form-check mb-0 d-flex align-items-center gap-2">

                        <input
                            type="checkbox"
                            name="rememberMe"
                            class="form-check-input mt-0"
                            id="rememberMe"
                            value="1">

                        <label
                            class="form-check-label small text-secondary"
                            for="rememberMe">

                            Ingat saya

                        </label>

                    </div>

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">

                    Masuk

                    <i
                        data-lucide="arrow-right"
                        style="
                            width: 20px;
                            height: 20px;
                        ">
                    </i>

                </button>

            </form>

            <!-- REGISTER -->
            <div class="mt-4 text-center">

                <p class="text-secondary mb-0">

                    Belum punya akun?

                    <?= Html::a(
                        'Daftar sekarang',
                        ['site/signup'],
                        [
                            'class' =>
                                'text-decoration-none fw-medium'
                        ]
                    ) ?>

                </p>

            </div>

            <!-- ADMIN -->
            <div class="mt-4 pt-4 border-top text-center">

                <p class="small text-secondary mb-2">
                    Atau masuk sebagai
                </p>

                <?= Html::a(
                    'Admin',
                    ['admin/login'],
                    [
                        'class' =>
                            'text-warning text-decoration-none fw-medium'
                    ]
                ) ?>

            </div>

        </div>

        <!-- BACK -->
        <div class="mt-4 text-center">

            <?= Html::a(
                '&larr; Kembali ke beranda',
                ['site/index'],
                [
                    'class' =>
                        'text-secondary text-decoration-none'
                ]
            ) ?>

        </div>

    </div>

</div>

<?php

// AJAX REST API
$apiUrl = Url::to(['api/login']);
$dashboardUrl = Url::to(['user/dashboard']);

$js = <<<JS

document
    .getElementById('login-form')
    .addEventListener('submit', async function(e){

    e.preventDefault();

    const formData = new FormData(this);

    const data =
        Object.fromEntries(formData.entries());

    const alertContainer =
        document.getElementById('alert-container');

    try {

        const response = await fetch('$apiUrl', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',

                'X-CSRF-Token':
                    document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },

            body: JSON.stringify(data)
        });

        const result = await response.json();

        if(response.ok){

            window.location.href =
                '$dashboardUrl';

        } else {

            alertContainer.innerHTML =
                `<div class="alert alert-danger mb-3">
                    \${
                        result.errors
                        ? Object.values(result.errors)[0]
                        : 'Login gagal'
                    }
                </div>`;
        }

    } catch(err){

        alertContainer.innerHTML =
            '<div class="alert alert-danger mb-3">Server error</div>';
    }
});

JS;

$this->registerJs($js);

?>