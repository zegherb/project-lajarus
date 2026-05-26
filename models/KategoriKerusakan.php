<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Model class for table "kategori_kerusakan".
 *
 * @property int $id
 * @property string $nama_kategori
 */
class KategoriKerusakan extends ActiveRecord
{
    /**
     * Menghubungkan model ke tabel "kategori_kerusakan"
     */
    public static function tableName()
    {
        return '{{%kategori_kerusakan}}';
    }

    /**
     * Aturan validasi
     */
    public function rules()
    {
        return [
            [['nama_kategori'], 'required'],
            [['nama_kategori'], 'string', 'max' => 50],
        ];
    }

    /**
     * Relasi ke tabel Laporan (Opsional, tapi sangat berguna untuk pemanggilan data nanti)
     */
    public function getLaporans()
    {
        return $this->hasMany(Laporan::class, ['kategori_id' => 'id']);
    }
}