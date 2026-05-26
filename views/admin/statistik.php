<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Statistik Sebaran - LAJARUS Admin';

// Load Icon Lucide & Leaflet
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', ['position' => View::POS_HEAD]);
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => View::POS_HEAD]);

$css = <<<CSS
@media (min-width: 992px) { .main-wrapper { padding-left: 256px; } }
.hover-shadow-md { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.hover-shadow-md:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
.legend-dot { width: 14px; height: 14px; border-radius: 50%; display: inline-block; border: 2px solid #fff; box-shadow: 0 0 0 1px rgba(0,0,0,0.1); }
.map-container { border-radius: 1rem; overflow: hidden; border: 1px solid #dee2e6; }
CSS;
$this->registerCss($css);

// Definisikan palet warna agar konsisten
$colors = [
    'Ringan' => '#0d6efd',       // Biru
    'Sedang' => '#ffc107',       // Kuning
    'Berat' => '#dc3545',        // Merah
    'Sangat Berat' => '#6f42c1'  // Ungu
];
?>

<?= $this->render('//layouts/_sidebar') ?>

<div class="bg-light min-vh-100" style="margin-top: -1.5rem; margin-bottom: -3rem; padding-bottom: 3rem;">
    <div class="main-wrapper">
        <div class="container-fluid py-4 px-lg-4 pt-5" style="max-width: 1400px;">
            
            <div class="mb-5">
                <h1 class="fs-3 fw-bold text-dark mb-1">Statistik Sebaran Kerusakan</h1>
                <p class="text-secondary">Peta visualisasi titik jalan rusak berdasarkan laporan pengguna</p>
            </div>

            <div class="row g-4 mb-4">
                
                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="text-secondary fw-bold mb-0">Rusak Ringan</h6>
                            <div class="legend-dot" style="background-color: <?= $colors['Ringan'] ?>;"></div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0"><?= $statsKategori['Ringan'] ?> <span class="fs-6 text-secondary fw-normal">Titik</span></h2>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="text-secondary fw-bold mb-0">Rusak Sedang</h6>
                            <div class="legend-dot" style="background-color: <?= $colors['Sedang'] ?>;"></div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0"><?= $statsKategori['Sedang'] ?> <span class="fs-6 text-secondary fw-normal">Titik</span></h2>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="text-secondary fw-bold mb-0">Rusak Berat</h6>
                            <div class="legend-dot" style="background-color: <?= $colors['Berat'] ?>;"></div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0"><?= $statsKategori['Berat'] ?> <span class="fs-6 text-secondary fw-normal">Titik</span></h2>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 hover-shadow-md">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="text-secondary fw-bold mb-0">Sangat Berat</h6>
                            <div class="legend-dot" style="background-color: <?= $colors['Sangat Berat'] ?>;"></div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0"><?= $statsKategori['Sangat Berat'] ?> <span class="fs-6 text-secondary fw-normal">Titik</span></h2>
                    </div>
                </div>

            </div>

            <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i data-lucide="map" class="text-primary" style="width: 22px; height: 22px;"></i> Peta Interaktif
                    </h5>
                    
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 rounded-3">
                        <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i> Refresh Peta
                    </button>
                </div>
                
                <div id="map-distribution" class="map-container shadow-sm" style="height: 600px; z-index: 1;"></div>
            </div>

        </div>
    </div>
</div>

<?php
// Konversi Array Data ke JSON untuk JavaScript
$pointsData = [];
foreach ($mapReports as $rep) {
    $pointsData[] = [
        'id' => $rep->id,
        'judul' => Html::encode($rep->judul),
        'lat' => (float)$rep->latitude,
        'lng' => (float)$rep->longitude,
        'status' => $rep->status,
        'kategori' => $rep->kategori->nama_kategori ?? 'Tidak Diketahui'
    ];
}
$jsonPoints = json_encode($pointsData);

$js = <<<JS
// 1. Inisialisasi Peta (Default koordinat Kendari)
var mapDist = L.map('map-distribution').setView([-3.9985, 122.5126], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(mapDist);

var locations = $jsonPoints;

// 2. Mapping Titik
locations.forEach(function(point) {
    // Tentukan Warna sesuai Kategori
    var dotColor = '#6c757d'; // Abu-abu default jika tidak masuk kategori
    if (point.kategori === 'Ringan') dotColor = '#0d6efd';
    else if (point.kategori === 'Sedang') dotColor = '#ffc107';
    else if (point.kategori === 'Berat') dotColor = '#dc3545';
    else if (point.kategori === 'Sangat Berat') dotColor = '#6f42c1';

    // Buat L.circleMarker
    var circle = L.circleMarker([point.lat, point.lng], {
        radius: 9,
        fillColor: dotColor,
        color: '#ffffff',
        weight: 2,
        opacity: 1,
        fillOpacity: 0.9
    }).addTo(mapDist);

    // Bikin Jendela Popup saat Titik di-Klik
    var detailUrl = '/admin/report-detail/' + point.id;
    var popupContent = `
        <div style="font-family: inherit; min-width: 180px; padding: 4px;">
            <h6 style="font-weight: bold; margin-bottom: 8px; color:#212529; font-size:14px;">\${point.judul}</h6>
            <div style="margin-bottom: 12px; font-size: 12px; color:#6c757d;">
                <div style="margin-bottom: 4px;">
                    <span style="display:inline-block; width: 60px;">Tingkat</span> : 
                    <span class="badge" style="background-color:\${dotColor}; color:#fff;">\${point.kategori}</span>
                </div>
                <div>
                    <span style="display:inline-block; width: 60px;">Status</span> : 
                    <strong>\${point.status}</strong>
                </div>
            </div>
            <a href="\${detailUrl}" class="btn btn-primary btn-sm w-100 text-white" style="font-size:12px; padding: 4px 0; text-decoration:none;">Lihat Detail Laporan</a>
        </div>
    `;
    circle.bindPopup(popupContent, { maxWidth: 300 });
});

// Efek Tombol Refresh
$('.btn-outline-secondary').on('click', function() {
    mapDist.setView([-3.9985, 122.5126], 13);
});
JS;
$this->registerJs($js);
?>