<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property string $file_name
 * @property double $file_size
 * @property string $created
 * @property string $type
 * @property integer $deleted
 * @property integer $sifted
 * @property integer $camera_id
 * @property integer $f_fav
 *
 * @property Camera $camera
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'file_size', 'created', 'camera_id'], 'required'],
            [['file_name'], 'string'],
            [['file_size'], 'number'],
            [['created'], 'safe'],
            [['deleted', 'sifted', 'camera_id', 'f_fav'], 'integer'],
            [['type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'file_size' => 'File Size',
            'created' => 'Created',
            'type' => 'Type',
            'deleted' => 'Deleted',
            'sifted' => 'Sifted',
            'camera_id' => 'Camera',
            'f_fav' => 'F Fav',
        ];
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


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCamera()
    {
        return $this->hasOne(Camera::className(), ['id' => 'camera_id']);
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
     *
     * App
     *
     */

    public function getImageUrl()
    {
        if ($this->camera && $this->file_name) {
            return 'http://' . Yii::$app->params['host'] . '/' . Yii::$app->params['imageDir'] . '/' . ($this->camera->user_id + 10011000) . '/' . $this->camera->ftp_login . '/' . $this->file_name;
        } else {
            return Yii::$app->homeUrl . 'images/no-image.jpg';
        }
    }

    public function getThumbnailUrl()
    {
        if ($this->camera && $this->file_name) {
            return 'http://' . Yii::$app->params['host'] . '/' . Yii::$app->params['imageDir'] . '/' . ($this->camera->user_id + 10011000) . '/' . $this->camera->ftp_login . '/.thumbs/' . $this->file_name;
        } else {
            return Yii::$app->homeUrl . 'images/no-image.jpg';
        }
    }

    public function getAbsoluteFileName()
    {
        return Yii::getAlias('@webroot') . '/' . Yii::$app->params['imageDir'] . '/' . ($this->camera->user_id + 10011000) . '/' . $this->camera->ftp_login . '/' . $this->file_name;
    }

    public function getAbsoluteThubnailFileName()
    {
        return Yii::getAlias('@webroot') . '/' . Yii::$app->params['imageDir'] . '/' . ($this->camera->user_id + 10011000) . '/' . $this->camera->ftp_login . '/.thumbs/' . $this->file_name;
    }

    public function getType()
    {
        return $this->type;
    }


}
