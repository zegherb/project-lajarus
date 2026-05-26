<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' => '{attribute} tidak boleh kosong.'],
            ['email', 'email', 'message' => 'Format email tidak valid.'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                $this->addError($attribute, 'Email atau password salah.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            // Login user dengan durasi session 30 hari jika rememberMe dicentang
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            // Mencari user berdasarkan email dengan status aktif (10)
            $this->_user = User::findOne(['email' => $this->email, 'status' => 10]);
        }

        return $this->_user;
    }
}