<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Profil Administrator - LAJARUS';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316; }
.text-orange-custom { color: #f97316; }
.admin-header-bg { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); height: 160px; border-radius: 1rem 1rem 0 0; }
.admin-avatar-wrapper { margin-top: -60px; padding-left: 2rem; }
.avatar-circle { width: 120px; height: 120px; border: 5px solid #fff; box-shadow: 0 .5rem 1rem rgba(0,0,0,0.1); }
CSS;
$this->registerCss($css);

// Ambil inisial nama
$words = explode(' ', $admin->name);
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1100px;">
            
            <div class="mb-4">
                <h1 class="fs-3 fw-bold text-dark mb-1">Profil Admin</h1>
                <p class="text-secondary">Informasi akun administrator sistem</p>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="admin-header-bg"></div>
                <div class="admin-avatar-wrapper d-flex flex-column flex-md-row align-items-md-end gap-4 pb-4 border-bottom mx-4">
                    <div class="rounded-circle avatar-circle bg-orange-custom d-flex align-items-center justify-content-center text-white fw-bold fs-1">
                        <?= $initials ?>
                    </div>
                    <div class="mb-2">
                        <h2 class="fw-bold text-dark mb-1"><?= Html::encode($admin->name) ?></h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-pill px-3 py-1 fw-semibold">Super Administrator</span>
                            <span class="text-secondary small">ID: #<?= $admin->id ?></span>
                        </div>
                    </div>
                    <div class="ms-md-auto mb-2">
                        <a href="<?= Url::to(['admin/settings']) ?>" class="btn btn-outline-dark rounded-3 d-flex align-items-center gap-2 px-4 shadow-sm">
                            <i data-lucide="settings" style="width: 18px; height: 18px;"></i> Edit Profil
                        </a>
                    </div>
                </div>

                <div class="p-4 mx-2">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold text-dark mb-4">Detail Informasi Akun</h5>
                            
                            <div class="row mb-4">
                                <div class="col-sm-4 text-secondary small text-uppercase fw-bold">Email Utama</div>
                                <div class="col-sm-8 text-dark fw-medium"><?= Html::encode($admin->email) ?></div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-sm-4 text-secondary small text-uppercase fw-bold">Nomor Telepon</div>
                                <div class="col-sm-8 text-dark fw-medium"><?= Html::encode($admin->phone ?: 'Belum diatur') ?></div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-4 text-secondary small text-uppercase fw-bold">Alamat Kantor</div>
                                <div class="col-sm-8 text-dark fw-medium"><?= Html::encode($admin->address ?: 'Belum diatur') ?></div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-sm-4 text-secondary small text-uppercase fw-bold">Status Keamanan</div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center gap-2 text-success fw-bold">
                                        <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i> Akun Terlindungi
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 border-start-md ps-md-5">
                            <h5 class="fw-bold text-dark mb-4">Ringkasan Sistem</h5>
                            
                            <div class="bg-light rounded-4 p-4 border border-dashed border-primary border-opacity-25 mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-secondary small fw-medium">Populasi Pengguna</span>
                                    <i data-lucide="users" class="text-primary" style="width: 18px; height: 18px;"></i>
                                </div>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_user']) ?> <span class="fs-6 fw-normal text-secondary">Masyarakat</span></h3>
                            </div>

                            <div class="bg-light rounded-4 p-4 border border-dashed border-success border-opacity-25">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-secondary small fw-medium">Laporan Tuntas</span>
                                    <i data-lucide="check-circle" class="text-success" style="width: 18px; height: 18px;"></i>
                                </div>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total_selesai']) ?> / <?= number_format($stats['total_laporan']) ?> <span class="fs-6 fw-normal text-secondary">Selesai</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i data-lucide="info" class="text-warning" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tips Keamanan Admin</h6>
                        <p class="text-secondary small mb-0">Pastikan Anda mengganti password secara berkala dan selalu melakukan <span class="fw-bold">Logout</span> setelah selesai mengelola dashboard ini.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>