<?php

namespace app\models;

use app\components\Helper;
use Yii;

/**
 * This is the model class for table "{{%location}}".
 *
 * @property integer $id
 * @property string $name
 * @property double $lat
 * @property double $lon
 * @property integer $user_id
 *
 * @property Camera[] $cameras
 * @property User $user
 * @property Registrator[] $registrators
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%location}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lat', 'lon'], 'number'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'user_id' => 'User',
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
    public function getCameras()
    {
        return $this->hasMany(Camera::className(), ['location_id' => 'id']);
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
    public function getRegistrators()
    {
        return $this->hasMany(Registrator::className(), ['location_id' => 'id']);
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

    public function getName($chars = 12)
    {
        if($this->name){
            return Helper::shortLine($this->name, $chars);
        }else{
            return "не указано";
        }
    }
}
