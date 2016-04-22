<?php

namespace app\models;

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
use Yii;

/**
 * This is the model class for table "{{%camera}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property string $ftp_login
 * @property string $ftp_password
 * @property string $ftp_home_dir
 * @property string $access
 * @property string $comment
 * @property integer $memory_limit
 * @property string $storage_time
 * @property integer $enabled
 * @property integer $deleted
 * @property integer $user_id
 * @property integer $location_id
 * @property integer $camera_category_id
 * @property string $icon_name
 * @property integer $delete
 * @property integer $internal_id
 * @property integer $registrator_id
 * @property integer $camera_registrator_id
 * @property string $format
 *
 * @property CameraCategory $cameraCategory
 * @property Location $location
 * @property User $user
 * @property CameraLog[] $cameraLogs
 * @property Image[] $images
 */
class Camera extends \yii\db\ActiveRecord
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
        return '{{%camera}}';
    }

    public static function getNextCameraNumberByUser($user_id = 0)
    {
        if (!$user_id) {
            $user_id = Yii::$app->user->identity->userId;
        }

        $lastId = self::find()->select(['internal_id'])->where('user_id=:user_id', [':user_id' => $user_id])->orderBy(['internal_id' => SORT_DESC])->scalar();

        return strval($lastId) + 1;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'icon_name'], 'string'],
            [['enabled', 'public', 'deleted', 'user_id', 'location_id', 'camera_category_id', 'delete', 'internal_id', 'registrator_id', 'camera_registrator_id'], 'integer'],
            [['memory_limit'], 'number'],
            [['name', 'user_id'], 'required'],
            [['name', 'created', 'ftp_login', 'ftp_password', 'access', 'storage_time'], 'string', 'max' => 45],
            [['ftp_home_dir'], 'string', 'max' => 255],
            [['ftp_login'], 'unique'],
            [['ftp_home_dir'], 'unique'],
            [['format'], 'string', 'max' => 50],
            [['created'], 'safe'],
            [['public'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'created' => 'Created',
            'ftp_login' => 'FTP-логин',
            'ftp_password' => 'FTP-пароль',
            'ftp_home_dir' => 'Ftp Home Dir',
            'access' => 'Access',
            'comment' => 'Комментарий к устройству',
            'memory_limit' => 'Лимит памяти',
            'storage_time' => 'Срок хранения',
            'enabled' => 'Enabled',
            'deleted' => 'Флаг удаления камеры',
            'user_id' => 'User',
            'location_id' => 'Объект',
            'camera_category_id' => 'Тематика',
            'icon_name' => 'icon_name',
            'delete' => 'Режим удаления фотографий по истечении времени хранения',
            'internal_id' => 'Internal ID',
            'registrator_id' => 'Registrator ID',
            'camera_registrator_id' => 'Camera Registrator ID',
            'format' => 'Формат файлов',
            'public' => 'Разрешить публичный доступ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCameraCategory()
    {
        return $this->hasOne(CameraCategory::className(), ['id' => 'camera_category_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getCameraLogs()
    {
        return $this->hasMany(CameraLog::className(), ['camera_id' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['camera_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    /*
    public function afterSave($insert, $changedAttributes) {

        return afterSave($insert, $changedAttributes);

    }
    */

    /**
     *
     * App
     *
     */

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {

        if (!file_exists($this->ftp_home_dir)) {
            mkdir($this->ftp_home_dir, 0775, true);
            chmod($this->ftp_home_dir, 0775);
        }

        return parent::beforeSave($insert);

    }

    /**
     * @return Image
     */
    public function getLastImage()
    {
        $image = Image::find()->where(['camera_id' => $this->id, 'deleted' => 0])->orderBy(['created' => SORT_DESC])->one();

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

    public function getCategoryName()
    {
        if ($this->cameraCategory) {
            return $this->cameraCategory->getName();
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
        if (!$totalSize = Yii::$app->db->createCommand('SELECT SUM(file_size) FROM image WHERE deleted = 0 AND camera_id = :id', [':id' => $this->id])->queryScalar()) {
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
    }

    public function getCategoryAndLocation()
    {
        if (isset($this->cameraCategory)) {
            return Helper::shortLine($this->cameraCategory->name . ' [' . $this->location->name . ']', 21);
        }
    }

    public function getCapturesQuantity()
    {
        return Image::find()->where('created > DATE_SUB(NOW(), INTERVAL 1 DAY) AND camera_id = :cameraId', [':cameraId' => $this->id])->count();
    }

    public function getCameraNumber()
    {
        return str_pad(strval(self::getCamerasCount()), 3, '0', STR_PAD_LEFT);
    }

    public static function getCamerasCount()
    {
        return self::find()->where(['user_id' => Yii::$app->user->identity->userId])->count();
    }

    public function countImages()
    {
        if (!$imagesCount = Yii::$app->db->createCommand('SELECT COUNT(id) FROM image WHERE deleted = 0 AND camera_id = :id', [':id' => $this->id])->queryScalar()) {
            $imagesCount = 0;
        }

        return $imagesCount;
    }

    public function getFilterTypes()
    {
        $filterTypes = ['SCHEDULE'];

        if (Image::find()->where(['camera_id' => $this->id, 'type' => 'ALERT'])->count() > 0) {
            $filterTypes[] = 'ALERT';
        }

        if (Image::find()->where(['camera_id' => $this->id, 'type' => 'MOVE'])->count() > 0) {
            $filterTypes[] = 'MOVE';
        }

        if (count($filterTypes) > 1) {
            $filterTypes[] = 'ALL';
        }

        return $filterTypes;
    }

    public static function getCameras($id = false)
    {
        $cameras = [];

        if($id){

            $cameras[] = Camera::findOne(['id' => $id]);

        } else {

            $cameras = Camera::find()->where([
                'user_id' => Yii::$app->user->identity->userId,
                'deleted' => 0
            ])->orderBy(['location_id' => SORT_DESC, 'id' => SORT_DESC])->all();

        }

        $cameraMenu = [];
        $camerasArray = [];
        foreach ($cameras as $camera) {
            if (Yii::$app->user->identity->role == 'ADDITIONAL_USER') {
                if (!Yii::$app->user->identity->checkPermission('access_view', $camera->id)) {
                    continue;
                }
            }
            $cameraElement = $camera->attributes;
            $cameraElement['thumb'] = $camera->getLastImage()->getThumbnailUrl();
            $cameraElement['category_name'] = $camera->getCategoryName();
            $cameraElement['location_name'] = $camera->getLocationName();
            $cameraElement['last_image_date'] = date('d-m-y H:i', strtotime($camera->getLastImage()->created));
            $cameraElement['totalSize'] = $camera->getTotalSize();
            $cameraElement['occupiedPercent'] = $camera->getOccupiedPercent();
            $cameraElement['quantity'] = $camera->getCapturesQuantity();
            $cameraElement['canEdit'] = Yii::$app->user->identity->canEdit();
            $cameraElement['href'] = Url::to(['/cabinet/camera', 'id' => $camera->id]);
            $cameraElement['manage_href'] = Url::to('/cabinet/camera/manage');
            $cameraElement['edit_href'] = Url::to(['/cabinet/camera/edit', 'id' => $camera->id]);
            $camerasArray[] = $cameraElement;
            $cameraMenu[] = $camera;
        }

        return [$cameraMenu, $camerasArray];
    }

    public static function getPublicListLabels(){
        return [
            0 => "Запретить публичный доступ",
            1 => "Разрешить публичный доступ",
        ];
    }

    /**
     * Example: http://timephoto.loc:8082/cabinet/public?token=33c6ffb2843fdd44cf099fe5ca0323a8&view=one
     * @param string $preset
     * @return string
     */
    public function getPublicCode(){

        $src = Url::to(['/camera', 'id' => $this->id], true);

        return "<script src='{$src}' id='timephoto-camera-{$this->id}'></script>";

    }

}
