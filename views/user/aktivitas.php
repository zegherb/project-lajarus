<?php
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Riwayat Aktivitas - LAJARUS';

$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.timeline { border-left: 2px solid #e2e8f0; position: relative; padding-left: 1.5rem; }
.timeline-item { position: relative; margin-bottom: 1.5rem; }
.timeline-dot { position: absolute; left: -2.15rem; top: 4px; width: 16px; height: 16px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 2px #cbd5e1; }
.dot-admin { background-color: #f97316; box-shadow: 0 0 0 2px #fed7aa; }
.dot-user { background-color: #3b82f6; box-shadow: 0 0 0 2px #bfdbfe; }
CSS;
$this->registerCss($css);
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1000px;">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fs-3 fw-bold text-dark mb-1">Log & Alur Aktivitas</h1>
                    <p class="text-secondary mb-0 d-flex align-items-center gap-1.5 small">
                        <i data-lucide="radio" class="text-primary" style="width:14px; height:14px;"></i> Data murni disinkronisasi melalui Protokol SOAP Web Service
                    </p>
                </div>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5">
                <?php if (empty($logs)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i data-lucide="history" class="mb-3 text-muted opacity-50" style="width: 48px; height: 48px;"></i>
                        <p class="mb-0 fw-medium">Belum ada rekaman aktivitas saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="timeline ms-2">
                        <?php foreach ($logs as $log): 
                            $isAdminLog = $log['tipe'] === 'admin';
                        ?>
                            <div class="timeline-item">
                                <div class="timeline-dot <?= $isAdminLog ? 'dot-admin' : 'dot-user' ?>"></div>
                                
                                <div class="bg-light rounded-3 p-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div class="d-flex align-items-start gap-2">
                                        <i data-lucide="<?= $isAdminLog ? 'shield' : 'user' ?>" class="mt-1 <?= $isAdminLog ? 'text-orange-custom' : 'text-primary' ?>" style="width:16px; height:16px; min-width:16px;"></i>
                                        <span class="text-dark fw-medium" style="font-size: 0.95rem;">
                                            <?= Html::encode($log['deskripsi']) ?>
                                        </span>
                                    </div>
                                    <span class="text-secondary small flex-shrink-0 text-end d-flex align-items-center gap-1">
                                        <i data-lucide="clock" style="width:12px; height:12px;"></i>
                                        <?= date('d M Y - H:i', $log['created_at']) ?> WIB
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>