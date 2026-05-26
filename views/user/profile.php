<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Profil Saya - LAJARUS';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-input-background { background-color: #f8f9fa !important; color: #212529 !important; }
.bg-input-background:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
CSS;
$this->registerCss($css);

// Ambil inisial nama untuk Avatar otomatis
$words = explode(' ', $user->name);
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container py-4 px-lg-4 pt-5" style="max-width: 1000px;">
            
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                    <div><?= Yii::$app->session->getFlash('success') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                    <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                    <div><?= Yii::$app->session->getFlash('error') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="mb-5">
                <h1 class="fs-3 fw-bold text-dark mb-1">Profil Saya</h1>
                <p class="text-secondary">Kelola informasi profil Anda</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 text-center">
                        <div class="position-relative d-inline-block mx-auto mb-4">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);">
                                <span class="fs-1 text-white fw-bold"><?= Html::encode($initials) ?></span>
                            </div>
                            <button type="button" class="btn btn-warning text-white rounded-circle position-absolute bottom-0 end-0 shadow d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 3px solid #fff;">
                                <i data-lucide="camera" style="width: 20px; height: 20px;"></i>
                            </button>
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-1"><?= Html::encode($user->name) ?></h5>
                        <p class="text-secondary small mb-4"><?= Html::encode($user->email) ?></p>

                        <div class="border-top pt-4">
                            <div class="mb-3">
                                <h3 class="fw-bold text-dark mb-0"><?= $stats['total'] ?></h3>
                                <p class="text-secondary small">Total Laporan</p>
                            </div>
                            <div class="row border-top pt-3 text-center">
                                <div class="col-6 border-end">
                                    <h4 class="fw-bold text-success mb-0"><?= $stats['selesai'] ?></h4>
                                    <p class="text-secondary small" style="font-size: 0.75rem;">Selesai</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="fw-bold text-warning mb-0"><?= $stats['proses'] ?></h4>
                                    <p class="text-secondary small" style="font-size: 0.75rem;">Proses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                        <h4 class="fw-bold text-dark mb-4">Informasi Pribadi</h4>
                        
                        <?= Html::beginForm(['user/update-profile'], 'post') ?>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Nama Lengkap</label>
                                <div class="position-relative">
                                    <i data-lucide="user" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="name" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" value="<?= Html::encode($user->name) ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Email</label>
                                <div class="position-relative">
                                    <i data-lucide="mail" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="email" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" value="<?= Html::encode($user->email) ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Nomor Telepon</label>
                                <div class="position-relative">
                                    <i data-lucide="phone" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="phone" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan nomor telepon" value="<?= Html::encode($user->phone ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Alamat</label>
                                <div class="position-relative">
                                    <i data-lucide="map-pin" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="address" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan alamat lengkap" value="<?= Html::encode($user->address ?? '') ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mt-4 rounded-3 shadow-sm">
                                <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan
                            </button>
                        <?= Html::endForm() ?>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold text-dark mb-4">Ubah Password</h4>
                        
                        <?= Html::beginForm(['user/change-password'], 'post') ?>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Password Lama</label>
                                <input type="password" name="old_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Password Baru</label>
                                <input type="password" name="new_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-warning text-white px-4 py-2 fw-medium rounded-3">
                                Ubah Password
                            </button>
                        <?= Html::endForm() ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>