<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $phone
 * @property string $sms_code
 * @property string $created
 * @property double $balance
 * @property integer $active
 * @property integer $cameras_enabled
 * @property string $role
 * @property string $tariff_expires
 * @property integer $tariff_id
 * @property string $custom_logo
 * @property integer $hide_site_logo
 * @property string $access_token
 *
 * @property AdditionalUser[] $additionalUsers
 * @property Camera[] $cameras
 * @property CameraCategory[] $cameraCategories
 * @property Location[] $locations
 * @property Registrator[] $registrators
 * @property Transaction[] $transactions
 * @property Tariff $tariff
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    public $newPassword;
    public $repeatPassword;
    public $oldPassword;
    public $custom_logo_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        return [
          [
            'class' => \yii\behaviors\TimestampBehavior::className(),
            'createdAtAttribute' => 'created',
            'updatedAtAttribute' => false,
            'value' => new \yii\db\Expression('NOW()'),
          ],
        ];
    }*/

    public static function findByUsername($username)
    {
        return self::findOne(['login' => $username]);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function isPasswordResetTokenValid($token)
    {
        return $this->access_token == $token;
    }

    public function generatePasswordResetToken()
    {
        $this->access_token = Yii::$app->getSecurity()->generateRandomString();
    }

    public function removePasswordResetToken()
    {
        $this->access_token = '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string'],
            [['created', 'tariff_expires'], 'safe'],
            [['balance'], 'number'],
            [['active', 'cameras_enabled', 'tariff_id', 'hide_site_logo'], 'integer'],
            [['login', 'phone', 'sms_code', 'role', 'custom_logo'], 'string', 'max' => 45],
            [['access_token'], 'string',  'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'phone' => 'Phone',
            'sms_code' => 'Sms Code',
            'created' => 'Created',
            'balance' => 'Balance',
            'active' => 'Active',
            'cameras_enabled' => 'Cameras Enabled',
            'role' => 'Role',
            'tariff_expires' => 'Tariff Expires',
            'tariff_id' => 'Tariff ID',
            'custom_logo' => 'Custom Logo',
            'hide_site_logo' => 'Hide Site Logo',
            'access_token' => 'Access token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalUsers()
    {
        return $this->hasMany(AdditionalUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCameras()
    {
        return $this->hasMany(Camera::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCameraCategories()
    {
        return $this->hasMany(CameraCategory::className(), ['user_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['user_id' => 'id']);
    }

    public function validatePassword($password)
    {
        return (bool)($this->password === $password);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrators()
    {
        return $this->hasMany(Registrator::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    public function getName()
    {
        return $this->getUsername();
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function checkPermission($machine_name)
    {
        return true;
    }

    /**
     *
     * Main App
     *
     */

    public function getTotalSize($format = Camera::SIZE_FORMAT_GB)
    {
        $totalSize = 0;
        foreach ($this->cameras as $camera) {
            if (!$camera->deleted) {
                $totalSize += $camera->getTotalSize($format);
            }
        }

        return $totalSize;
    }

    public function getAccountNumber()
    {
        return intval($this->id) + 10011000;
    }

    public function changePassword()
    {
        if (!empty($this->newPassword) && !empty($this->oldPassword) && !empty($this->oldPassword)) {
            if ($this->oldPassword == $this->password && $this->newPassword == $this->repeatPassword) {
                $this->password = $this->newPassword;
                $this->save();

                return true;
            }
        }

        return false;
    }

    public function predictReservePeriod()
    {
        $avg = Yii::$app->db->createCommand('SELECT ROUND(AVG(avg_size)) FROM (
                SELECT SUM(file_size) as avg_size FROM image WHERE camera_id IN(
                    SELECT id FROM camera WHERE user_id = :userId) AND created >= NOW() - INTERVAL 1 DAY GROUP BY camera_id) a')
            ->bindValue('userId', Yii::$app->user->identity->userId)->queryScalar();

        if (!$avg) {
            return '-';
        } else {
            $space = intval(round($this->getFreeSpace()) * 1024 * 1024);

            return floor($space / intval($avg));
        }


    }

    public function getFreeSpace($except = null, $format = Camera::SIZE_FORMAT_GB)
    {
        $occupiedSpace = 0;

        if ($this->tariff) {

            foreach ($this->cameras as $camera) {
                if (!$camera->deleted && $except !== $camera->id) {
                    $occupiedSpace += $camera->memory_limit;
                }
            }

            $occupiedSpace = ($this->tariff->memory_limit * 1024) - $occupiedSpace;

            return round($occupiedSpace / 1024, 2);
        } else {
            return 0;
        }
    }

    public function canCopy()
    {
        if ($this->tariff->copy == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function getPhoneNumber()
    {
        if ($this->phone && !$this->sms_code) {
            return $this->phone;
        } else {
            return null;
        }
    }

    public function hasSmsCode()
    {
        if ($this->sms_code) {
            return true;
        }

        return false;
    }

    public function canAddCamera()
    {
        if ($this->tariff->cameras_quantity <= $this->countCameras() && $this->tariff->cameras_quantity != -1) {
            return false;
        }

        return true;
    }

    public function countCameras()
    {
        $cameras = 0;
        foreach ($this->cameras as $camera) {
            if (!$camera->deleted) {
                $cameras++;
            }
        }

        return $cameras;
    }

    public function canSms()
    {
        if ($this->tariff->sms == 1) {
            return true;
        }

        return false;
    }

    public function canHideSiteLogo()
    {
        if ($this->tariff->site_logo == 0) {
            return true;
        }

        return false;
    }

    public function siteLogoHidden()
    {
        if ($this->hide_site_logo) {
            return true;
        } else {
            return false;
        }
    }

    public function canWatermark()
    {
        if ($this->tariff->watermark == 1) {
            return true;
        }

        return false;
    }

    public function hasCustomLogo()
    {
        if ($this->tariff->watermark && $this->custom_logo) {
            return true;
        } else {
            return false;
        }
    }

    public function canEdit()
    {
        if ($this->tariff->edit == 1) {
            return true;
        }

        return false;
    }

    public function getTariffId()
    {
        if ($this->tariff) {
            return $this->tariff->id;
        } else {
            return null;
        }
    }

    public function getTariffName()
    {
        if ($this->tariff) {
            return $this->tariff->name;
        } else {
            return '';
        }
    }

    public function getTariffExpireDate()
    {
        if ($this->tariff_expires) {
            return date('d.m.Y', strtotime($this->tariff_expires));
        } else {
            return false;
        }
    }

    /**
     * @param $tariffDuration TariffDuration
     */
    public function setTariffDuration($tariffDuration)
    {
        $this->tariff_id = $tariffDuration->tariff_id;
        $this->tariff_expires = $tariffDuration->tariff_id == 1 ? null : new Expression('NOW() + interval :months month', [':months' => $tariffDuration->duration->months]);
        $this->save();
    }

    public function buy($tariffDuration)
    {
        if ($this->getBalance() >= $tariffDuration->price) {
            $this->balance -= $tariffDuration->price;
            if ($this->save()) {
                $transaction = new Transaction();
                $transaction->user_id = $this->id;
                $transaction->amount = $tariffDuration->price;
                $transaction->type = 'OUT';
                //$transaction->created = new Expression('NOW()');
                $transaction->tariff_duration_id = $tariffDuration->id;
                $transaction->save();

                return true;
            }
        }

        return false;
    }

    public function getBalance()
    {
        return floatval($this->balance);
    }

    public function completeInvoice($amount, $comment, $user)
    {
        $transaction = new Transaction();
        $transaction->amount = $amount;
        $transaction->description = $comment;
        $transaction->type = 'IN';
        $transaction->method = 'INVOICE';
        $transaction->status = 'COMPLETED';
        $transaction->user_id = $user;
        //$transaction->created = new Expression('NOW()');

        if ($transaction->validate() && $transaction->save()) {
            $this->refillBalance($amount);
            $transaction = Transaction::findOne($transaction->id);

            return $transaction;
        } else {
            return false;
        }
    }

    public function refillBalance($amount)
    {
        $this->balance += $amount;
        $this->save();

        return true;
    }

    public function hasUnmappedLocations()
    {
        $count = Location::find()->where(['lat' => null, 'lon' => null, 'user_id' => $this->id])->count();

        if ($count) {
            return true;
        } else {
            return false;
        }
    }

    // UserIdentity

    public function getUserId()
    {
        return $this->id;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

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

}
