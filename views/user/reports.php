<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\View;

$this->title = 'Laporan Saya - LAJARUS';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.report-item { transition: background-color 0.2s; }
.report-item:hover { background-color: #f8f9fa !important; }
.report-item:last-child { border-bottom: 0 !important; }
.pagination { margin-bottom: 0; }
CSS;
$this->registerCss($css);
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container py-4 px-lg-4 pt-5" style="max-width: 1000px;">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="fs-3 fw-bold text-dark mb-1">Daftar Laporan Saya</h1>
                    <p class="text-secondary mb-0">Semua riwayat laporan jalan rusak yang Anda kirimkan.</p>
                </div>
                <a href="<?= Url::to(['user/new-report']) ?>" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm fw-medium">
                    <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Lapor
                </a>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="d-flex flex-column">
                    <?php if (empty($reports)): ?>
                        <div class="p-5 text-center text-secondary bg-white">
                            <i data-lucide="inbox" class="text-black-50 mb-3 mx-auto" style="width: 48px; height: 48px;"></i>
                            <h5 class="fw-bold text-dark">Belum ada laporan</h5>
                            <p class="mb-0">Anda belum membuat laporan jalan rusak sama sekali.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reports as $report): 
                            // Logika Pewarnaan Badge Status Otomatis
                            $badgeColor = 'bg-secondary-subtle text-secondary-emphasis';
                            if ($report->status == 'Selesai') $badgeColor = 'bg-success-subtle text-success-emphasis';
                            if ($report->status == 'Menunggu') $badgeColor = 'bg-warning-subtle text-warning-emphasis';
                            if ($report->status == 'Proses') $badgeColor = 'bg-primary-subtle text-primary-emphasis';
                        ?>
                            <div class="p-4 border-bottom bg-white report-item d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold text-dark mb-1"><?= Html::encode($report->judul) ?></h5>
                                    <p class="text-secondary small mb-3 d-flex align-items-center gap-1">
                                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i>
                                        <?= Html::encode($report->alamat_lokasi) ?>
                                    </p>
                                    
                                    <div class="d-flex align-items-center flex-wrap gap-3">
                                        <span class="badge rounded-pill fw-normal px-3 py-2 <?= $badgeColor ?>">
                                            Status: <?= Html::encode($report->status) ?>
                                        </span>
                                        <span class="text-secondary small d-flex align-items-center gap-1">
                                            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                            <?= date('d M Y, H:i', $report->created_at) ?> WIB
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-3 mt-md-0 text-md-end">
                                    <a href="<?= Url::to(['user/report-detail', 'id' => $report->id]) ?>" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-2 rounded-3 fw-medium">
                                        <i data-lucide="eye" style="width: 18px; height: 18px;"></i> Lihat Detail
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($reports) && $pagination->pageCount > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'options' => ['class' => 'pagination shadow-sm rounded-3 overflow-hidden'],
                        'linkOptions' => ['class' => 'page-link bg-white text-dark border-0'],
                        'disabledPageCssClass' => 'disabled',
                        'disabledListItemSubTagOptions' => ['class' => 'page-link bg-light text-muted border-0'],
                        'activePageCssClass' => 'active',
                    ]) ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>