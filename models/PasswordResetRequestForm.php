<?php
namespace app\models;

use yii\base\Model;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $login;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'required'],
            ['login', 'email'],
            [
                'login',
                'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['active' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с таким E-mail адресом не найден.'
            ],
        ];
    }


    public function attributeLabels()
    {
        return [
            'login' => 'E-mail',
            'reCaptcha' => 'Я не робот',
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'active' => User::STATUS_ACTIVE,
            'login' => $this->login,
        ]);

        if ($user) {

            $user->generatePasswordResetToken();

            if ($user->save()) {
                return \Yii::$app->mailer->compose([
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text'
                    ], [
                        'user' => $user,
                        'resetPasswordLink' => Url::to(['site/reset-password', 'token' => $user->access_token], true),
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->login)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
