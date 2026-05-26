<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Pengaturan Akun - LAJARUS Admin';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316 !important; color: #fff !important; }
.bg-orange-custom:hover { background-color: #ea580c !important; }
.text-orange-custom { color: #f97316; }
.bg-input-background { background-color: #f8f9fa !important; color: #212529 !important; }
.bg-input-background:focus { border-color: #f97316 !important; box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25) !important; }
CSS;
$this->registerCss($css);
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 900px;">
            
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
                <a href="<?= Url::to(['admin/profile']) ?>" class="btn btn-link text-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-2 px-0">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> Kembali ke Profil
                </a>
                <h1 class="fs-3 fw-bold text-dark mb-1">Pengaturan Akun Admin</h1>
                <p class="text-secondary">Kelola informasi pribadi dan keamanan kredensial Anda</p>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                        <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">Informasi Pribadi Admin</h5>
                        
                        <?= Html::beginForm(['admin/update-profile'], 'post') ?>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Nama Lengkap</label>
                                <div class="position-relative">
                                    <i data-lucide="user" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="name" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" value="<?= Html::encode($admin->name) ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Email Administrator</label>
                                <div class="position-relative">
                                    <i data-lucide="mail" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="email" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" value="<?= Html::encode($admin->email) ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Nomor Telepon</label>
                                <div class="position-relative">
                                    <i data-lucide="phone" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="phone" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan nomor telepon instansi/kerja" value="<?= Html::encode($admin->phone ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Alamat Kantor / Instansi</label>
                                <div class="position-relative">
                                    <i data-lucide="map-pin" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" name="address" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan alamat penugasan" value="<?= Html::encode($admin->address ?? '') ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn bg-orange-custom text-white w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mt-4 rounded-3 shadow-sm">
                                <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan Profil
                            </button>
                        <?= Html::endForm() ?>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">Ganti Password Keamanan</h5>
                        
                        <?= Html::beginForm(['admin/change-password'], 'post') ?>
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
                            
                            <button type="submit" class="btn bg-orange-custom text-white px-4 py-2 fw-medium rounded-3 shadow-sm">
                                Perbarui Password Admin
                            </button>
                        <?= Html::endForm() ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>