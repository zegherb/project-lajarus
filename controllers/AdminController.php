<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AdminController extends Controller
{
    public function actionLogin()
    {
        // Pengecekan sesi
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role === 'admin') {
                return $this->redirect(['admin/dashboard']);
            } else {
                return $this->redirect(['user/dashboard']);
            }
        }

        return $this->render('login');
    }

    public function actionDashboard()
    {
        // Kunci akses Hanya Admin yang bisa masuk
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        // Hitung Data Statistik Utama
        $totalLaporan = \app\models\Laporan::find()->count();
        $totalUser = \app\models\User::find()->where(['role' => 'user'])->count();
        $totalSelesai = \app\models\Laporan::find()->where(['status' => 'Selesai'])->count();
        $totalProses = \app\models\Laporan::find()->where(['status' => 'Proses'])->count();

        // Hitung Data untuk Quick Actions
        $countMenunggu = \app\models\Laporan::find()->where(['status' => 'Menunggu'])->count();
        $countDiverifikasi = \app\models\Laporan::find()->where(['status' => 'Diverifikasi'])->count();
        $countPrioritas = \app\models\Laporan::find()->where(['is_priority' => 1])->count();

        // Ambil 5 Laporan Terbaru (Gabungkan dengan data user pelapor)
        $recentReports = \app\models\Laporan::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->with('user')
            ->all();

        // Ambil 3 Komentar Terbaru
        $recentComments = \app\models\KomentarLaporan::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->with(['user'])
            ->all();

        return $this->render('dashboard', [
            'stats' => [
                'total_laporan' => $totalLaporan,
                'total_user' => $totalUser,
                'total_selesai' => $totalSelesai,
                'total_proses' => $totalProses,
                'menunggu' => $countMenunggu,
                'diverifikasi' => $countDiverifikasi,
                'prioritas' => $countPrioritas
            ],
            'recentReports' => $recentReports,
            'recentComments' => $recentComments
        ]);
    }

    // Halaman Detail Laporan Khusus Admin
    public function actionReportDetail($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        // Ambil laporan beserta data user pelapornya
        $report = \app\models\Laporan::find()->where(['id' => $id])->with('user')->one();
        
        if (!$report) {
            throw new \yii\web\NotFoundHttpException('Laporan tidak ditemukan.');
        }

        // Ambil seluruh riwayat komentar untuk laporan ini
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
    // Halaman Peta Statistik Sebaran
    public function actionStatistik()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        // Ambil Data Semua Titik Koordinat Laporan
        $mapReports = \app\models\Laporan::find()
            ->select(['id', 'judul', 'latitude', 'longitude', 'status', 'kategori_id'])
            ->where(['not', ['latitude' => null]])
            ->andWhere(['not', ['longitude' => null]])
            ->with('kategori')
            ->all();

        // Hitung statistik untuk summary cards
        $statsKategori = [
            'Ringan' => 0,
            'Sedang' => 0,
            'Berat' => 0,
            'Sangat Berat' => 0,
        ];

        foreach ($mapReports as $report) {
            $kat = $report->kategori->nama_kategori ?? 'Tidak Diketahui';
            if (isset($statsKategori[$kat])) {
                $statsKategori[$kat]++;
            }
        }

        return $this->render('statistik', [
            'mapReports' => $mapReports,
            'statsKategori' => $statsKategori
        ]);
    }
    // Halaman Daftar Semua Laporan (Dengan Filter & Search)
    public function actionReports()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        // Tangkap inputan filter dari user (jika ada)
        $search = Yii::$app->request->get('search');
        $statusFilter = Yii::$app->request->get('status');

        // Kueri dasar, join dengan tabel user agar bisa nyari berdasarkan nama pelapor
        $query = \app\models\Laporan::find()
            ->joinWith('user') 
            ->orderBy(['laporan.created_at' => SORT_DESC])
            ->with(['kategori']);

        // Logika Filter Status
        if (!empty($statusFilter)) {
            $query->andWhere(['laporan.status' => $statusFilter]);
        }

        // Logika Pencarian Judul Laporan atau Nama Pelapor
        if (!empty($search)) {
            $query->andWhere(['or', 
                ['like', 'laporan.judul', $search],
                ['like', 'user.name', $search]
            ]);
        }

        // Set Pagination
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
            // Kirim nilai saat ini ke view biar formnya nahan pilihan terakhir
            'currentSearch' => $search,
            'currentStatus' => $statusFilter,
        ]);
    }
    // Halaman Manajemen Semua Pengguna
    public function actionUsers()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        $search = Yii::$app->request->get('search');
        $query = \app\models\User::find()->orderBy(['id' => SORT_DESC]);

        if (!empty($search)) {
            $query->andWhere(['or', 
                ['like', 'name', $search],
                ['like', 'email', $search]
            ]);
        }

        $pagination = new \yii\data\Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $users = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('users', [
            'users' => $users,
            'pagination' => $pagination,
            'currentSearch' => $search
        ]);
    }
    // Halaman Profil Administrator
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        $admin = \app\models\User::findOne(Yii::$app->user->identity->id);

        $stats = [
            'total_user' => \app\models\User::find()->count(),
            'total_laporan' => \app\models\Laporan::find()->count(),
            'total_selesai' => \app\models\Laporan::find()->where(['status' => 'Selesai'])->count(),
        ];

        return $this->render('profile', [
            'admin' => $admin,
            'stats' => $stats
        ]);
    }
    // Halaman Pengaturan Profil & Password Admin
    public function actionSettings()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        $admin = \app\models\User::findOne(Yii::$app->user->identity->id);

        return $this->render('settings', [
            'admin' => $admin,
        ]);
    }

    // Proses Update Data Profil Admin
    public function actionUpdateProfile()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        $admin = \app\models\User::findOne(Yii::$app->user->identity->id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            $admin->name = $post['name'] ?? $admin->name;
            $admin->phone = $post['phone'] ?? $admin->phone;
            $admin->address = $post['address'] ?? $admin->address;

            if ($admin->save(false)) {
                $adminName = Yii::$app->user->identity->name;
                Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => Yii::$app->user->id, 
                'tipe' => 'admin', 
                'deskripsi' => "Admin {$adminName} memperbarui profilnya.", 
                'created_at' => time()
        ])->execute();
                Yii::$app->session->setFlash('success', 'Profil Administrator berhasil diperbarui!');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui profil.');
            }
        }

        return $this->redirect(['admin/settings']);
    }

    // Proses Ubah Password Admin
    public function actionChangePassword()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        $admin = \app\models\User::findOne(Yii::$app->user->identity->id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $oldPassword = $post['old_password'] ?? '';
            $newPassword = $post['new_password'] ?? '';
            $confirmPassword = $post['confirm_password'] ?? '';

            // Validasi password lama
            if (!$admin->validatePassword($oldPassword)) {
                Yii::$app->session->setFlash('error', 'Password lama yang Anda masukkan salah.');
                return $this->redirect(['admin/settings']);
            }

            // Validasi kesesuaian password baru
            if ($newPassword !== $confirmPassword) {
                Yii::$app->session->setFlash('error', 'Konfirmasi password baru tidak cocok.');
                return $this->redirect(['admin/settings']);
            }

            // Enkripsi password baru
            if (method_exists($admin, 'setPassword')) {
                $admin->setPassword($newPassword);
            } else {
                $admin->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
            }

            if ($admin->save(false)) {
                Yii::$app->session->setFlash('success', 'Password Administrator berhasil diubah!');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengubah password.');
            }
        }

        return $this->redirect(['admin/settings']);
    }
   
    public function actionAktivitas()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
            return $this->redirect(['admin/login']);
        }

        try {
            $soapService = new \app\controllers\SoapController('soap', Yii::$app); 
            // Kirim parameter role admin agar memunculkan seluruh log sistem global
            $jsonResponse = $soapService->getLogAktivitas(Yii::$app->user->id, 'admin'); 
            $logs = json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            $logs = [];
        }

        return $this->render('aktivitas', ['logs' => $logs]);
    }
}