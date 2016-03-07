<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%additional_user}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $login
 * @property string $name
 * @property string $password
 * @property string $email
 * @property integer $active
 * @property integer $traffic_limit_after_block
 * @property integer $current_traffic
 *
 * @property User $user
 */
class AdditionalUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%additional_user}}';
    }

    public static function findByUsername($username)
    {
        return self::findOne(['email' => $username]);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'login', 'password'], 'required'],
            [['user_id', 'active', 'traffic_limit_after_block', 'current_traffic'], 'integer'],
            [['password'], 'string'],
            [['login', 'email'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255],
            [['create_time', 'update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'login' => 'Login',
            'name' => 'Личные данные',
            'password' => 'Password',
            'active' => 'Активен/Не активен',
            'create_time' => 'create_time',
            'update_time' => 'update_time',
            'traffic_limit_after_block' => 'Ограничение',
            'current_traffic' => 'Трафик',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => new \yii\db\Expression('NOW()'),
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    /*
    public function beforeValidate() {


        if($this->getIsNewRecord()){

            //

        }

        return parent::beforeValidate();

    }
    */

    /**
     * @inheritdoc
     */
    /*
    public function beforeSave($insert) {

        return parent::beforeSave($insert);

    }
    */

    /**
     * @inheritdoc
     */
    /*
    public function afterSave($insert, $changedAttributes) {

        return afterSave($insert, $changedAttributes);

    }
    */

    /*public function getAccountNumber()
    {
        return 'U' . $this->id;
    }*/
    public function validatePassword($password)
    {
        return (bool)($this->password === $password);
    }

    public function checkPermission($machine_name)
    {
        return AdditionalUserPermission::find()
            ->where([
                '{{%permission}}.machine_name' => $machine_name,
                '{{%additional_user_permission}}.additional_user_id' => $this->id,
            ])->joinWith('permission', '{{%additional_user_permission}}.permission_id = {{%permission}}.id')->exists();
    }

    public function canAddCamera()
    {
        return false;
    }

    // UserIdentity

    public function getAuthKey()
    {
        return $this->id;
    }

    public function getId()
    {
        return $this->id;

    }

    public function validateAuthKey($authKey)
    {
        return $this->id == $authKey;
    }

    public function getUserId()
    {
        return $this->user->id;
    }

    public function __get($name)
    {
        if ($name == 'attributes') {
            return parent::attributes();
        }

        if ($name == 'role') {
            return $this->getRole();
        }

        if ($name == 'user') {
            return $this->getUser()->one();
        }

        if (in_array($name, $this->attributes)) {
            return parent::__get($name);
        } else {
            return $this->user->{$name};
        }

    }



    public function getRole()
    {
        return 'ADDITIONAL_USER';
    }

    // UserIdentity magical

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function __call($name, $params)
    {

        if (method_exists($this, $name)) {
            return parent::__call($name, $params);
        } else {
            return call_user_func_array([$this->getUser()->one(), $name], $params);
        }

    }

}
