<?php
use yii\helpers\Html;
?>

<footer class="bg-dark text-white py-3 mt-auto">
    <div class="container">

        <div class="row gy-3 align-items-start">

            <!-- LOGO -->
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-primary rounded d-flex align-items-center justify-content-center"
                         style="width: 34px; height: 34px">
                        <span class="text-white fw-bold">L</span>
                    </div>

                    <span class="fw-bold">LAJARUS</span>
                </div>

                <p class="text-white-50 small mb-0">
                    Sistem Pelaporan Jalan Rusak Sulawesi Tenggara
                </p>
            </div>

            <!-- LINK -->
            <div class="col-md-4">
                <h6 class="fw-bold mb-2">Tautan</h6>

                <ul class="list-unstyled mb-0 small">
                    <li class="mb-1">
                        <?= Html::a('Masuk', ['site/login'], [
                            'class' => 'text-white-50 text-decoration-none'
                        ]) ?>
                    </li>

                    <li class="mb-1">
                        <?= Html::a('Daftar', ['site/signup'], [
                            'class' => 'text-white-50 text-decoration-none'
                        ]) ?>
                    </li>

                    <li class="mb-1">
                        <?= Html::a('Tentang Kami', ['site/about'], [
                            'class' => 'text-white-50 text-decoration-none'
                        ]) ?>
                    </li>

                    <li>
                        <?= Html::a('Kontak', ['site/contact'], [
                            'class' => 'text-white-50 text-decoration-none'
                        ]) ?>
                    </li>
                </ul>
            </div>

            <!-- KONTAK -->
            <div class="col-md-4">
                <h6 class="fw-bold mb-2">Kontak</h6>

                <ul class="list-unstyled text-white-50 small mb-0">
                    <li>Email: info@lajarus.id</li>
                    <li>Telp: (021) 1234-5678</li>
                    <li>Kendari, Sulawesi Tenggara</li>
                </ul>
            </div>
        </div>

        <!-- BOTTOM -->
        <div class="border-top border-secondary mt-3 pt-2 d-flex flex-column flex-md-row justify-content-between align-items-center">

            <p class="small text-white-50 mb-2 mb-md-0">
                &copy; <?= date('Y') ?> LAJARUS
            </p>

            <button id="theme-toggle"
                    class="btn btn-sm text-white-50 border-0 d-flex align-items-center gap-1"
                    style="background: rgba(255,255,255,0.08);">

                <span id="theme-icon">☀️</span>
                <span id="theme-text" class="small">
                    Mode Terang
                </span>

            </button>
        </div>

    </div>
</footer>

<?php
$this->registerJs(<<<JS
const toggleBtn = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const themeText = document.getElementById('theme-text');
const htmlElement = document.documentElement;

const currentTheme = localStorage.getItem('lajarus_theme') || 'light';

htmlElement.setAttribute('data-bs-theme', currentTheme);

updateButtonUI(currentTheme);

toggleBtn.addEventListener('click', () => {

    const currentTheme =
        htmlElement.getAttribute('data-bs-theme');

    const targetTheme =
        currentTheme === 'light'
            ? 'dark'
            : 'light';

    htmlElement.setAttribute(
        'data-bs-theme',
        targetTheme
    );

    localStorage.setItem(
        'lajarus_theme',
        targetTheme
    );

    updateButtonUI(targetTheme);
});

function updateButtonUI(theme) {

    if (theme === 'dark') {

        themeIcon.textContent = '☀️';
        themeText.textContent = 'Mode Terang';

    } else {

        themeIcon.textContent = '🌙';
        themeText.textContent = 'Mode Gelap';
    }
}
JS);
?>