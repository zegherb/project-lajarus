<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Tentang LAJARUS';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'LAJARUS (Layanan Laporan Jalan Rusak) adalah platform pelaporan infrastruktur untuk wilayah Sulawesi Tenggara.';
$this->params['meta_keywords'] = 'lajarus, tentang, lapor jalan rusak, sulawesi tenggara, kendari, infrastruktur';
?>

<div class="site-about py-5">
    <div class="container">
        
        <!-- Bagian Atas: Hero Section -->
        <div class="row align-items-center mb-5 pb-5 border-bottom border-secondary-subtle">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <div class="d-flex justify-content-center justify-content-lg-start align-items-center gap-3 mb-4">
                    <div class="bg-primary rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px">
                        <span class="text-white fs-4 fw-bold">L</span>
                    </div>
                    <h1 class="display-5 fw-bold mb-0 text-primary">LAJARUS</h1>
                </div>
                
                <h2 class="h3 fw-semibold mb-3">Membangun Infrastruktur Lebih Baik, Bersama.</h2>
                <p class="lead text-body-secondary mb-4" style="line-height: 1.6;">
                    <strong>Layanan Laporan Jalan Rusak</strong> hadir sebagai jembatan digital interaktif antara masyarakat dan instansi berwenang. Kami berdedikasi untuk mempercepat respons perbaikan infrastruktur jalan demi kenyamanan, roda ekonomi, dan keselamatan berkendara di wilayah Sulawesi Tenggara.
                </p>
                
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3 mt-4">
                    <?= Html::a('Lapor Sekarang &nbsp; &#10148;', ['site/index'], ['class' => 'btn btn-primary btn-lg rounded-pill px-4 shadow-sm']) ?>
                    <?= Html::a('Hubungi Admin', ['site/contact'], ['class' => 'btn btn-outline-secondary btn-lg rounded-pill px-4']) ?>
                </div>
            </div>
            
            <div class="col-lg-6 text-center">
                <!-- Gambar Ilustrasi (Pakai Placeholder Otomatis yang aesthetic) -->
                <img src="https://placehold.co/600x400/0d6efd/ffffff?text=LAJARUS+Smart+City" 
                     alt="LAJARUS Illustration" 
                     class="img-fluid rounded-4 shadow-lg" 
                     style="max-height: 400px; width: 100%; object-fit: cover;">
            </div>
        </div>

        <!-- Bagian Bawah: Keunggulan Sistem -->
        <div class="text-center mb-5 mt-4">
            <h2 class="fw-bold mb-3">Mengapa Menggunakan LAJARUS?</h2>
            <p class="text-body-secondary">Platform ini dirancang dengan standar arsitektur <i>Software Engineering</i> modern untuk pengalaman pelaporan terbaik.</p>
        </div>

        <div class="row g-4 text-center">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-lift" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <span class="fs-1">📍</span>
                        </div>
                        <h5 class="fw-bold">Pemetaan Spasial (GIS)</h5>
                        <p class="text-body-secondary small mb-0">Terintegrasi langsung dengan API OpenStreetMap untuk memastikan titik koordinat lokasi kerusakan jalan terekam dengan akurasi tinggi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-lift" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <span class="fs-1">⏱️</span>
                        </div>
                        <h5 class="fw-bold">Real-time & Transparan</h5>
                        <p class="text-body-secondary small mb-0">Pantau terus status laporan Anda dengan rekam jejak Log Aktivitas yang jelas, mulai dari verifikasi hingga jalan selesai diperbaiki.</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-lift" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <span class="fs-1">🔗</span>
                        </div>
                        <h5 class="fw-bold">Web Service Terpusat</h5>
                        <p class="text-body-secondary small mb-0">Mengadopsi arsitektur REST dan SOAP tingkat <i>Enterprise</i> agar data pelaporan dapat saling terhubung lintas instansi secara aman.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Tambahan Sedikit CSS untuk efek hover pada card -->
<?php
$this->registerCss("
    .hover-lift:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 1rem 3rem rgba(0,0,0,.15) !important;
    }
");
?>