<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = 'Semua Laporan - LAJARUS Admin';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316; }
.text-orange-custom { color: #f97316; }
.table-accent { background-color: #f8f9fa; }
.bg-input-dark { background-color: #f8f9fa; }
.bg-input-dark:focus { border-color: #f97316; box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25); }

/* Custom Styling untuk Pagination Yii2 biar ala Bootstrap 5 */
.pagination { margin: 0; gap: 0.5rem; }
.page-item .page-link { border: none; border-radius: 0.5rem; color: #495057; background-color: #fff; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); font-weight: 500; }
.page-item.active .page-link { background-color: #f97316; color: #fff; }
.page-item.disabled .page-link { color: #adb5bd; background-color: #f8f9fa; box-shadow: none; }
CSS;
$this->registerCss($css);

// Ambil nilai filter saat ini dari URL agar form nahan pilihan terakhir
$currentSearch = Yii::$app->request->get('search', '');
$currentStatus = Yii::$app->request->get('status', '');
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1400px;">
            
            <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="fs-3 fw-bold text-dark mb-1">Semua Laporan Masuk</h1>
                    <p class="text-secondary mb-0">Kelola dan pantau seluruh laporan dari masyarakat</p>
                </div>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                
                <form action="<?= Url::to(['admin/reports']) ?>" method="get" class="p-4 border-bottom bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="position-relative w-100" style="max-width: 400px;">
                        <i data-lucide="search" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                        <input type="text" name="search" class="form-control bg-input-dark py-2 border-0 shadow-sm" style="padding-left: 2.75rem;" placeholder="Cari judul laporan atau nama pelapor..." value="<?= Html::encode($currentSearch) ?>">
                    </div>
                    
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <select name="status" class="form-select bg-input-dark py-2 border-0 shadow-sm text-secondary" style="min-width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" <?= $currentStatus === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="Diverifikasi" <?= $currentStatus === 'Diverifikasi' ? 'selected' : '' ?>>Diverifikasi</option>
                            <option value="Proses" <?= $currentStatus === 'Proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="Selesai" <?= $currentStatus === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="Ditolak" <?= $currentStatus === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                        
                        <button type="submit" class="btn bg-orange-custom text-white d-flex align-items-center gap-2 px-3 shadow-sm rounded-3">
                            <i data-lucide="filter" style="width: 16px; height: 16px;"></i> <span class="d-none d-sm-inline">Filter</span>
                        </button>
                        
                        <?php if (!empty($currentSearch) || !empty($currentStatus)): ?>
                            <a href="<?= Url::to(['admin/reports']) ?>" class="btn btn-light border d-flex align-items-center px-3 shadow-sm rounded-3" title="Reset Filter">
                                <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-accent text-secondary small text-uppercase" style="letter-spacing: 0.5px;">
                            <tr>
                                <th class="px-4 py-3 fw-bold border-0">Judul & Lokasi</th>
                                <th class="px-4 py-3 fw-bold border-0">Pelapor</th>
                                <th class="px-4 py-3 fw-bold border-0">Kategori</th>
                                <th class="px-4 py-3 fw-bold border-0">Status</th>
                                <th class="px-4 py-3 fw-bold border-0">Waktu Lapor</th>
                                <th class="px-4 py-3 fw-bold border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reports)): ?>
                                <tr>
                                    <td colspan="6" class="text-center p-5 text-secondary">
                                        <i data-lucide="inbox" class="mb-2" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                                        <p class="mb-0">Belum ada laporan yang cocok atau masuk ke sistem.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reports as $report): 
                                    // Set Warna Status
                                    $badgeColor = 'bg-secondary-subtle text-secondary-emphasis';
                                    if ($report->status == 'Menunggu') $badgeColor = 'bg-warning-subtle text-warning-emphasis';
                                    if ($report->status == 'Diverifikasi') $badgeColor = 'bg-info-subtle text-info-emphasis';
                                    if ($report->status == 'Proses') $badgeColor = 'bg-primary-subtle text-primary-emphasis';
                                    if ($report->status == 'Selesai') $badgeColor = 'bg-success-subtle text-success-emphasis';
                                    if ($report->status == 'Ditolak') $badgeColor = 'bg-danger-subtle text-danger-emphasis';

                                    // Set Warna Kategori
                                    $sev = $report->kategori->nama_kategori ?? 'Tidak Diketahui';
                                    $sevColor = 'text-secondary';
                                    if ($sev == 'Sangat Berat' || $sev == 'Berat') $sevColor = 'text-danger';
                                    if ($sev == 'Sedang') $sevColor = 'text-warning';
                                    if ($sev == 'Ringan') $sevColor = 'text-primary';
                                ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                                    <?= Html::encode($report->judul) ?>
                                                    <?php if ($report->is_priority): ?>
                                                        <i data-lucide="alert-circle" class="text-danger" style="width: 14px; height: 14px;" title="Prioritas Darurat"></i>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="text-secondary small text-truncate" style="max-width: 250px;">
                                                    <i data-lucide="map-pin" style="width: 12px; height: 12px; display: inline;"></i> <?= Html::encode($report->alamat_lokasi) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-dark fw-medium small">
                                            <?= Html::encode($report->user->name ?? 'Anonim') ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="fw-medium small <?= $sevColor ?>">
                                                <i data-lucide="activity" style="width: 14px; height: 14px; display: inline; margin-top:-2px;"></i> <?= Html::encode($sev) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge <?= $badgeColor ?> rounded-pill px-3 py-1 fw-medium">
                                                <?= Html::encode($report->status) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column text-secondary small">
                                                <span class="fw-medium text-dark"><?= date('d M Y', $report->created_at) ?></span>
                                                <span><?= date('H:i', $report->created_at) ?> WIB</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="<?= Url::to(['admin/report-detail', 'id' => $report->id]) ?>" class="btn btn-light btn-sm rounded-3 text-primary fw-medium border shadow-sm px-3 hover-shadow-md">
                                                Tinjau <i data-lucide="arrow-right" style="width: 14px; height: 14px; display: inline; margin-top:-2px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-top d-flex justify-content-center">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'options' => ['class' => 'pagination justify-content-center mb-0'],
                        'linkOptions' => ['class' => 'page-link px-3 py-2'],
                        'pageCssClass' => 'page-item',
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageCssClass' => 'page-item',
                        'nextPageCssClass' => 'page-item',
                        'prevPageLabel' => '«',
                        'nextPageLabel' => '»',
                        'maxButtonCount' => 5,
                    ]) ?>
                </div>

            </div>
            
        </div>
    </div>
</div>