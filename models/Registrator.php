<?php

namespace app\models;

use app\components\Helper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%registrator}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $prefix
 * @property integer $create_date
 * @property string $ftp_login
 * @property string $ftp_password
 * @property string $ftp_home_dir
 * @property string $access
 * @property string $comment
 * @property integer $memory_limit
 * @property integer $enabled
 * @property integer $deleted
 * @property integer $user_id
 * @property integer $location_id
 * @property integer $delete
 *
 * @property Location $location
 * @property User $user
 */
class Registrator extends \yii\db\ActiveRecord
{
    const SIZE_FORMAT_GB = 1;
    const SIZE_FORMAT_MB = 2;
    const SIZE_FORMAT_KB = 3;
    public $icon_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%registrator}}';
    }

    public static function getNewFtpLogin()
    {
        return Yii::$app->user->identity->accountNumber . '_R' . self::getNextRegistratorNumber();
    }

    public static function getNextRegistratorNumber()
    {
        $lastId = self::find()->orderBy(['id' => SORT_DESC])->one();

        if ($lastId) {
            return str_pad(($lastId->id + 1), 3, "0", STR_PAD_LEFT);
        } else {
            return str_pad(1, 3, "0", STR_PAD_LEFT);
        }
    }

    /**
     * @inheritdoc
     */
    /*
    public function behaviors() {
        return [
            'timestamp' => [
              'class' => yii\behaviors\TimestampBehavior::className(),
              'createdAtAttribute' => 'create_time',
              'updatedAtAttribute' => 'update_time',
              'value' => new yii\db\Expression('NOW()'),
            ],

        ];
    }
    */

    public static function getRegistrators()
    {
        $registrators = self::find()
            ->where('user_id = :userId AND deleted = 0', [':userId' => Yii::$app->user->identity->userId])
            ->orderBy(['location_id' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $registratorArray = [];
        foreach ($registrators as $registrator) {
            $registratorElement = $registrator->attributes;
            //$registratorElement['thumb'] = $registrator->getLastImage()->getThumbnailUrl();
            //$registratorElement['category_name'] = $registrator->getCategoryName();
            $registratorElement['location_name'] = $registrator->getLocationName();
            //$registratorElement['last_image_date'] = $registrator->getLastImage()->created;
            $registratorElement['totalSize'] = $registrator->getTotalSize();
            $registratorElement['occupiedPercent'] = $registrator->getOccupiedPercent();
            $registratorElement['quantity'] = $registrator->getCapturesQuantity();
            //$registratorElement['canEdit'] = Yii::$app->user->identity->canEdit();
            $registratorElement['href'] = Url::to('/cabinet/registrator', ['id' => $registrator->id]);
            $registratorElement['manage_href'] = Url::to('/cabinet/registrator/manage');
            $registratorElement['edit_href'] = Url::to('/cabinet/registrator/edit', ['id' => $registrator->id]);
            $camerasArray[] = $registratorElement;
        }

        return array($registrators, json_encode($registratorArray));

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'prefix', 'create_date', 'ftp_login', 'ftp_password', 'ftp_home_dir', 'user_id'], 'required'],
            [['create_date', 'enabled', 'deleted', 'user_id', 'location_id', 'delete'], 'integer'],
            [['memory_limit'], 'number'],
            [['comment'], 'string'],
            [['name', 'ftp_login', 'ftp_password', 'ftp_home_dir', 'access'], 'string', 'max' => 45],
            [['prefix'], 'string', 'max' => 255],
            [['ftp_login'], 'unique'],
            [['ftp_home_dir'], 'unique']
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'create_date' => 'Created',
            'ftp_login' => 'FTP-логин',
            'ftp_password' => 'FTP-пароль',
            'ftp_home_dir' => 'Ftp Home Dir',
            'access' => 'Access',
            'comment' => 'Комментарий к устройству',
            'memory_limit' => 'Лимит памяти',
            'enabled' => 'Enabled',
            'deleted' => 'Deleted',
            'user_id' => 'User',
            'location_id' => 'Объект',
            'icon_name' => 'Icon Name',
            'delete' => 'Icon Name',
            'internal_id' => 'Номер камеры',
            'prefix' => 'Префикс',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return Image
     */
    public function getLastImage()
    {
        $image = Image::find()->where(['Registrator_id' => $this->id, 'deleted' => 0])->orderBy(['create_date' => SORT_DESC])->one();

        return $image ? $image : new Image();
    }

    public function getName()
    {
        return Helper::shortLine($this->name, 20);
    }

    public function getLocationName()
    {
        if ($this->location) {
            return $this->location->getName();
        } else {
            return 'Private';
        }
    }

    public function getOccupiedPercent()
    {
        $totalSize = $this->getTotalSize(self::SIZE_FORMAT_MB);
        $hasSize = floatval($this->memory_limit);

        if ($hasSize == 0) {
            return 100;
        } else {
            return number_format($totalSize / $hasSize * 100, 2);
        }
    }

    public function getTotalSize($format = self::SIZE_FORMAT_GB)
    {

        if (!$totalSize = Yii::$app->db->createCommand('SELECT SUM(file_size) FROM image WHERE deleted = 0 AND Registrator_id = :id', [':id' => $this->id])
            ->queryScalar()
        ) {
            $totalSize = 0;
        }

        switch ($format) {
            case self::SIZE_FORMAT_GB:
                return round($totalSize / 1024 / 1024, 2);
            case self::SIZE_FORMAT_MB:
                return round($totalSize / 1024, 2);
            case self::SIZE_FORMAT_KB:
                return round($totalSize, 2);
            default:
                return round($totalSize / 1024 / 1024, 2);
        }

        return $totalSize;
    }

    public function getCapturesQuantity()
    {
        return Image::find()->where('created > DATE_SUB(NOW(), INTERVAL 1 DAY) AND Registrator_id = :RegistratorId', [':RegistratorId' => $this->id])->count();
    }

    public function getRegistratorNumber()
    {
        return str_pad(strval(self::getRegistratorsCount()), 3, '0', STR_PAD_LEFT);
    }

    public static function getRegistratorsCount()
    {
        return self::find()->where(['user_id' => Yii::app()->user->getId()])->count();
    }

    public function countImages()
    {
        return (int)Yii::$app->db->createCommand('SELECT COUNT(*) FROM image WHERE deleted = 0 AND Registrator_id = :id', [':id' => $this->id])
            ->queryScalar();
    }

    public function getFilterTypes()
    {
        $filterTypes = ['SCHEDULE'];

        if (Image::find()->where(['Registrator_id' => $this->id, 'type' => 'ALERT'])->count() > 0) {
            $filterTypes[] = 'ALERT';
        }
        if (Image::find()->where(['Registrator_id' => $this->id, 'type' => 'MOVE'])->count() > 0) {
            $filterTypes[] = 'MOVE';
        }

        if (count($filterTypes) > 1) {
            $filterTypes[] = 'ALL';
        }

        return $filterTypes;
    }

}
