<?php
use yii\helpers\Html;
?>
<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row gy-5">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                        <span class="text-white fs-5 fw-bold">L</span>
                    </div>
                    <span class="fs-5 fw-bold">LAJARUS</span>
                </div>
                <p class="text-white-50 mb-0">Sistem Pelaporan Jalan Rusak untuk Sulawesi Tenggara yang Lebih Baik</p>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold mb-4">Tautan</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><?= Html::a('Masuk', ['site/login'], ['class' => 'text-white-50 text-decoration-none']) ?></li>
                    <li><?= Html::a('Daftar', ['site/signup'], ['class' => 'text-white-50 text-decoration-none']) ?></li>
                    <li><?= Html::a('Tentang Kami', ['site/about'], ['class' => 'text-white-50 text-decoration-none']) ?></li>
                    <li><?= Html::a('Kontak', ['site/contact'], ['class' => 'text-white-50 text-decoration-none']) ?></li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold mb-4">Hubungi Kami</h5>
                <ul class="list-unstyled text-white-50 d-flex flex-column gap-2">
                    <li>Email: info@lajarus.id</li>
                    <li>Telp: (021) 1234-5678</li>
                    <li>Alamat: Jl. Mangkerey, Kendari</li>
                </ul>
            </div>
        </div>
        <div class="border-top border-secondary mt-5 pt-4 text-center text-white-50">
            <p class="mb-0">&copy; <?= date('Y') ?> LAJARUS. All rights reserved.</p>
        </div>
    </div>
</footer>