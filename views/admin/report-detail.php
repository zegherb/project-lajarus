<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Detail Laporan (Admin) - LAJARUS';

$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', ['position' => View::POS_HEAD]);
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => View::POS_HEAD]);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.bg-orange-custom { background-color: #f97316 !important; color: #fff !important; }
.bg-orange-custom:hover { background-color: #ea580c !important; }
.text-orange-custom { color: #f97316; }
.chat-admin { background-color: rgba(249, 115, 22, 0.06); border-left: 4px solid #f97316; }
.chat-user { background-color: #f8f9fa; border-left: 4px solid #6c757d; }
.bg-input-dark { background-color: #f8f9fa; }
.btn-delete-comment { opacity: 0.4; transition: opacity 0.2s; }
.comment-bubble:hover .btn-delete-comment { opacity: 1; }
CSS;
$this->registerCss($css);

$lat = $report->latitude ?: '-3.9985';
$lng = $report->longitude ?: '122.5126';

// Ambil Kategori Tingkat Kerusakan dari model relasi
$sev = $report->kategori->nama_kategori ?? 'Tidak Diketahui';
$sevColor = 'bg-secondary-subtle text-secondary';
if ($sev == 'Sangat Berat' || $sev == 'Berat') $sevColor = 'bg-danger-subtle text-danger';
if ($sev == 'Sedang') $sevColor = 'bg-warning-subtle text-warning-emphasis';
if ($sev == 'Ringan') $sevColor = 'bg-info-subtle text-info-emphasis';
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1400px;">

            <a href="<?= Url::to(['admin/dashboard']) ?>" class="btn btn-link text-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-4 px-0">
                <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i> Kembali ke Dashboard
            </a>

            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span id="badge-prioritas" class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 <?= $report->is_priority ? '' : 'd-none' ?>">
                                    <i data-lucide="alert-circle" style="width: 12px; height: 12px; display: inline; margin-top: -2px;"></i> Prioritas Utama
                                </span>
                                <span id="badge-status-top" class="badge bg-warning text-dark rounded-pill px-3 py-1"><?= Html::encode($report->status) ?></span>
                            </div>
                            <h2 class="fs-4 fw-bold text-dark mb-0"><?= Html::encode($report->judul) ?></h2>
                        </div>

                        <div class="d-flex flex-wrap gap-4 text-secondary small mb-4 border-bottom pb-4">
                            <div class="d-flex align-items-center gap-2">
                                <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                                <span><?= Html::encode($report->user->name ?? 'Anonim') ?> (Pelapor)</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span><?= date('d M Y, H:i', $report->created_at) ?> WIB</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">Deskripsi Kerusakan</h6>
                            <p class="text-secondary" style="line-height: 1.6;"><?= Html::encode($report->deskripsi) ?></p>
                        </div>

                        <div class="d-flex align-items-center gap-3 border-top pt-3">
                            <span class="text-secondary small fw-medium">Tingkat Kerusakan:</span>
                            <span class="badge rounded-pill fw-normal px-3 py-1.5 <?= $sevColor ?>">
                                <?= Html::encode($sev) ?>
                            </span>
                        </div>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i data-lucide="image" style="width: 20px; height: 20px;"></i> Foto Bukti
                        </h5>
                        <?php if ($report->foto): ?>
                            <img src="<?= Url::to('@web/uploads/' . $report->foto) ?>" class="img-fluid rounded-3 shadow-sm border" alt="Bukti" style="max-height: 500px; object-fit: cover; width: 100%;">
                        <?php else: ?>
                            <div class="bg-light rounded text-center p-5 text-secondary border border-dashed">
                                <i data-lucide="camera" class="mb-2" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                <p class="mb-0">Tidak ada bukti foto terlampir.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i> Lokasi Laporan
                        </h5>
                        <p class="text-secondary mb-4"><?= Html::encode($report->alamat_lokasi) ?></p>
                        <div id="map-detail" class="rounded-3 border shadow-sm" style="height: 350px; z-index: 1;"></div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2 border-bottom pb-3">
                            <i data-lucide="settings" style="width: 20px; height: 20px;"></i> Panel Kendali
                        </h5>

                        <form id="admin-control-form">
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-dark">Update Status Laporan</label>
                                <select class="form-select bg-input-dark py-2 border-0 shadow-sm text-dark" id="status-select">
                                    <option value="Menunggu" <?= $report->status == 'Menunggu' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                                    <option value="Diverifikasi" <?= $report->status == 'Diverifikasi' ? 'selected' : '' ?>>Diverifikasi (Disetujui)</option>
                                    <option value="Proses" <?= $report->status == 'Proses' ? 'selected' : '' ?>>Dalam Proses Perbaikan</option>
                                    <option value="Selesai" <?= $report->status == 'Selesai' ? 'selected' : '' ?>>Perbaikan Selesai</option>
                                    <option value="Ditolak" <?= $report->status == 'Ditolak' ? 'selected' : '' ?>>Tolak Laporan</option>
                                </select>
                            </div>

                            <div class="mb-4 bg-danger bg-opacity-10 p-3 rounded-3 border border-danger border-opacity-25">
                                <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                    <label class="form-check-label small fw-bold text-danger m-0" for="priority-switch">Tandai Sebagai Prioritas</label>
                                    <input class="form-check-input m-0" type="checkbox" role="switch" id="priority-switch" <?= $report->is_priority ? 'checked' : '' ?> style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                </div>
                            </div>

                            <button type="submit" class="btn bg-orange-custom text-white w-100 py-2 fw-bold mb-3 shadow-sm" id="btn-save-control">
                                Simpan Perubahan
                            </button>

                            <div class="border-top pt-3 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" id="btn-delete-report">
                                    <i data-lucide="trash-2" style="width: 16px; height: 16px; display: inline;"></i> Hapus Laporan Permanen
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i data-lucide="message-square" style="width: 20px; height: 20px;"></i> Komunikasi Pelapor
                        </h5>

                        <div class="space-y-3 mb-4" id="chat-container" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($komentar)): ?>
                                <p class="text-center text-secondary small py-3" id="no-comment-msg">Belum ada komentar.</p>
                            <?php else: ?>
                                <?php foreach ($komentar as $komen): 
                                    $isAdmin = $komen->user->role === 'admin';
                                ?>
                                    <div class="rounded-3 p-3 mb-3 comment-bubble position-relative <?= $isAdmin ? 'chat-admin' : 'chat-user' ?>" id="comment-row-<?= $komen->id ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="fw-bold small <?= $isAdmin ? 'text-orange-custom' : 'text-dark' ?>">
                                                <?= Html::encode($komen->user->name) ?> <?= $isAdmin ? '(Admin)' : '' ?>
                                            </span>
                                            <span class="text-secondary me-4" style="font-size: 0.7rem;"><?= date('d M, H:i', $komen->created_at) ?></span>
                                        </div>
                                        <p class="text-dark small mb-0 pe-3"><?= Html::encode($komen->isi_komentar) ?></p>
                                        
                                        <button type="button" class="btn p-0 text-danger position-absolute top-0 end-0 mt-2 me-2 btn-delete-comment" onclick="deleteComment(<?= $komen->id ?>)">
                                            <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="border-top pt-3">
                            <textarea class="form-control bg-input-dark border-0 py-2 mb-3 text-dark" id="comment-text" rows="3" placeholder="Tulis balasan untuk pelapor..."></textarea>
                            <button type="button" class="btn bg-orange-custom text-white px-4 py-2 fw-medium w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm" id="btn-send-comment">
                                <i data-lucide="send" style="width: 16px; height: 16px;"></i> Kirim Balasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$updateApi = Url::to(['api/admin-update-report', 'id' => $report->id]);
$commentApi = Url::to(['api/add-komentar', 'id' => $report->id]);
$deleteApi = Url::to(['api/admin-delete-report', 'id' => $report->id]);
$deleteCommentApi = Url::to(['api/delete-komentar', 'id' => '']);
$dashboardUrl = Url::to(['admin/dashboard']);

$js = <<<JS
var mapDetail = L.map('map-detail').setView([$lat, $lng], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDetail);
L.marker([$lat, $lng]).addTo(mapDetail);

var chatContainer = document.getElementById("chat-container");
if(chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;

// AJAX: Simpan Status & Prioritas
$('#admin-control-form').on('submit', function(e) {
    e.preventDefault();
    var btn = $('#btn-save-control');
    btn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: '$updateApi',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            status: $('#status-select').val(),
            is_priority: $('#priority-switch').is(':checked') ? 1 : 0
        }),
        success: function(res) {
            btn.prop('disabled', false).text('Simpan Perubahan');
            alert('Berhasil memperbarui data laporan!');
            window.location.reload();
        }
    });
});

// AJAX: Kirim Komentar (FIXED)
$('#btn-send-comment').on('click', function() {
    var textInput = $('#comment-text');
    var isi = textInput.val().trim();
    if(!isi) return;

    var btn = $(this);
    btn.prop('disabled', true);

    $.ajax({
        url: '$commentApi',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ isi_komentar: isi }),
        success: function(res) {
            btn.prop('disabled', false);
            if(res && res.status === 'success') {
                textInput.val('');
                $('#no-comment-msg').remove();

                $('#chat-container').append(`
                    <div class="rounded-3 p-3 mb-3 comment-bubble position-relative chat-admin" id="comment-row-\${res.data.id}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold small text-orange-custom">\${res.data.nama} (Admin)</span>
                            <span class="text-secondary me-4" style="font-size: 0.7rem;">\${res.data.waktu}</span>
                        </div>
                        <p class="text-dark small mb-0 pe-3">\${res.data.isi}</p>
                        <button type="button" class="btn p-0 text-danger position-absolute top-0 end-0 mt-2 me-2 btn-delete-comment" onclick="deleteComment(\${res.data.id})">
                            <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                        </button>
                    </div>
                `);
                lucide.createIcons();
                chatContainer.scrollTop = chatContainer.scrollHeight;
            } else {
                alert(res.message || 'Gagal mengirim komentar.');
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            alert('Gagal mengirim komentar. Terjadi error di server.');
        }
    });
});

// Fungsi Global Hapus Komentar via AJAX
window.deleteComment = function(id) {
    if(!confirm("Hapus komentar ini?")) return;
    $.ajax({
        url: '$deleteCommentApi' + id,
        type: 'POST',
        success: function(res) {
            if(res.status === 'success') {
                $('#comment-row-' + id).fadeOut(300, function() { $(this).remove(); });
            } else {
                alert(res.message);
            }
        }
    });
}

// AJAX: Hapus Laporan
$('#btn-delete-report').on('click', function() {
    if(confirm("Apakah Anda yakin ingin menghapus laporan ini secara permanen?")) {
        $.ajax({
            url: '$deleteApi',
            type: 'POST',
            success: function(res) {
                if(res.status === 'success') window.location.href = '$dashboardUrl';
            }
        });
    }
});
JS;
$this->registerJs($js);
?>