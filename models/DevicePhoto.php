<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%device_photo}}".
 *
 * @property integer $id
 * @property string $original_file_name
 * @property string $file_name
 * @property integer $device_id
 *
 * @property Device $device
 */
class DevicePhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device_photo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['original_file_name', 'device_id'], 'required'],
            [['original_file_name'], 'string'],
            [['device_id'], 'integer'],
            [['file_name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_file_name' => 'Original File Name',
            'file_name' => 'File Name',
            'device_id' => 'Device',
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
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
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

}
