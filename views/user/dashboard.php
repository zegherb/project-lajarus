<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard User - LAJARUS';

// Registrasi CSS khusus untuk halaman ini
$css = <<<CSS
@media (min-width: 992px) {
    .main-wrapper {
        padding-left: 256px; 
    }
}
.hover-shadow-md {
    transition: box-shadow 0.3s ease-in-out;
}
.hover-shadow-md:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}
.report-item {
    transition: background-color 0.2s;
}
.report-item:hover {
    background-color: #f8f9fa !important;
}
.report-item:last-child {
    border-bottom: 0 !important;
}
CSS;
$this->registerCss($css);
?>
<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container py-4 px-lg-4 pt-5">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                    <div><?= Yii::$app->session->getFlash('success') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="mb-4">
                <h1 class="fs-3 fw-bold text-dark mb-1">Dashboard</h1>
                <p class="text-secondary">Selamat datang kembali, <?= Html::encode($userName) ?>!</p>
            </div>

            <div class="mb-4">
                <a href="<?= Url::to(['user/new-report']) ?>" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm fw-medium">
                    <i data-lucide="plus" style="width: 20px; height: 20px;"></i> Buat Laporan Baru
                </a>
            </div>

            <div class="row g-4 mb-4">
                <?php foreach ($stats as $stat): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm p-4 rounded-3 hover-shadow-md bg-white">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="text-secondary small mb-1"><?= $stat['label'] ?></p>
                                    <h3 class="fw-bold text-dark mb-0"><?= $stat['value'] ?></h3>
                                </div>
                                <div class="d-flex align-items-center justify-content-center rounded <?= $stat['color'] ?>" style="width: 48px; height: 48px;">
                                    <i data-lucide="<?= $stat['icon'] ?>" class="text-white" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0">Laporan Terbaru</h5>
                    <a href="<?= Url::to(['user/reports']) ?>" class="text-primary text-decoration-none small fw-medium">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="d-flex flex-column">
                    <?php if (empty($recentReports)): ?>
                        <div class="p-5 text-center text-secondary bg-white">
                            <i data-lucide="file-text" class="text-black-50 mb-2" style="width: 48px; height: 48px;"></i>
                            <p class="mb-0">Belum ada laporan jalan rusak yang Anda buat.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentReports as $report): 
                            // Logika Pewarnaan Badge Status Otomatis
                            $badgeColor = 'bg-secondary-subtle text-secondary-emphasis';
                            if ($report->status == 'Selesai') $badgeColor = 'bg-success-subtle text-success-emphasis';
                            if ($report->status == 'Menunggu') $badgeColor = 'bg-warning-subtle text-warning-emphasis';
                            if ($report->status == 'Proses') $badgeColor = 'bg-primary-subtle text-primary-emphasis';
                        ?>
                            <div class="p-4 border-bottom bg-white report-item">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1"><?= Html::encode($report->judul) ?></h6>
                                        <p class="text-secondary small mb-3"><?= Html::encode($report->alamat_lokasi) ?></p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge rounded-pill fw-normal px-3 py-2 <?= $badgeColor ?>">
                                                <?= Html::encode($report->status) ?>
                                            </span>
                                            <span class="text-black-50 small" style="font-size: 0.75rem;">
                                                <?= date('d M Y, H:i', $report->created_at) ?> WIB
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <a href="<?= Url::to(['user/report-detail', 'id' => $report->id]) ?>" class="btn btn-light border p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i data-lucide="eye" class="text-secondary" style="width: 20px; height: 20px;"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            

        </div>
        
    </div>
</div>