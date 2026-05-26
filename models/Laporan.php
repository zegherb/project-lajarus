<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\User;

class Laporan extends ActiveRecord
{
    public $imageFile; // Properti virtual untuk menangani file upload

    public static function tableName()
    {
        return '{{%laporan}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['kategori_id', 'judul', 'deskripsi', 'alamat_lokasi'], 'required'],
            [['kategori_id'], 'integer'],
            [['deskripsi', 'alamat_lokasi'], 'string'],
            [['judul', 'foto'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'string', 'max' => 50],
            [['status'], 'default', 'value' => 'Menunggu'],
            
            // Aturan untuk upload file gambar (Maksimal 10MB)
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
        ];
    }

    public function upload()
    {
        if ($this->validate(['imageFile']) && $this->imageFile !== null) {
            // Beri nama file unik pakai timestamp
            $fileName = time() . '_' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs('uploads/' . $fileName);
            $this->foto = $fileName;
            return true;
        }
        return false;
    }
    public function getKategori()
    {
        return $this->hasOne(KategoriKerusakan::class, ['id' => 'kategori_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}