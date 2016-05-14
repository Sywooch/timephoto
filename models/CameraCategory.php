<?php

namespace app\models;

use app\components\Helper;
use Yii;

/**
 * This is the model class for table "{{%camera_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 *
 * @property Camera[] $cameras
 * @property User $user
 */
class CameraCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%camera_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        return $this->hasMany(Camera::className(), ['camera_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
