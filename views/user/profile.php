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
                        
                        <h5 class="fw-bold text-dark mb-1" id="display_name"><?= Html::encode($user->name) ?></h5>
                        <p class="text-secondary small mb-4"><?= Html::encode($user->email) ?></p>

                        <div class="border-top pt-4">
                            <div class="mb-3">
                                <h3 class="fw-bold text-dark mb-0"><?= $stats['total'] ?? 0 ?></h3>
                                <p class="text-secondary small">Total Laporan</p>
                            </div>
                            <div class="row border-top pt-3 text-center">
                                <div class="col-6 border-end">
                                    <h4 class="fw-bold text-success mb-0"><?= $stats['selesai'] ?? 0 ?></h4>
                                    <p class="text-secondary small" style="font-size: 0.75rem;">Selesai</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="fw-bold text-warning mb-0"><?= $stats['proses'] ?? 0 ?></h4>
                                    <p class="text-secondary small" style="font-size: 0.75rem;">Proses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                        <h4 class="fw-bold text-dark mb-4">Informasi Pribadi</h4>
                        
                        <form id="form-update-profile">
                            <input type="hidden" id="user_id" value="<?= Html::encode($user->id) ?>">

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Nama Lengkap</label>
                                <div class="position-relative">
                                    <i data-lucide="user" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" id="input_name" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" value="<?= Html::encode($user->name) ?>" required>
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
                                    <input type="text" id="input_phone" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan nomor telepon" value="<?= Html::encode($user->phone ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Alamat</label>
                                <div class="position-relative">
                                    <i data-lucide="map-pin" class="position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem; width: 18px; height: 18px;"></i>
                                    <input type="text" id="input_address" class="form-control bg-input-background py-3" style="padding-left: 2.75rem;" placeholder="Masukkan alamat lengkap" value="<?= Html::encode($user->address ?? '') ?>">
                                </div>
                            </div>

                            <button type="submit" id="btn-save-profile" class="btn btn-primary w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mt-4 rounded-3 shadow-sm">
                                <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    <div class="card bg-white border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold text-dark mb-4">Ubah Password</h4>
                        
                        <form id="form-change-password">
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Password Lama</label>
                                <input type="password" id="input_old_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Password Baru</label>
                                <input type="password" id="input_new_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-dark small fw-medium">Konfirmasi Password Baru</label>
                                <input type="password" id="input_confirm_password" class="form-control bg-input-background py-3" placeholder="••••••••" required>
                            </div>
                            
                            <button type="submit" id="btn-save-password" class="btn btn-warning text-white px-4 py-2 fw-medium rounded-3">
                                Ubah Password
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
// SCRIPT JAVASCRIPT UNTUK MENGIRIM SOAP REQUEST
$js = <<<JS
// ==========================================
// 1. SCRIPT UPDATE PROFIL VIA SOAP API
// ==========================================
document.getElementById('form-update-profile').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('user_id').value;
    const name = document.getElementById('input_name').value;
    const phone = document.getElementById('input_phone').value;
    const address = document.getElementById('input_address').value;
    
    const btn = document.getElementById('btn-save-profile');
    btn.innerHTML = 'Menyimpan...';
    btn.disabled = true;

    // KUNCI UTAMA: Paksa pakai http:// agar cocok 100% dengan cetakan WSDL
    // const currentNamespace = 'http://' + window.location.host + '/soap/index?ws=1';
    const currentNamespace = window.location.origin + '/soap/index?ws=1';

    const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="\${currentNamespace}">
        <SOAP-ENV:Body>
            <ns1:updateUserProfile>
                <id>\${id}</id>
                <name>\${name}</name>
                <phone>\${phone}</phone>
                <address>\${address}</address>
            </ns1:updateUserProfile>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>`;

    fetch('/soap/index?ws=1', {
        method: 'POST',
        headers: { 'Content-Type': 'text/xml; charset=utf-8' },
        body: xmlBody
    })
    .then(response => response.text())
    .then(str => {
        const parser = new window.DOMParser();
        const xmlDoc = parser.parseFromString(str, "text/xml");
        
        // DETEKSI FATAL ERROR DARI SERVER (SOAP FAULT)
        const faultNode = xmlDoc.getElementsByTagName("faultstring")[0];
        if (faultNode) {
            alert("❌ FATAL ERROR SERVER: " + faultNode.textContent);
            btn.innerHTML = '<i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan API';
            btn.disabled = false;
            lucide.createIcons();
            return; // Hentikan proses
        }
        
        // JIKA AMAN, LANJUT BACA STATUS KITA
        const statusNode = xmlDoc.getElementsByTagName("status")[0];
        const messageNode = xmlDoc.getElementsByTagName("message")[0];
        
        // Pakai .textContent agar tidak crash jika tag XML-nya kosong
        const status = statusNode ? statusNode.textContent : 'error';
        const message = messageNode ? messageNode.textContent : 'Pesan tidak terdeteksi';

        if(status === 'success') {
            alert("✅ BERHASIL: " + message);
            location.reload(); 
        } else {
            alert("❌ GAGAL: " + message);
            btn.innerHTML = '<i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan API';
            btn.disabled = false;
            lucide.createIcons();
        }
    })
    .catch(error => {
        console.error('Error Jaringan:', error);
        alert('Terjadi kesalahan jaringan! Cek console browser.');
        btn.innerHTML = '<i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Perubahan API';
        btn.disabled = false;
        lucide.createIcons();
    });
});

// ==========================================
// 2. SCRIPT UBAH PASSWORD VIA SOAP API
// ==========================================
document.getElementById('form-change-password').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const id = document.getElementById('user_id').value;
    const oldPass = document.getElementById('input_old_password').value;
    const newPass = document.getElementById('input_new_password').value;
    const confPass = document.getElementById('input_confirm_password').value;
    
    const btn = document.getElementById('btn-save-password');
    btn.innerHTML = 'Memproses...';
    btn.disabled = true;

    // KUNCI UTAMA: Paksa pakai http://
    // const currentNamespace = 'http://' + window.location.host + '/soap/index?ws=1';
    const currentNamespace = window.location.origin + '/soap/index?ws=1';

    const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="\${currentNamespace}">
        <SOAP-ENV:Body>
            <ns1:changeUserPassword>
                <id>\${id}</id>
                <oldPassword>\${oldPass}</oldPassword>
                <newPassword>\${newPass}</newPassword>
                <confirmPassword>\${confPass}</confirmPassword>
            </ns1:changeUserPassword>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>`;

    fetch('/soap/index?ws=1', {
        method: 'POST',
        headers: { 'Content-Type': 'text/xml; charset=utf-8' },
        body: xmlBody
    })
    .then(response => response.text())
    .then(str => {
        const parser = new window.DOMParser();
        const xmlDoc = parser.parseFromString(str, "text/xml");
        
        // DETEKSI FATAL ERROR
        const faultNode = xmlDoc.getElementsByTagName("faultstring")[0];
        if (faultNode) {
            alert("❌ FATAL ERROR SERVER: " + faultNode.textContent);
            btn.innerHTML = 'Ubah Password API';
            btn.disabled = false;
            return;
        }
        
        const statusNode = xmlDoc.getElementsByTagName("status")[0];
        const messageNode = xmlDoc.getElementsByTagName("message")[0];
        
        const status = statusNode ? statusNode.textContent : 'error';
        const message = messageNode ? messageNode.textContent : 'Pesan tidak terdeteksi';

        if(status === 'success') {
            alert("✅ BERHASIL: " + message);
            document.getElementById('form-change-password').reset(); 
        } else {
            alert("❌ GAGAL: " + message);
        }
        
        btn.innerHTML = 'Ubah Password API';
        btn.disabled = false;
    })
    .catch(error => {
        console.error('Error Jaringan:', error);
        alert('Terjadi kesalahan jaringan! Cek console browser.');
        btn.innerHTML = 'Ubah Password API';
        btn.disabled = false;
    });
});
JS;

$this->registerJs($js);
?>