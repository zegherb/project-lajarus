<?php
use yii\helpers\Url;

$userType = !Yii::$app->user->isGuest ? Yii::$app->user->identity->role : 'user';
$currentRoute = '/' . Yii::$app->controller->route;

$userMenuItems = [
    ['icon' => 'home', 'label' => 'Dashboard', 'path' => '/user/dashboard'],
    ['icon' => 'file-text', 'label' => 'Laporan Saya', 'path' => '/user/reports'],
    ['icon' => 'plus', 'label' => 'Buat Laporan', 'path' => '/user/new-report'],
    ['icon' => 'history', 'label' => 'Aktivitas', 'path' => '/user/aktivitas'],
    ['icon' => 'user', 'label' => 'Profil', 'path' => '/user/profile'],
];

$adminMenuItems = [
    ['icon' => 'home', 'label' => 'Dashboard', 'path' => '/admin/dashboard'],
    ['icon' => 'bar-chart-3', 'label' => 'Statistik', 'path' => '/admin/statistik'],
    ['icon' => 'file-text', 'label' => 'Semua Laporan', 'path' => '/admin/reports'],
    ['icon' => 'history', 'label' => 'Log Aktivitas', 'path' => '/admin/aktivitas'],
    ['icon' => 'users', 'label' => 'Pengguna', 'path' => '/admin/users'],
    ['icon' => 'user', 'label' => 'Profil', 'path' => '/admin/profile'],
];

$menuItems = $userType === 'admin' ? $adminMenuItems : $userMenuItems;

$css = <<<CSS
.custom-sidebar {
    width: 256px; 
    z-index: 1060; /* Naikkan tingkatan z-index agar berada di atas navbar lengket saat mobile */
    transition: transform 0.3s ease-in-out;
}

@media (max-width: 991.98px) {
    .custom-sidebar { transform: translateX(-100%); }
    .custom-sidebar.open { transform: translateX(0) !important; }
}

@media (min-width: 992px) {
    .custom-sidebar { transform: translateX(0) !important; }
}

.nav-link-custom { transition: all 0.2s; }
.nav-link-custom:hover:not(.active) { background-color: #f8f9fa; }
CSS;
$this->registerCss($css);
?>

<div id="sidebar-overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none" style="z-index: 1050; cursor: pointer;"></div>

<aside id="custom-sidebar" class="custom-sidebar position-fixed top-0 start-0 h-100 bg-white border-end shadow-sm d-flex flex-column">
    
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom" style="height: 60px;">
        <a href="<?= Url::to(['/site/index']) ?>" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="bg-primary d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px">
                <span class="text-white fw-bold">L</span>
            </div>
            <span class="fw-bold text-dark fs-5">
                LAJARUS <?= strtoupper($userType) ?>
            </span>
        </a>

        <button id="sidebar-close" class="btn btn-light btn-sm d-lg-none d-flex align-items-center justify-content-center p-1">
            <i data-lucide="x" class="text-secondary" style="width: 20px; height: 20px;"></i>
        </button>
    </div>

    <div class="flex-grow-1 p-3 overflow-y-auto">
        <ul class="nav flex-column gap-2">
            <?php foreach ($menuItems as $item): ?>
                <?php $isActive = ($currentRoute === $item['path']); ?>
                <li class="nav-item">
                    <a href="<?= Url::to([trim($item['path'], '/')]) ?>" 
                       class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 fw-medium nav-link-custom <?= $isActive ? 'bg-primary text-white active shadow-sm' : 'text-secondary' ?>">
                        <i data-lucide="<?= $item['icon'] ?>" style="width: 20px; height: 20px;"></i>
                        <span><?= $item['label'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="border-top p-3 bg-light mt-auto">
        <small class="text-secondary fw-bold d-block mb-1">
            <?= $userType === 'admin' ? 'Admin Panel' : 'Portal Pengguna' ?>
        </small>
        <small class="text-black-50" style="font-size: 0.75rem;">
            &copy; <?= date('Y') ?> LAJARUS v1.0
        </small>
    </div>
</aside>

<?php
$js = <<<JS
$(document).ready(function() {
    // Jalankan trigger buka menu berdasarkan klik tombol hamburger di _header.php
    $(document).on('click', '#hamburger-menu-toggle', function(e) {
        e.preventDefault();
        $('#custom-sidebar').addClass('open');
        $('#sidebar-overlay').removeClass('d-none');
    });

    // Jalankan fungsi tutup menu kembali
    $(document).on('click', '#sidebar-close, #sidebar-overlay', function() {
        $('#custom-sidebar').removeClass('open');
        $('#sidebar-overlay').addClass('d-none');
    });
});
JS;
$this->registerJs($js);
?>