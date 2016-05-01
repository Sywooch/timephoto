<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    //public $reCaptcha;
    public $login;
    public $password;
    public $repassword;
    public $reCaptcha;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6LdEux4TAAAAAEcpOC0uI_RC8epU8n1ukgJeTKKA',  ],
            [['login', 'repassword', 'password'], 'required'],
            [['login', 'repassword', 'password'], 'trim'],
            [['login',], 'checkUnique'],
            [['login',], 'email'],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message'=>'Вы неверно повторили пароль'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'login' => 'Ваш E-mail',
            'password' => 'Пароль',
            'repassword' => 'Повторите пароль',
            'reCaptcha' => 'Я не робот',
        ];
    }

    public function checkUnique($attribute, $params){
        $user = User::find()->where([$attribute => $this->$attribute])->one();
        if( !empty($user) ){
            $this->addError($attribute, $this->attributeLabels()[$attribute]. ' "' . $this->$attribute . '"' .
                ' уже зарегистрирован. Воспользуйтесь формой восстановления пароля или зарегистрируйте новый аккаунт' );
        }

    }


}
