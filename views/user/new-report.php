<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Buat Laporan Baru - LAJARUS';

// Load Icon Lucide
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);

// Load CSS & JS Leaflet.js
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', ['position' => View::POS_HEAD]);
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => View::POS_HEAD]);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
/* Pastikan input background tetap abu-abu muda di light mode */
.bg-input-background { background-color: #f8f9fa !important; color: #212529 !important; }
.bg-input-background::placeholder { color: #6c757d !important; }

.image-preview-container { position: relative; display: inline-block; }
.image-preview-container img { width: 100%; height: 128px; object-fit: cover; border-radius: 0.5rem; }
.remove-image-btn { position: absolute; top: -10px; right: -10px; border-radius: 50%; width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center; }

/* Tambahan CSS untuk memastikan upload box tetap terang */
#upload-box { background-color: #f8f9fa !important; border-color: #dee2e6 !important; }
#upload-box p.text-secondary { color: #6c757d !important; }
#upload-box p.text-black-50 { color: rgba(0, 0, 0, 0.5) !important; }
#upload-box .bg-primary.bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
CSS;
$this->registerCss($css);
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container py-4 px-lg-4 pt-5" style="max-width: 900px;">
            
            <div class="mb-4">
                <h1 class="fs-3 fw-bold text-dark mb-1">Buat Laporan Baru</h1>
                <p class="text-secondary">Laporkan kondisi jalan rusak di sekitar Anda</p>
            </div>

            <form id="report-form" enctype="multipart/form-data">
                <div id="alert-container"></div>
                
                <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                    <h4 class="fw-bold text-dark mb-4">Informasi Laporan</h4>
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control bg-input-background py-3" placeholder="Contoh: Jalan Berlubang di Jl. Sudirman" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control bg-input-background py-3" rows="4" placeholder="Jelaskan kondisi kerusakan jalan secara detail..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Tingkat Kerusakan <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select bg-input-background py-3" required>
                                <option value="" class="text-secondary">Pilih tingkat kerusakan</option>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat->id ?>" class="text-dark"><?= Html::encode($kat->nama_kategori) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                    <h4 class="fw-bold text-dark mb-4">Foto Kerusakan</h4>
                    
                    <div class="border border-2 border-dashed rounded-3 p-5 text-center position-relative mb-3 hover-shadow" id="upload-box" style="cursor: pointer;">
                        <input type="file" name="foto" id="foto-input" class="d-none" accept="image/*">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                <i data-lucide="camera" class="text-primary" style="width: 32px; height: 32px;"></i>
                            </div>
                            <p class="fw-bold text-dark mb-1">Pilih dari Galeri</p>
                            <p class="text-secondary small mb-0">Klik area ini untuk mengunggah bukti jalan rusak</p>
                            <p class="text-black-50" style="font-size: 0.75rem;">PNG, JPG hingga 10MB</p>
                        </div>
                    </div>
                    <div class="row g-3" id="preview-container"></div>
                </div>

                <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                    <h4 class="fw-bold text-dark mb-4">Lokasi Kerusakan</h4>
                    
                    <button type="button" id="btn-get-location" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-2 mb-4 w-100 py-2 fw-medium">
                        <i data-lucide="crosshair" style="width: 18px; height: 18px;"></i>
                        Deteksi Lokasi Otomatis (GPS)
                    </button>

                    <div id="map" class="rounded-3 border shadow-sm mb-4" style="height: 350px; z-index: 1;"></div>

                    <div class="mb-2">
                        <label class="form-label fw-medium text-dark">Alamat Detail <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i data-lucide="map-pin" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 20px; height: 20px;"></i>
                            <input type="text" name="alamat_lokasi" id="alamat_lokasi" class="form-control bg-input-background py-3" style="padding-left: 2.75rem" placeholder="Geser pin di peta untuk mengisi otomatis..." required>
                        </div>
                        <small class="text-secondary mt-2 d-block fw-medium" id="coord-text">Geser pin merah di peta ke titik jalan yang rusak.</small>
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="d-flex gap-3 mt-5">
                    <a href="<?= Url::to(['user/dashboard']) ?>" class="btn btn-light border py-3 px-4 fw-medium text-dark">Batal</a>
                    <button type="submit" class="btn btn-primary py-3 px-5 fw-bold d-flex align-items-center justify-content-center gap-2 flex-grow-1">
                        <i data-lucide="send" style="width: 20px; height: 20px;"></i>
                        Kirim Laporan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php
$apiUrl = Url::to(['api/create-laporan']);
$dashboardUrl = Url::to(['user/dashboard']);

$js = <<<JS
// ==========================================
// 1. LOGIKA UPLOAD FOTO & KAMERA
// ==========================================
$('#upload-box').on('click', function(e) {
    if (e.target.id !== 'foto-input') {
        $('#foto-input')[0].click(); 
    }
});

$('#foto-input').on('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-container').html(`
                <div class="col-12 col-sm-6">
                    <div class="image-preview-container w-100">
                        <img src="\${e.target.result}" class="border shadow-sm w-100" style="height: 200px;" alt="Preview">
                        <button type="button" class="btn btn-danger remove-image-btn" id="remove-img">
                            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
            `);
            lucide.createIcons();
            $('#upload-box').addClass('d-none');
        }
        reader.readAsDataURL(file);
    }
});

