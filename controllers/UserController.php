<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Laporan;

class UserController extends Controller
{
    /**
     * Membatasi akses hanya untuk user yang sudah login
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Tanda '@' artinya khusus user yang terautentikasi (login)
                    ],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        // Jalankan fungsi bawaan Yii2 dulu
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 1. Tendang kalau belum login
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login'])->send();
            return false;
        }

        // 2. 🌟 BLOKIR ADMIN MASUK KE AREA USER 🌟
        if (Yii::$app->user->identity->role === 'admin') {
            Yii::$app->session->setFlash('error', 'Akses Ditolak! Admin tidak memiliki akses ke portal Pengguna.');
            $this->redirect(['admin/dashboard'])->send();
            return false;
        }

        return true;
    }

    public function actionDashboard()
    {
        $userId = Yii::$app->user->identity->id;
        $userName = Yii::$app->user->identity->name;
        
        // Mengambil Laporan Terbaru milik user ini
        $recentReports = Laporan::find()
            ->with('kategori') // Pastikan relasi kategori ada di model Laporan
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        // Hitung statistik (Opsional: dinamis dari database)
        $totalLaporan = Laporan::find()->where(['user_id' => $userId])->count();
        $dalamProses = Laporan::find()->where(['user_id' => $userId, 'status' => 'Proses'])->count();
        $selesai = Laporan::find()->where(['user_id' => $userId, 'status' => 'Selesai'])->count();
        $menunggu = Laporan::find()->where(['user_id' => $userId, 'status' => 'Menunggu'])->count();

        $stats = [
            ['icon' => 'file-text', 'label' => 'Total Laporan', 'value' => $totalLaporan, 'color' => 'bg-primary'],
            ['icon' => 'clock', 'label' => 'Dalam Proses', 'value' => $dalamProses, 'color' => 'bg-secondary'],
            ['icon' => 'check-circle', 'label' => 'Selesai', 'value' => $selesai, 'color' => 'bg-success'],
            ['icon' => 'alert-circle', 'label' => 'Menunggu', 'value' => $menunggu, 'color' => 'bg-warning'],
        ];

        return $this->render('dashboard', [
            'userName' => $userName,
            'recentReports' => $recentReports,
            'stats' => $stats
        ]);
    }
    public function actionNewReport()
    {
        // Ambil data kategori untuk dropdown
        $kategori = \app\models\KategoriKerusakan::find()->all();
        return $this->render('new-report', ['kategori' => $kategori]);
    }
    // ACTION: Hapus Laporan
    public function actionDeleteReport($id)
    {
        $report = Laporan::findOne([
            'id' => $id, 
            'user_id' => Yii::$app->user->identity->id
        ]);

        // Cek apakah laporan ada DAN statusnya masih "Menunggu" atau "Ditolak"
        if ($report && in_array($report->status, ['Menunggu', 'Ditolak'])) {
            // Hapus file foto dari folder uploads
            if ($report->foto) {
                $filePath = Yii::getAlias('@webroot/uploads/') . $report->foto;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $report->delete();
            Yii::$app->session->setFlash('success', 'Laporan berhasil dihapus.');
        } else {
            Yii::$app->session->setFlash('error', 'Laporan tidak dapat dihapus karena sedang diproses atau sudah selesai.');
        }

        return $this->redirect(['dashboard']);
    }

    // ACTION: Halaman Update Laporan
    public function actionUpdateReport($id)
    {
        $model = Laporan::findOne([
            'id' => $id, 
            'user_id' => Yii::$app->user->identity->id
        ]);

        // Kunci akses jika status bukan Menunggu atau Ditolak
        if (!$model || !in_array($model->status, ['Menunggu', 'Ditolak'])) {
            Yii::$app->session->setFlash('error', 'Laporan sudah tidak bisa diedit karena sedang diproses atau sudah selesai.');
            return $this->redirect(['report-detail', 'id' => $id]);
        }

        $kategori = \app\models\KategoriKerusakan::find()->all();

        return $this->render('update-report', [
            'model' => $model,
            'kategori' => $kategori
        ]);
    }
    public function actionReportDetail($id)
    {
        $report = Laporan::findOne([
            'id' => $id, 
            'user_id' => Yii::$app->user->identity->id
        ]);

        if (!$report) {
            throw new \yii\web\NotFoundHttpException('Laporan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Ambil riwayat komentar untuk laporan ini
        $komentar = \app\models\KomentarLaporan::find()
            ->where(['laporan_id' => $id])
            ->orderBy(['created_at' => SORT_ASC])
            ->with('user')
            ->all();

        return $this->render('report-detail', [
            'report' => $report,
            'komentar' => $komentar,
        ]);
    }
    public function actionReports()
    {
        $userId = Yii::$app->user->identity->id;
        
        // Ambil data menggunakan Query untuk Paginasi
        $query = Laporan::find()->where(['user_id' => $userId])->orderBy(['created_at' => SORT_DESC]);
        
        // Atur Paginasi (misal: 10 laporan per halaman)
        $pagination = new \yii\data\Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);
        
        $reports = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('reports', [
            'reports' => $reports,
            'pagination' => $pagination,
        ]);
    }
     public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $userId = Yii::$app->user->identity->id;
        $user = \app\models\User::findOne($userId);

        // Hitung statistik laporan user ini
        $totalLaporan = \app\models\Laporan::find()->where(['user_id' => $userId])->count();
        $selesai = \app\models\Laporan::find()->where(['user_id' => $userId, 'status' => 'Selesai'])->count();
        $proses = \app\models\Laporan::find()->where(['user_id' => $userId, 'status' => 'Proses'])->count();

        return $this->render('profile', [
            'user' => $user,
            'stats' => [
                'total' => $totalLaporan,
                'selesai' => $selesai,
                'proses' => $proses,
            ]
        ]);
    }
    // ACTION: Proses Update Data Profil
    public function actionUpdateProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->identity->id;
        $user = \app\models\User::findOne($userId);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            $user->name = $post['name'] ?? $user->name;
            $user->phone = $post['phone'] ?? $user->phone;
            $user->address = $post['address'] ?? $user->address;

            if ($user->save(false)) {
                Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => Yii::$app->user->id, 
                'tipe' => 'user', 
                'deskripsi' => "Anda memperbarui profil", 
                'created_at' => time()
        ])->execute();
                Yii::$app->session->setFlash('success', 'Profil Anda berhasil diperbarui!');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui profil.');
            }
        }

        return $this->redirect(['profile']);
    }

    // ACTION: Proses Ubah Password
    public function actionChangePassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->identity->id;
        $user = \app\models\User::findOne($userId);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $oldPassword = $post['old_password'] ?? '';
            $newPassword = $post['new_password'] ?? '';
            $confirmPassword = $post['confirm_password'] ?? '';

            // 1. Validasi keaslian password lama
            if (!$user->validatePassword($oldPassword)) {
                Yii::$app->session->setFlash('error', 'Password lama yang Anda masukkan salah.');
                return $this->redirect(['profile']);
            }

            // 2. Validasi kesesuaian password baru
            if ($newPassword !== $confirmPassword) {
                Yii::$app->session->setFlash('error', 'Konfirmasi password baru tidak cocok.');
                return $this->redirect(['profile']);
            }

            // 3. Set enkripsi password baru (Gunakan fungsi setPassword bawaan model User)
            if (method_exists($user, 'setPassword')) {
                $user->setPassword($newPassword);
            } else {
                $user->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
            }

            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', 'Password Anda berhasil diubah!');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengubah password.');
            }
        }

        return $this->redirect(['profile']);
    }
    public function actionAktivitas()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        try {
            $soapService = new \app\controllers\SoapController('soap', Yii::$app);
            // Kirim parameter role user agar disaring murni hak miliknya saja
            $jsonResponse = $soapService->getLogAktivitas(Yii::$app->user->id, 'user'); 
            $logs = json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            $logs = [];
        }

        return $this->render('aktivitas', ['logs' => $logs]);
    }
    

}