<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class SoapController extends Controller
{
    
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            
            'index' => [
                'class' => 'mongosoft\soapserver\Action',
            ],
        ];
    }
    /**
     * Fitur Wajib SOAP: Cek Status Laporan berdasarkan ID
     * Perhatikan tag @soap di bawah ini, ini wajib ada agar terbaca oleh WSDL!
     * * @param int $id_laporan
     * @return string
     * @soap
     */
    public function getStatusLaporan($id_laporan)
    {
        $laporan = \app\models\Laporan::findOne($id_laporan);
        
        if ($laporan) {
            return "Status Laporan '{$laporan->judul}': " . strtoupper($laporan->status);
        }
        
        return "Laporan dengan ID {$id_laporan} tidak ditemukan.";
    }

    /**
     * Fitur Tambahan SOAP: Hitung Total Laporan
     * * @return int
     * @soap
     */
    public function getTotalLaporan()
    {
        return (int) \app\models\Laporan::find()->count();
    }

    /**
     * Fitur SOAP: Mengambil seluruh riwayat dan statistik sistem
     * @return string
     * @soap
     */
    /**
     * Fitur SOAP: Mengambil Log Aktivitas Sistem secara Real-Time
     * @param int $user_id
     * @param string $role
     * @return string
     * @soap
     */
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

}