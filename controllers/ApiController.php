<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\SignupForm;
use yii\web\Response;

class ApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Mengatur response agar selalu menghasilkan format JSON
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    // Endpoint: POST /api/register
    public function actionRegister()
    {
        $model = new SignupForm();
        
        // Mengambil data JSON dari body request
        $model->load(Yii::$app->request->getBodyParams(), '');

        if ($model->validate() && $user = $model->signup()) {
            return [
                'status' => 'success',
                'message' => 'Registrasi berhasil! Silakan masuk.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ];
        }

        // Jika validasi gagal, kembalikan daftar pesan error
        Yii::$app->response->statusCode = 422;
        return [
            'status' => 'error',
            'errors' => $model->getErrors()
        ];
    }
    // Endpoint: POST /api/login
    public function actionLogin()
    {
        // Jika user sudah login, tolak akses ke endpoint ini
        if (!Yii::$app->user->isGuest) {
            return [
                'status' => 'error',
                'message' => 'Anda sudah login.'
            ];
        }

        $model = new \app\models\LoginForm();
        
        // Load data JSON dari body request
        $model->load(Yii::$app->request->getBodyParams(), '');

        if ($model->login()) {
            return [
                'status' => 'success',
                'message' => 'Login berhasil! Mengalihkan...',
                'data' => [
                    'id' => Yii::$app->user->identity->id,
                    'name' => Yii::$app->user->identity->name,
                    'role' => Yii::$app->user->identity->role,
                ]
            ];
        }

        // Jika validasi gagal (email/password salah)
        Yii::$app->response->statusCode = 401; // Unauthorized
        return [
            'status' => 'error',
            'errors' => $model->getErrors()
        ];
    }
    // Endpoint: POST /api/create-laporan
    public function actionCreateLaporan()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'];
        }

        $model = new \app\models\Laporan();
        
        if ($model->load(Yii::$app->request->post(), '')) {
            $model->user_id = Yii::$app->user->identity->id;
            $model->imageFile = \yii\web\UploadedFile::getInstanceByName('foto');
            
            // 1. VALIDASI PERTAMA (Mengecek file /tmp/ masih ada)
            if ($model->validate()) {
                
                // 2. JIKA ADA FOTO, PINDAHKAN
                if ($model->imageFile) {
                    $model->upload(); 
                    $model->imageFile = null; 
                }

                // 3. SIMPAN KE DATABASE 
                // (Gunakan false agar tidak terjadi double validation yang bikin error)
                if ($model->save(false)) {
                    $username = Yii::$app->user->identity->name;
                    Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => Yii::$app->user->id, 'tipe' => 'user', 'deskripsi' => "{$username} membuat laporan '{$model->judul}'", 'created_at' => time()])->execute();
                    Yii::$app->session->setFlash('success', 'Berhasil! Laporan jalan rusak Anda telah dikirim.');
                    return [
                        'status' => 'success',
                        'message' => 'Laporan berhasil dikirim!',
                        'data' => ['id' => $model->id]
                    ];
                   
                }
            }

            Yii::$app->response->statusCode = 422;
            return [
                'status' => 'error',
                'errors' => $model->getErrors()
            ];
        }

        return ['status' => 'error', 'message' => 'Tidak ada data yang dikirim.'];
    }
    // Endpoint: POST /api/update-laporan?id=XX
    public function actionUpdateLaporan($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'];
        }

        // Cari laporan milik user yang statusnya masih Menunggu
        $model = \app\models\Laporan::findOne([
            'id' => $id, 
            'user_id' => Yii::$app->user->identity->id
        ]);

        if (!$model || !in_array($model->status, ['Menunggu', 'Ditolak'])) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Laporan tidak ditemukan atau sudah tidak bisa diubah.'];
        }

        $oldFoto = $model->foto; // Simpan nama foto lama

        if ($model->load(Yii::$app->request->post(), '')) {
            $model->imageFile = \yii\web\UploadedFile::getInstanceByName('foto');
            
            if ($model->validate()) {
                // Jika user mengunggah foto baru
                if ($model->imageFile) {
                    // Hapus file foto lama dari server agar tidak jadi sampah
                    if ($oldFoto) {
                        $oldPath = Yii::getAlias('@webroot/uploads/') . $oldFoto;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $model->upload(); 
                    $model->imageFile = null; 
                } else {
                    // Jika tidak upload foto baru, pertahankan foto lama
                    $model->foto = $oldFoto;
                }

                if ($model->save(false)) {
                    $username = Yii::$app->user->identity->name;
                    Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => Yii::$app->user->id, 'tipe' => 'user', 'deskripsi' => "{$username} memperbarui laporan '{$model->judul}'", 'created_at' => time()])->execute();
                    Yii::$app->session->setFlash('success', 'Mantap! Laporan Anda berhasil diperbarui.');
                    return [
                        'status' => 'success',
                        'message' => 'Laporan berhasil diperbarui!'
                    ];
                    
                }
            }

            Yii::$app->response->statusCode = 422;
            return [
                'status' => 'error',
                'errors' => $model->getErrors()
            ];
        }

        return ['status' => 'error', 'message' => 'Tidak ada data yang dikirim.'];
    }
    // Endpoint: POST /api/admin-login
    public function actionAdminLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return ['status' => 'error', 'message' => 'Anda sudah login.'];
        }

        $model = new \app\models\LoginForm();
        $model->load(Yii::$app->request->getBodyParams(), '');

        if ($model->login()) {
            // Cek apakah yang login benar-benar ADMIN
            if (Yii::$app->user->identity->role === 'admin') {
                return [
                    'status' => 'success',
                    'message' => 'Berhasil masuk! Mengalihkan ke Dashboard...'
                ];
            } else {
                // Tendang kalau user biasa nyasar ke sini
                Yii::$app->user->logout();
                Yii::$app->response->statusCode = 403; // Forbidden
                return [
                    'status' => 'error',
                    'message' => 'Akses Ditolak! Anda bukan Administrator.'
                ];
            }
        }

        // Jika email/password salah
        Yii::$app->response->statusCode = 401; 
        return [
            'status' => 'error',
            'message' => 'Email atau password yang Anda masukkan salah.'
        ];
    }
    // 1. Endpoint Admin: Update Status & Prioritas Laporan
    public function actionAdminUpdateReport($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            Yii::$app->response->statusCode = 403;
            return ['status' => 'error', 'message' => 'Akses ditolak.'];
        }

        $report = \app\models\Laporan::findOne($id);
        if (!$report) {
            return ['status' => 'error', 'message' => 'Laporan tidak ditemukan.'];
        }

        // --- SIMPAN DATA LAMA SEBELUM DIUBAH ---
        $oldStatus = $report->status;
        $oldPriority = $report->is_priority;

        $params = Yii::$app->request->getBodyParams();
        $report->status = $params['status'] ?? $report->status;
        $report->is_priority = isset($params['is_priority']) ? (int)$params['is_priority'] : $report->is_priority;

        // --- MASUKKAN LOGIKA LOG KE DALAM BLOK SUCCESS ---
        if ($report->save(false)) {
            
            $adminName = Yii::$app->user->identity->name;
            $time = time();

            // 1. Cek apakah statusnya benar-benar berubah
            if ($report->status !== $oldStatus) {
                $statusBaru = strtolower($report->status); 
                Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'admin', 'deskripsi' => "Admin {$adminName} memperbarui status laporan '{$report->judul}'", 'created_at' => $time])->execute();
                Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'user', 'deskripsi' => "Admin memperbarui status laporan {$report->judul} menjadi {$statusBaru}", 'created_at' => $time])->execute();
            }

            // 2. Cek apakah prioritasnya diaktifkan (berubah jadi 1)
            if ($report->is_priority != $oldPriority && $report->is_priority == 1) {
                Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'admin', 'deskripsi' => "Admin {$adminName} menjadikan laporan '{$report->judul}' sebagai prioritas", 'created_at' => $time])->execute();
                Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'user', 'deskripsi' => "Laporan {$report->judul} dijadikan prioritas oleh admin", 'created_at' => $time])->execute();
            }

            return ['status' => 'success', 'message' => 'Laporan berhasil diperbarui!'];
        }

        return ['status' => 'error', 'message' => 'Gagal memperbarui data.'];
    }

    // 3. Endpoint Admin: Hapus Laporan Total (Permanen)
    public function actionAdminDeleteReport($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            Yii::$app->response->statusCode = 403;
            return ['status' => 'error', 'message' => 'Akses ditolak.'];
        }

        $report = \app\models\Laporan::findOne($id);
        if ($report) {
            if ($report->foto) {
                $filePath = Yii::getAlias('@webroot/uploads/') . $report->foto;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $adminName = Yii::$app->user->identity->name;
            // Untuk Log Admin
            Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'admin', 'deskripsi' => "Admin {$adminName} menghapus laporan dari {$report->user->name} berjudul '{$report->judul}'", 'created_at' => time()])->execute();
            // Untuk Notif di Halaman User terkait
            Yii::$app->db->createCommand()->insert('log_aktivitas', ['user_id' => $report->user_id, 'tipe' => 'user', 'deskripsi' => "Admin menghapus laporan {$report->judul} anda", 'created_at' => time()])->execute();
            $report->delete();
            Yii::$app->session->setFlash('success', 'Laporan berhasil dihapus secara permanen.');
            return ['status' => 'success', 'message' => 'Laporan berhasil dihapus.'];
        }

        return ['status' => 'error', 'message' => 'Laporan tidak ditemukan.'];
    }
    // FIX ENDPOINT: Kirim Komentar (Pastikan mengembalikan JSON murni tanpa bocor)
    public function actionAddKomentar($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'];
        }

        $params = Yii::$app->request->getBodyParams();
        if (empty($params['isi_komentar'])) {
            return ['status' => 'error', 'message' => 'Komentar tidak boleh kosong.'];
        }
        $laporan = \app\models\Laporan::findOne($id);
        if (!$laporan) {
            return ['status' => 'error', 'message' => 'Laporan tidak ditemukan.'];
        }

        $comment = new \app\models\KomentarLaporan();
        $comment->laporan_id = $id;
        $comment->user_id = Yii::$app->user->identity->id;
        $comment->isi_komentar = $params['isi_komentar'];
        $comment->created_at = time();

        if ($comment->save(false)) {
            $currentUser = Yii::$app->user->identity;
            $time = time();

            
            if ($currentUser->role === 'admin') {
                Yii::$app->db->createCommand()->insert('log_aktivitas', [
                    'user_id' => $laporan->user_id, 
                    'tipe' => 'admin', 
                    'deskripsi' => "Admin {$currentUser->name} membalas komentar {$laporan->user->name}", 
                    'created_at' => $time
                ])->execute();
                
                Yii::$app->db->createCommand()->insert('log_aktivitas', [
                    'user_id' => $laporan->user_id, 
                    'tipe' => 'user', 
                    'deskripsi' => "Admin membalas komentar {$laporan->user->name}", 
                    'created_at' => $time
                ])->execute();
            } else {
                Yii::$app->db->createCommand()->insert('log_aktivitas', [
                    'user_id' => $currentUser->id, 
                    'tipe' => 'user', 
                    'deskripsi' => "{$currentUser->name} mengirim komentar pada laporan '{$laporan->judul}'", 
                    'created_at' => $time
                ])->execute();
            }

            if (ob_get_length()) ob_clean();
            
            return [
                'status' => 'success',
                'message' => 'Komentar berhasil dikirim!',
                'data' => [
                    'id' => $comment->id,
                    'nama' => Yii::$app->user->identity->name,
                    'role' => Yii::$app->user->identity->role,
                    'isi' => \yii\helpers\Html::encode($comment->isi_komentar),
                    'waktu' => date('d M Y, H:i', $comment->created_at)

                ]
            ];
        
        }

        Yii::$app->response->statusCode = 422;
        return ['status' => 'error', 'message' => 'Gagal menyimpan komentar ke database.'];
    }

    // ENDPOINT BARU: Hapus Komentar (User & Admin)
    public function actionDeleteKomentar($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'];
        }

        $comment = \app\models\KomentarLaporan::findOne($id);
        if (!$comment) {
            return ['status' => 'error', 'message' => 'Komentar tidak ditemukan.'];
        }

        // Aturan: Admin bebas hapus, User hanya bisa hapus komentarnya sendiri
        if (Yii::$app->user->identity->role === 'admin' || $comment->user_id == Yii::$app->user->identity->id) {
            if ($comment->delete()) {
                return ['status' => 'success', 'message' => 'Komentar berhasil dihapus.'];
            }
        }

        Yii::$app->response->statusCode = 403;
        return ['status' => 'error', 'message' => 'Anda tidak memiliki akses untuk menghapus komentar ini.'];
    }
    // 1. Endpoint Admin: Jadikan Pengguna Sebagai Admin
    public function actionMakeAdmin($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            Yii::$app->response->statusCode = 403;
            return ['status' => 'error', 'message' => 'Akses ditolak.'];
        }

        $user = \app\models\User::findOne($id);
        if (!$user) {
            return ['status' => 'error', 'message' => 'Pengguna tidak ditemukan.'];
        }

        // Proteksi: Cegah merubah akun sendiri
        if ($user->id == Yii::$app->user->identity->id) {
            return ['status' => 'error', 'message' => 'Anda tidak bisa mengubah role Anda sendiri.'];
        }

        $user->role = 'admin';
        if ($user->save(false)) {
            Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => $user->id, 
                'tipe' => 'user', 
                'deskripsi' => "Admin memperbarui role {$user->name} menjadi admin", 
                'created_at' => time()
            ])->execute();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan ' . $user->name . ' sebagai Admin!'];
        }

        return ['status' => 'error', 'message' => 'Gagal memperbarui hak akses.'];
    }

    // 2. Endpoint Admin: Hapus Akun Pengguna
    public function actionDeleteUser($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            Yii::$app->response->statusCode = 403;
            return ['status' => 'error', 'message' => 'Akses ditolak.'];
        }

        $user = \app\models\User::findOne($id);
        if (!$user) {
            return ['status' => 'error', 'message' => 'Pengguna tidak ditemukan.'];
        }

        // Proteksi: Cegah bunuh diri (hapus akun sendiri) wkwk
        if ($user->id == Yii::$app->user->identity->id) {
            return ['status' => 'error', 'message' => 'Anda tidak bisa menghapus akun Anda sendiri.'];
        }

        if ($user->delete()) {
            Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => $userId, 
                'tipe' => 'admin', 
                'deskripsi' => "Admin {$adminName} menghapus akun {$user->name}", 
                'created_at' => time()
            ])->execute();
            return ['status' => 'success', 'message' => 'Akun pengguna berhasil dihapus permanen.'];
        }

        return ['status' => 'error', 'message' => 'Gagal menghapus pengguna.'];
    }
}