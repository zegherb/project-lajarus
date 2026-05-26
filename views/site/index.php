<?php

use yii\helpers\Html;
use yii\web\View;

/** @var yii\web\View $this */

$this->title = 'LAJARUS - Pelaporan Jalan Rusak';

// Memuat library Lucide Icons via CDN
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', View::POS_READY);
?>

<div style="background: linear-gradient(to bottom, #f8f9fa, #ffffff)">
    <!-- Hero Section -->
    <section class="container py-5">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 text-dark">
                    Laporkan Jalan Rusak di <span class="text-primary">Sekitar Anda</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    LAJARUS memudahkan Anda melaporkan kondisi jalan rusak untuk perbaikan yang lebih cepat dan efisien.
                </p>
                <div class="d-flex gap-3">
                    <?= Html::a('Mulai Sekarang <i data-lucide="arrow-right" class="ms-2" style="width:20px; height:20px;"></i>', ['site/signup'], [
                        'class' => 'btn btn-primary btn-lg d-flex align-items-center gap-2 shadow'
                    ]) ?>
                    <?= Html::a('Masuk', ['site/login'], [
                        'class' => 'btn btn-outline-primary btn-lg'
                    ]) ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #0d6efd, #0a58ca)">
                    <div class="bg-white rounded-3 p-4">
                        <!-- Feature 1 -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded" style="width: 48px; height: 48px; background-color: #e9ecef">
                                <i data-lucide="map-pin" class="text-secondary" style="width:24px; height:24px;"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 text-dark">Lokasi Otomatis</p>
                                <p class="text-secondary small mb-0">GPS Terintegrasi</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded bg-primary bg-opacity-10" style="width: 48px; height: 48px">
                                <i data-lucide="file-text" class="text-primary" style="width:24px; height:24px;"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 text-dark">Laporan Mudah</p>
                                <p class="text-secondary small mb-0">Proses Cepat & Simpel</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded bg-success bg-opacity-10" style="width: 48px; height: 48px">
                                <i data-lucide="trending-up" class="text-success" style="width:24px; height:24px;"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 text-dark">Tracking Real-time</p>
                                <p class="text-secondary small mb-0">Pantau Status Laporan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3 text-dark">Fitur Unggulan</h2>
                <p class="lead text-secondary">
                    Kemudahan dalam melaporkan dan memantau kondisi jalan
                </p>
            </div>

            <div class="row g-4">
                <!-- Feature Card 1 -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light p-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-center bg-primary rounded mb-4" style="width: 64px; height: 64px">
                            <i data-lucide="map-pin" class="text-white" style="width:32px; height:32px;"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3 text-dark">Lokasi Akurat</h3>
                        <p class="text-secondary mb-0">
                            Gunakan GPS untuk menandai lokasi jalan rusak dengan presisi tinggi
                        </p>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light p-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-center bg-secondary rounded mb-4" style="width: 64px; height: 64px">
                            <i data-lucide="file-text" class="text-white" style="width:32px; height:32px;"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3 text-dark">Laporan Cepat</h3>
                        <p class="text-secondary mb-0">
                            Buat laporan dalam hitungan menit dengan formulir yang mudah digunakan
                        </p>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light p-4 shadow-sm">
                        <div class="d-flex align-items-center justify-content-center bg-success rounded mb-4" style="width: 64px; height: 64px">
                            <i data-lucide="check-circle" class="text-white" style="width:32px; height:32px;"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3 text-dark">Status Real-time</h3>
                        <p class="text-secondary mb-0">
                            Pantau perkembangan laporan Anda dari pengajuan hingga selesai
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 text-white" style="background: linear-gradient(to right, #0d6efd, #0a58ca)">
        <div class="container py-4">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="display-4 fw-bold mb-2">1+</div>
                    <div class="text-white-50 fs-5">Laporan Diterima</div>
                </div>
                <div class="col-md-4">
                    <div class="display-4 fw-bold mb-2">N/A</div>
                    <div class="text-white-50 fs-5">Jalan Diperbaiki</div>
                </div>
                <div class="col-md-4">
                    <div class="display-4 fw-bold mb-2">1+</div>
                    <div class="text-white-50 fs-5">Pengguna Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container py-5 text-center" style="max-width: 800px">
            <h2 class="fw-bold mb-4 text-dark">
                Mulai Berkontribusi untuk Jalan yang Lebih Baik
            </h2>
            <p class="lead text-secondary mb-5">
                Bergabunglah dengan pengguna lainnya dalam meningkatkan kualitas infrastruktur jalan
            </p>
            <?= Html::a('Daftar Sekarang <i data-lucide="arrow-right" class="ms-2" style="width:20px; height:20px;"></i>', ['site/signup'], [
                'class' => 'btn btn-warning btn-lg text-dark d-inline-flex align-items-center gap-2 shadow px-5 py-3 fw-bold'
            ]) ?>
        </div>
    </section>
</div>