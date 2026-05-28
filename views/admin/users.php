<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = 'Manajemen Pengguna - LAJARUS Admin';

$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316; }
.text-orange-custom { color: #f97316; }
.table-accent { background-color: #f8f9fa; }
.bg-input-dark { background-color: #f8f9fa; }
.bg-input-dark:focus { border-color: #f97316; box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25); }

.pagination { margin: 0; gap: 0.5rem; }
.page-item .page-link { border: none; border-radius: 0.5rem; color: #495057; background-color: #fff; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); font-weight: 500; }
.page-item.active .page-link { background-color: #f97316; color: #fff; }
.page-item.disabled .page-link { color: #adb5bd; background-color: #f8f9fa; box-shadow: none; }
CSS;
$this->registerCss($css);

$currentAdminId = Yii::$app->user->identity->id;
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1400px;">
            
            <div class="mb-4">
                <h1 class="fs-3 fw-bold text-dark mb-1">Manajemen Pengguna</h1>
                <p class="text-secondary mb-0">Kelola data akun masyarakat dan administrator sistem</p>
            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                
                <form action="<?= Url::to(['admin/users']) ?>" method="get" class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <div class="position-relative w-100" style="max-width: 400px;">
                        <i data-lucide="search" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                        <input type="text" name="search" class="form-control bg-input-dark py-2 border-0 shadow-sm" style="padding-left: 2.75rem;" placeholder="Cari nama pengguna atau email..." value="<?= Html::encode($currentSearch) ?>">
                    </div>
                    <?php if (!empty($currentSearch)): ?>
                        <a href="<?= Url::to(['admin/users']) ?>" class="btn btn-light border rounded-3 small px-3">Reset</a>
                    <?php endif; ?>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-accent text-secondary small text-uppercase" style="letter-spacing: 0.5px;">
                            <tr>
                                <th class="px-4 py-3 fw-bold border-0">Pengguna</th>
                                <th class="px-4 py-3 fw-bold border-0">Kontak</th>
                                <th class="px-4 py-3 fw-bold border-0">Alamat</th>
                                <th class="px-4 py-3 fw-bold border-0">Hak Akses</th>
                                <th class="px-4 py-3 fw-bold border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-5 text-secondary">Akun tidak ditemukan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): 
                                    // Bikin Avatar otomatis dari inisial nama
                                    $words = explode(' ', $user->name);
                                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                    $isAdmin = $user->role === 'admin';
                                ?>
                                    <tr id="user-row-<?= $user->id ?>">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0 <?= $isAdmin ? 'bg-orange-custom' : 'bg-primary' ?>" style="width: 42px; height: 42px; font-size: 0.9rem;">
                                                    <?= $initials ?>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark"><?= Html::encode($user->name) ?></span>
                                                    <span class="text-secondary small"><?= Html::encode($user->email) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-secondary small fw-medium">
                                            <?= Html::encode($user->phone ?: '-') ?>
                                        </td>
                                        <td class="px-4 py-3 text-secondary small text-truncate" style="max-width: 200px;">
                                            <?= Html::encode($user->address ?: '-') ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge rounded-pill px-3 py-1 <?= $isAdmin ? 'bg-danger bg-opacity-10 text-danger' : 'bg-secondary bg-opacity-10 text-secondary' ?> fw-semibold">
                                                <?= $isAdmin ? 'Administrator' : 'Masyarakat' ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if (!$isAdmin): ?>
                                                    <button type="button" class="btn btn-outline-success btn-sm rounded-3 d-flex align-items-center gap-1.5 px-2.5 fw-medium" onclick="makeAdmin(<?= $user->id ?>)">
                                                        <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i> Set Admin
                                                    </button>
                                                <?php endif; ?>

                                                <?php if ($user->id !== $currentAdminId): ?>
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 d-flex align-items-center gap-1.5 px-2.5" onclick="deleteUser(<?= $user->id ?>)">
                                                        <i data-lucide="user-x" style="width: 14px; height: 14px;"></i> Hapus
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted small fst-italic px-2">Akun Anda</span>
                                                <?php endif; ?>
                                            </div>
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
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$makeAdminApi = Url::to(['api/make-admin', 'id' => '']);
$deleteUserApi = Url::to(['api/delete-user', 'id' => '']);

$js = <<<JS
// AJAX: Set Hak Akses Jadi Admin
window.makeAdmin = function(id) {
    if(!confirm("Apakah Anda yakin ingin menaikkan hak akses pengguna ini menjadi Administrator?")) return;

    $.ajax({
        url: '$makeAdminApi' + id,
        type: 'POST',
        success: function(res) {
            if(res.status === 'success') {
                alert(res.message);
                window.location.reload(); // Reload ringan untuk update layout badge
            } else {
                alert(res.message);
            }
        }
    });
}

// AJAX: Hapus Permanen Pengguna
window.deleteUser = function(id) {
    if(!confirm("PERINGATAN! Menghapus akun pengguna akan menghapus seluruh data laporan mereka dari database secara permanen. Lanjutkan?")) return;

    $.ajax({
        url: '$deleteUserApi' + id,
        type: 'POST',
        success: function(res) {
            if(res.status === 'success') {
                alert(res.message);
                $('#user-row-' + id).fadeOut(300, function() { $(this).remove(); });
            } else {
                alert(res.message);
            }
        }
    });
}
JS;
$this->registerJs($js);
?>