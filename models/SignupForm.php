<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User; // Pastikan kamu sudah punya model dasar User ActiveRecord

class SignupForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $confirmPassword;

    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'password', 'confirmPassword'], 'required', 'message' => '{attribute} tidak boleh kosong.'],
            ['email', 'trim'],
            ['email', 'email', 'message' => 'Format email tidak valid.'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Email ini sudah terdaftar.'],
            [['password', 'confirmPassword'], 'string', 'min' => 6, 'message' => '{attribute} minimal 6 karakter.'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Konfirmasi password tidak cocok.'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->role = 'user';
        $user->status = 10;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->created_at = time();
        $user->updated_at = time();
        
        return $user->save() ? $user : null;
    }
}