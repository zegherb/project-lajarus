<?php
use yii\helpers\Html;
use yii\helpers\Url;

$profileLink = ['/user/profile'];
if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin') {
    $profileLink = ['/admin/profile'];
}

// LOGIKA PINTAR: Deteksi apakah ini Halaman Dashboard atau Halaman Publik (Landing Page)
$isDashboard = (Yii::$app->controller->id !== 'site');

// CSS Navbar digeser hanya jika di dalam Dashboard
if ($isDashboard) {
    $css = <<<CSS
    @media (min-width: 992px) { 
        .navbar-adjust { margin-left: 256px; } 
    }
    CSS;
    $this->registerCss($css);
}
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm <?= $isDashboard ? 'navbar-adjust' : '' ?>">
    <div class="container-fluid px-4">

        <!-- KIRI: Hamburger & Logo -->
        <div class="d-flex align-items-center gap-3">

            <!-- HAMBURGER MENU DINAMIS -->
            <?php if ($isDashboard): ?>
                <!-- Hamburger untuk Dashboard (Memicu JS custom _sidebar.php) -->
                <button class="btn btn-light d-lg-none p-2" type="button" id="hamburger-menu-toggle">
                    <i data-lucide="menu" style="width:22px; height:22px;"></i>
                </button>
            <?php else: ?>
                <!-- Hamburger untuk Landing Page (Memicu Offcanvas Bootstrap) -->
                <button class="btn btn-light d-lg-none p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#publicMobileMenu">
                    <i data-lucide="menu" style="width:22px; height:22px;"></i>
                </button>
            <?php endif; ?>

            <!-- LOGO (Teks LAJARUS dijamin selalu tampil!) -->
            <a href="<?= Url::to(['/site/index']) ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                    <span class="text-white fw-bold">L</span>
                </div>
                <span class="fs-5 fw-bold text-dark">LAJARUS</span>
            </a>
        </div>

        <!-- KANAN: Menu Akun -->
        <!-- Sembunyikan di Mobile Landing Page (karena pindah ke dalam Offcanvas), Tetap Tampilkan di Mobile Dashboard -->
        <div class="<?= !$isDashboard ? 'd-none d-lg-flex' : 'd-flex' ?> align-items-center gap-2">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Masuk', ['/site/login'], ['class' => 'text-dark text-decoration-none px-2 py-2 small fw-medium']) ?>
                <?= Html::a('Daftar', ['/site/signup'], ['class' => 'btn btn-primary btn-sm px-3 py-2']) ?>
            <?php else: ?>
                <?= Html::a(
                    '<i data-lucide="user" style="width:18px; height:18px;"></i> <span class="d-none d-sm-inline ms-1">Profil</span>',
                    $profileLink,
                    ['class' => 'btn btn-light btn-sm d-flex align-items-center py-2']
                ) ?>

                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'mb-0']) ?>
                    <?= Html::submitButton(
                        '<i data-lucide="log-out" style="width:18px; height:18px;"></i> <span class="d-none d-sm-inline ms-1">Keluar</span>',
                        ['class' => 'btn btn-outline-danger btn-sm d-flex align-items-center py-2']
                    ) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
        
    </div>
</nav>

<!-- MENU GESER (OFFCANVAS) KHUSUS LANDING PAGE MOBILE -->
<?php if (!$isDashboard): ?>
<div class="offcanvas offcanvas-start" tabindex="-1" id="publicMobileMenu">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px;">
                <span class="text-white fw-bold">L</span>
            </div>
            <h5 class="offcanvas-title fw-bold mb-0">LAJARUS</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
            <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-light text-start d-flex align-items-center gap-2 fw-medium">
                <i data-lucide="home" style="width:18px; height:18px;"></i> Beranda
            </a>
            <hr class="my-1 border-secondary opacity-25">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Masuk Akun', ['/site/login'], ['class' => 'btn btn-outline-primary w-100 text-start fw-medium']) ?>
                <?= Html::a('Daftar Baru', ['/site/signup'], ['class' => 'btn btn-primary w-100 text-start fw-medium']) ?>
            <?php else: ?>
                <?= Html::a('<i data-lucide="user" style="width:18px; height:18px;"></i> Profil Saya', $profileLink, ['class' => 'btn btn-light w-100 d-flex align-items-center gap-2 fw-medium text-start']) ?>
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'w-100']) ?>
                    <?= Html::submitButton('<i data-lucide="log-out" style="width:18px; height:18px;"></i> Keluar', ['class' => 'btn btn-outline-danger w-100 d-flex align-items-center gap-2 fw-medium text-start']) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>