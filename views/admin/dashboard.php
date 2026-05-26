<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Admin Dashboard - LAJARUS';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316; }
.text-orange-custom { color: #f97316; }
.hover-shadow-md { transition: box-shadow 0.3s ease; }
.hover-shadow-md:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
.table-accent { background-color: #f8f9fa; }
.text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
CSS;
$this->registerCss($css);
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1400px;">
            
            <div class="mb-5">
                <h1 class="fs-3 fw-bold text-dark mb-1">Admin Dashboard</h1>
                <p class="text-secondary">Selamat datang di panel admin LAJARUS</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-secondary small fw-medium mb-1">Total Laporan</p>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_laporan']) ?></h3>
                            </div>
                            <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                <i data-lucide="file-text" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                        <div class="text-secondary small">Semua laporan masuk</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-secondary small fw-medium mb-1">Masyarakat (User)</p>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_user']) ?></h3>
                            </div>
                            <div class="bg-orange-custom rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                <i data-lucide="users" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                        <div class="text-secondary small">Total akun terdaftar</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-secondary small fw-medium mb-1">Laporan Selesai</p>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_selesai']) ?></h3>
                            </div>
                            <div class="bg-success rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                <i data-lucide="check-circle" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                        <div class="text-success small fw-medium">Infrastruktur telah diperbaiki</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-secondary small fw-medium mb-1">Dalam Proses</p>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_proses']) ?></h3>
                            </div>
                            <div class="bg-warning rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                <i data-lucide="clock" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                        <div class="text-warning small fw-medium">Sedang dikerjakan di lapangan</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md d-flex flex-row align-items-center gap-4">
                            <div class="bg-warning rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i data-lucide="eye" class="text-white" style="width: 28px; height: 28px;"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-dark mb-0"><?= $stats['menunggu'] ?></h3>
                                <p class="text-secondary small mb-0">Belum Diverifikasi</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md d-flex flex-row align-items-center gap-4">
                            <div class="bg-info rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i data-lucide="check-square" class="text-white" style="width: 28px; height: 28px;"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-dark mb-0"><?= $stats['diverifikasi'] ?></h3>
                                <p class="text-secondary small mb-0">Sudah Diverifikasi</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md d-flex flex-row align-items-center gap-4">
                            <div class="bg-danger rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i data-lucide="alert-circle" class="text-white" style="width: 28px; height: 28px;"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-dark mb-0"><?= $stats['prioritas'] ?></h3>
                                <p class="text-secondary small mb-0">Laporan Prioritas Utama</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                
                <div class="col-xl-8">
                    <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0">Laporan Terbaru</h5>
                            <a href="#" class="text-primary text-decoration-none small fw-medium">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-accent text-secondary small">
                                    <tr>
                                        <th class="px-4 py-3 fw-medium border-0">Judul Laporan</th>
                                        <th class="px-4 py-3 fw-medium border-0">Pelapor</th>
                                        <th class="px-4 py-3 fw-medium border-0">Status</th>
                                        <th class="px-4 py-3 fw-medium border-0">Prioritas</th>
                                        <th class="px-4 py-3 fw-medium border-0">Tanggal</th>
                                        <th class="px-4 py-3 fw-medium border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentReports)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center p-4 text-secondary small">Belum ada laporan masuk.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentReports as $report): 
                                            // Pengaturan Warna Badge Status Dinamis
                                            $badgeColor = 'bg-secondary-subtle text-secondary-emphasis';
                                            if ($report->status == 'Menunggu') $badgeColor = 'bg-warning-subtle text-warning-emphasis';
                                            if ($report->status == 'Diverifikasi') $badgeColor = 'bg-info-subtle text-info-emphasis';
                                            if ($report->status == 'Proses') $badgeColor = 'bg-primary-subtle text-primary-emphasis';
                                            if ($report->status == 'Selesai') $badgeColor = 'bg-success-subtle text-success-emphasis';
                                            if ($report->status == 'Ditolak') $badgeColor = 'bg-danger-subtle text-danger-emphasis';
                                        ?>
                                            <tr>
                                                <td class="px-4 py-3 text-dark fw-medium"><?= Html::encode($report->judul) ?></td>
                                                <td class="px-4 py-3 text-secondary small"><?= Html::encode($report->user->name ?? 'Anonim') ?></td>
                                                <td class="px-4 py-3"><span class="badge <?= $badgeColor ?> rounded-pill px-3"><?= Html::encode($report->status) ?></span></td>
                                                <td class="px-4 py-3 small">
                                                    <?php if ($report->is_priority): ?>
                                                        <span class="text-danger fw-bold"><i data-lucide="alert-circle" style="width:14px;height:14px;display:inline;margin-top:-2px;"></i> Darurat</span>
                                                    <?php else: ?>
                                                        <span class="text-secondary">Normal</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 text-secondary small"><?= date('d M Y', $report->created_at) ?></td>
                                                <td class="px-4 py-3"><a href="<?= Url::to(['admin/report-detail', 'id' => $report->id]) ?>" class="text-primary text-decoration-none small fw-medium">Detail</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card bg-white border-0 shadow-sm rounded-4 h-100">
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i data-lucide="message-square" style="width: 20px; height: 20px;"></i> Komentar Baru
                            </h5>
                        </div>
                        <div class="p-4">
                            <?php if (empty($recentComments)): ?>
                                <p class="text-center text-secondary small py-4 mb-0">Belum ada komentar baru.</p>
                            <?php else: ?>
                                <?php foreach ($recentComments as $comment): 
                                    $isAdmin = $comment->user->role === 'admin';
                                    
                                    // Bikin inisial avatar (Misal: John Doe -> JD)
                                    $words = explode(' ', $comment->user->name);
                                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                ?>
                                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0 <?= $isAdmin ? 'bg-orange-custom' : 'bg-primary' ?>" style="width: 40px; height: 40px;">
                                            <?= $initials ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small"><?= Html::encode($comment->user->name) ?> <?= $isAdmin ? '<span class="text-orange-custom" style="font-size:0.7rem;">(Admin)</span>' : '' ?></span>
                                                <span class="text-secondary" style="font-size: 0.7rem;"><?= date('H:i', $comment->created_at) ?></span>
                                            </div>
                                            <p class="text-secondary small mb-1 text-truncate-2" style="line-height: 1.4;"><?= Html::encode($comment->isi_komentar) ?></p>
                                            <a href="<?= Url::to(['admin/report-detail', 'id' => $comment->laporan_id]) ?>" class="text-primary text-decoration-none d-block fw-medium" style="font-size: 0.75rem;">Lihat Laporan →</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>