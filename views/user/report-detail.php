<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Detail Laporan - LAJARUS';

$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', ['position' => View::POS_HEAD]);
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => View::POS_HEAD]);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.timeline-dot { width: 12px; height: 12px; border-radius: 50%; }
.timeline-line { width: 2px; height: 3rem; margin: 4px 0; }
.bg-completed { background-color: #198754; }
.bg-current { background-color: #0d6efd; }
.bg-pending { background-color: #dee2e6; }
.btn-delete-comment { opacity: 0; transition: opacity 0.2s; }
.comment-bubble:hover .btn-delete-comment { opacity: 0.6; }
.comment-bubble:hover .btn-delete-comment:hover { opacity: 1; }
CSS;
$this->registerCss($css);

$badgeColor = 'bg-secondary-subtle text-secondary-emphasis';
if ($report->status == 'Menunggu') $badgeColor = 'bg-warning-subtle text-warning-emphasis';
if ($report->status == 'Diverifikasi') $badgeColor = 'bg-info-subtle text-info-emphasis'; 
if ($report->status == 'Proses') $badgeColor = 'bg-primary-subtle text-primary-emphasis';
if ($report->status == 'Selesai') $badgeColor = 'bg-success-subtle text-success-emphasis';
if ($report->status == 'Ditolak') $badgeColor = 'bg-danger-subtle text-danger-emphasis';
                        
$sev = $report->kategori->nama_kategori ?? 'Tidak Diketahui';
$sevColor = 'bg-secondary-subtle text-secondary';
if ($sev == 'Sangat Berat' || $sev == 'Berat') $sevColor = 'bg-danger-subtle text-danger';
if ($sev == 'Sedang') $sevColor = 'bg-warning-subtle text-warning-emphasis';
if ($sev == 'Ringan') $sevColor = 'bg-info-subtle text-info-emphasis';

$currentUserId = Yii::$app->user->identity->id;
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
            
            <a href="<?= Url::to(['user/dashboard']) ?>" class="btn btn-link text-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-4 px-0">
                <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i> Kembali ke Dashboard
            </a>

            <div class="row g-4">
                <div class="col-lg-8">
                    
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                            <h2 class="fs-4 fw-bold text-dark mb-0"><?= Html::encode($report->judul) ?></h2>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($report->is_priority): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">
                                        <i data-lucide="alert-circle" style="width: 14px; height: 14px; display: inline; margin-top: -2px;"></i> Prioritas Darurat
                                    </span>
                                <?php endif; ?>
                                <span class="badge rounded-pill fw-normal px-3 py-2 <?= $badgeColor ?>">
                                    <?= Html::encode($report->status) ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-4 text-secondary small mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                <span><?= date('d M Y', $report->created_at) ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                                <span><?= date('H:i', $report->created_at) ?> WIB</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">Deskripsi</h6>
                            <p class="text-secondary" style="line-height: 1.6;"><?= nl2br(Html::encode($report->deskripsi)) ?></p>
                        </div>

                        <div class="d-flex align-items-center gap-3 border-top pt-3">
                            <span class="text-secondary small">Tingkat Kerusakan:</span>
                            <span class="badge rounded-pill fw-normal px-3 py-1 <?= $sevColor ?>">
                                <?= Html::encode($sev) ?>
                            </span>
                        </div>
                        <?php if ($report->status === 'Menunggu' || $report->status === 'Ditolak'): ?>
                            <div class="d-flex gap-2 border-top mt-4 pt-3">
                                <a href="<?= Url::to(['user/update-report', 'id' => $report->id]) ?>" class="btn btn-outline-primary btn-sm px-3 d-flex align-items-center gap-2">
                                    <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i> Edit Laporan
                                </a>
                                
                                <?= Html::beginForm(['user/delete-report', 'id' => $report->id], 'post', ['onsubmit' => 'return confirm("Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.");']) ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center gap-2">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i> Hapus
                                    </button>
                                <?= Html::endForm() ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i data-lucide="image" style="width: 20px; height: 20px;"></i> Foto Kerusakan
                        </h5>
                        <?php if ($report->foto): ?>
                            <img src="<?= Url::to('@web/uploads/' . $report->foto) ?>" alt="Foto Bukti" class="img-fluid rounded-3 border shadow-sm" style="max-height: 400px; object-fit: cover; width: 100%;">
                        <?php else: ?>
                            <div class="bg-light rounded text-center p-5 text-secondary border border-dashed">Tidak ada foto terlampir.</div>
                        <?php endif; ?>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i> Lokasi
                        </h5>
                        <p class="text-secondary mb-4"><?= Html::encode($report->alamat_lokasi) ?></p>
                        <div id="map-detail" class="rounded-3 border shadow-sm" style="height: 250px; z-index: 1;"></div>
                    </div>
                    
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i data-lucide="message-square" style="width: 20px; height: 20px;"></i> Komentar & Update
                        </h5>

                        <div class="space-y-3 mb-4" id="chat-container" style="max-height: 400px; overflow-y: auto;">
                            <?php if (empty($komentar)): ?>
                                <p class="text-center text-secondary small py-3" id="no-comment-msg">Belum ada komentar atau pesan dari admin.</p>
                            <?php else: ?>
                                <?php foreach ($komentar as $komen): 
                                    $isAdmin = $komen->user->role === 'admin';
                                ?>
                                    <div class="border rounded-3 p-3 mb-3 comment-bubble position-relative <?= $isAdmin ? 'bg-primary bg-opacity-10 border-primary border-opacity-25' : 'bg-light' ?>" id="comment-row-<?= $komen->id ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="fw-bold small <?= $isAdmin ? 'text-primary' : 'text-dark' ?>">
                                                <?= $isAdmin ? 'Admin LAJARUS' : 'Anda' ?>
                                            </span>
                                            <span class="text-secondary me-3" style="font-size: 0.75rem;"><?= date('d M Y, H:i', $komen->created_at) ?></span>
                                        </div>
                                        <p class="<?= $isAdmin ? 'text-dark' : 'text-secondary' ?> small mb-0 pe-3">
                                            <?= Html::encode($komen->isi_komentar) ?>
                                        </p>
                                        
                                        <?php if (!$isAdmin && $komen->user_id == $currentUserId): ?>
                                            <button type="button" class="btn p-0 text-danger position-absolute top-0 end-0 mt-2 me-2 btn-delete-comment" onclick="deleteComment(<?= $komen->id ?>)">
                                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="border-top pt-3">
                            <textarea id="comment-text" class="form-control bg-light border py-3 mb-3" rows="3" placeholder="Tambahkan komentar atau pertanyaan..." required></textarea>
                            <button type="button" id="btn-send-comment" class="btn btn-primary px-4 py-2 fw-medium d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto">
                                <i data-lucide="send" style="width: 16px; height: 16px;"></i> Kirim Pesan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 position-sticky" style="top: 100px;">
                        <h5 class="fw-bold text-dark mb-4">Timeline Proses</h5>
                        <div class="d-flex flex-column">
                            <div class="d-flex gap-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="timeline-dot bg-completed"></div>
                                    <div class="timeline-line bg-completed"></div>
                                </div>
                                <div class="pb-3">
                                    <p class="fw-medium text-dark mb-0 small">Laporan Dibuat</p>
                                    <p class="text-black-50" style="font-size: 0.75rem;"><?= date('d M Y, H:i', $report->created_at) ?></p>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="timeline-dot <?= ($report->status == 'Selesai') ? 'bg-completed' : (($report->status == 'Menunggu') ? 'bg-pending' : 'bg-current') ?>"></div>
                                </div>
                                <div>
                                    <p class="fw-medium text-dark mb-0 small">Status Laporan: <?= Html::encode($report->status) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$commentApi = Url::to(['api/add-komentar', 'id' => $report->id]);
$deleteCommentApi = Url::to(['api/delete-komentar', 'id' => '']);
$lat = $report->latitude ?: '-3.9985';
$lng = $report->longitude ?: '122.5126';

$js = <<<JS
var mapDetail = L.map('map-detail').setView([$lat, $lng], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDetail);
L.marker([$lat, $lng]).addTo(mapDetail);

var chatContainer = document.getElementById("chat-container");
if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;

// AJAX Kirim Komentar (FIXED)
$('#btn-send-comment').on('click', function() {
    var textInput = $('#comment-text');
    var isi = textInput.val().trim();
    if(!isi) return;

    var btn = $(this);
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mengirim...');

    $.ajax({
        url: '$commentApi',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ isi_komentar: isi }),
        success: function(res) {
            btn.prop('disabled', false).html('<i data-lucide="send" style="width:16px;height:16px;"></i> Kirim Pesan');
            if(res && res.status === 'success') {
                textInput.val('');
                $('#no-comment-msg').remove();
                
                $('#chat-container').append(`
                    <div class="border rounded-3 p-3 mb-3 comment-bubble position-relative bg-light" id="comment-row-\${res.data.id}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold small text-dark">Anda</span>
                            <span class="text-secondary me-3" style="font-size: 0.75rem;">\${res.data.waktu}</span>
                        </div>
                        <p class="text-secondary small mb-0 pe-3">\${res.data.isi}</p>
                        <button type="button" class="btn p-0 text-danger position-absolute top-0 end-0 mt-2 me-2 btn-delete-comment" onclick="deleteComment(\${res.data.id})">
                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                `);
                lucide.createIcons();
                chatContainer.scrollTop = chatContainer.scrollHeight;
            } else {
                alert(res.message || 'Gagal mengirim pesan.');
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<i data-lucide="send" style="width:16px;height:16px;"></i> Kirim Pesan');
            alert('Gagal menyambung ke server.');
            lucide.createIcons();
        }
    });
});

// AJAX Hapus Komentar (User)
window.deleteComment = function(id) {
    if(!confirm("Hapus komentar Anda?")) return;
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
JS;
$this->registerJs($js);
?>