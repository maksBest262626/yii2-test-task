<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public string $email = '';
    public string $password = '';
    public string $passwordRepeat = '';

    public function rules(): array
    {
        return [
            [['email', 'password', 'passwordRepeat'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address is already registered.'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'passwordRepeat' => 'Repeat Password',
        ];
    }

    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->status = User::STATUS_ACTIVE;
        // created_at / updated_at are set automatically by TimestampBehavior

        return $user->save() ? $user : null;
    }
}