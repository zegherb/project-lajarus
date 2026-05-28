<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class SoapController extends Controller
{
    
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        if (Yii::$app->request->headers->get('x-forwarded-proto') === 'https' || 
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            $_SERVER['HTTPS'] = 'on';
            Yii::$app->request->setHostInfo('https://' . Yii::$app->request->serverName);
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            
            'index' => [
                'class' => 'mongosoft\soapserver\Action',
            ],
        ];
    }
   
    public function getLogAktivitas($user_id, $role)
    {
        $query = (new \yii\db\Query())
            ->select(['deskripsi', 'tipe', 'created_at'])
            ->from('log_aktivitas')
            ->orderBy(['created_at' => SORT_DESC]);

        if ($role === 'admin') {
            
        } else {
           
            $query->where(['user_id' => $user_id]);
        }

        $logs = $query->limit(100)->all();

        return json_encode($logs);
    }

    /**
     * Memperbarui profil pengguna via SOAP Web Service (Murni XML)
     *
     * @param int $id ID User yang akan diubah
     * @param string $name Nama lengkap yang baru
     * @param string $phone Nomor HP yang baru
     * @param string $address Alamat yang baru
     * @return object Response status dan pesan
     * @soap
     */
    public function updateUserProfile($id, $name, $phone, $address)
    {
        // Siapkan wadah Objek kosong agar otomatis jadi Tag XML
        $response = new \stdClass();
        
        $user = \app\models\User::findOne($id);
        
        if (!$user) {
            $response->status = 'error';
            $response->message = 'Pengguna tidak ditemukan.';
            return $response; // Langsung return objek
        }

        // Update data atribut
        $user->name = $name;
        $user->phone = $phone;
        $user->address = $address;

        if ($user->save(false)) {
            // Catat ke Log Aktivitas
            Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => $id, 
                'tipe' => 'user', 
                'deskripsi' => "{$name} memperbarui profil via murni SOAP API", 
                'created_at' => time()
            ])->execute();

            $response->status = 'success';
            $response->message = 'Mantap! Profil berhasil diperbarui menggunakan SOAP XML murni.';
            return $response;
        }

        $response->status = 'error';
        $response->message = 'Gagal menyimpan perubahan ke database.';
        return $response;
    }
    /**
     * Mengubah password pengguna via SOAP Web Service (Murni XML)
     *
     * @param int $id ID User yang akan diubah passwordnya
     * @param string $oldPassword Password lama
     * @param string $newPassword Password baru
     * @param string $confirmPassword Konfirmasi password baru
     * @return object Response status dan pesan
     * @soap
     */
    public function changeUserPassword($id, $oldPassword, $newPassword, $confirmPassword)
    {
        $response = new \stdClass();
        $user = \app\models\User::findOne($id);

        if (!$user) {
            $response->status = 'error';
            $response->message = 'Pengguna tidak ditemukan.';
            return $response;
        }

        // 1. Validasi keaslian password lama
        if (!$user->validatePassword($oldPassword)) {
            $response->status = 'error';
            $response->message = 'Password lama yang Anda masukkan salah.';
            return $response;
        }

        // 2. Validasi kesesuaian password baru
        if ($newPassword !== $confirmPassword) {
            $response->status = 'error';
            $response->message = 'Konfirmasi password baru tidak cocok.';
            return $response;
        }

        // 3. Set enkripsi password baru
        if (method_exists($user, 'setPassword')) {
            $user->setPassword($newPassword);
        } else {
            $user->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
        }

        if ($user->save(false)) {
            // Catat ke Log Aktivitas
            Yii::$app->db->createCommand()->insert('log_aktivitas', [
                'user_id' => $id, 
                'tipe' => 'user', 
                'deskripsi' => "Memperbarui password keamanan via SOAP API", 
                'created_at' => time()
            ])->execute();

            $response->status = 'success';
            $response->message = 'Password Anda berhasil diubah via SOAP XML!';
            return $response;
        }

        $response->status = 'error';
        $response->message = 'Gagal menyimpan password baru ke database.';
        return $response;
    }

}