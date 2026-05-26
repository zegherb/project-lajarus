<?php

namespace app\models;

use Yii;

class KomentarLaporan extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'komentar_laporan';
    }

    public function rules()
    {
        return [
            [['laporan_id', 'user_id', 'isi_komentar', 'created_at'], 'required'],
            [['laporan_id', 'user_id', 'created_at'], 'integer'],
            [['isi_komentar'], 'string'],
        ];
    }

    // Relasi ke model User (untuk tahu siapa yang komen)
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}