$(document).on('click', '#remove-img', function() {
    $('#foto-input').val(''); 
    $('#preview-container').empty();
    $('#upload-box').removeClass('d-none');
});


// ==========================================
// 2. LOGIKA LEAFLET JS (PETA INTERAKTIF)
// ==========================================
var defaultLat = -3.9985;
var defaultLng = 122.5126;

var map = L.map('map').setView([defaultLat, defaultLng], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    maxZoom: 19
}).addTo(map);

var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

function updateLocation(lat, lng) {
    $('#latitude').val(lat);
    $('#longitude').val(lng);
    $('#coord-text').text('Titik Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6));

    $('#alamat_lokasi').attr('placeholder', 'Mencari detail alamat...');
    $.ajax({
        url: `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=\${lat}&lon=\${lng}`,
        method: 'GET',
        success: function(data) {
            if(data && data.display_name) {
                $('#alamat_lokasi').val(data.display_name);
            }
        }
    });
}

map.on('click', function(e) {
    marker.setLatLng(e.latlng);
    updateLocation(e.latlng.lat, e.latlng.lng);
});

marker.on('dragend', function(e) {
    var position = marker.getLatLng();
    updateLocation(position.lat, position.lng);
});

$('#btn-get-location').on('click', function() {
    var btn = $(this);
    var originalHtml = btn.html();
    
    if (navigator.geolocation) {
        btn.html('<span class="spinner-border spinner-border-sm"></span> Mencari Titik...');
        
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
            updateLocation(lat, lng);
            
            btn.html(originalHtml);
        }, function(error) {
            alert('Akses lokasi ditolak atau gagal. Pastikan GPS HP Anda menyala.');
            btn.html(originalHtml);
        });
    } else {
        alert("Perangkat Anda tidak mendukung fitur lokasi otomatis.");
    }
});


// ==========================================
// 3. LOGIKA SUBMIT LAPORAN
// ==========================================
$('#report-form').on('submit', function(e) {
    e.preventDefault();
    
    if (!$('#latitude').val() || !$('#longitude').val()) {
        alert("Silakan tentukan titik lokasi pada peta terlebih dahulu.");
        return;
    }
    
    var formData = new FormData(this);
    var alertContainer = $('#alert-container');
    var submitBtn = $(this).find('button[type="submit"]');
    var originalBtnHtml = submitBtn.html();
    
    alertContainer.empty();
    submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Mengirim Laporan...').prop('disabled', true);

    $.ajax({
        url: '$apiUrl',
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false, 
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                alertContainer.html('<div class="alert alert-success">' + response.message + '</div>');
                setTimeout(function() {
                    window.location.href = '$dashboardUrl';
                }, 1500);
            }
        },
        error: function(xhr) {
            var errorMsgs = '';
            var resp = xhr.responseJSON;
            if (resp && resp.errors) {
                Object.values(resp.errors).forEach(function(err) {
                    errorMsgs += '<li>' + err + '</li>';
                });
            } else {
                errorMsgs = '<li>Gagal mengirim laporan. Pastikan foto tidak terlalu besar (Max 5MB).</li>';
            }
            alertContainer.html('<div class="alert alert-danger"><ul class="mb-0">' + errorMsgs + '</ul></div>');
            submitBtn.html(originalBtnHtml).prop('disabled', false);
        }
    });
});
JS;
$this->registerJs($js);
?>