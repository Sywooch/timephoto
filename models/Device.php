<?php

namespace app\models;

use Yii;
use yz\shoppingcart\CartPositionTrait;
use yz\shoppingcart\CartPositionInterface;

/**
 * This is the model class for table "{{%device}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property integer $main_page
 * @property integer $active
 * @property integer $device_category_id
 *
 * @property DeviceCategory $deviceCategory
 * @property DeviceCase[] $deviceCases
 * @property DeviceFeature[] $deviceFeatures
 * @property DevicePhoto[] $devicePhotos
 */
class Device extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['description'], 'string'],
          [['price', 'device_category_id'], 'required'],
          [['price'], 'number'],
          [['main_page', 'active', 'device_category_id'], 'integer'],
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
          'description' => 'Description',
          'price' => 'Price',
          'main_page' => 'Main Page',
          'active' => 'Active',
          'device_category_id' => 'Device Category',
        ];
    }

    public function getFormattedPrice(){
        $return = preg_replace(
            '~' .
            '(\d' . // число
            '(?=' . // после есть (логическое есть)
            '(?:\d{3})+' . // число
            '(?!\d)' . // после нет числа (логическое отрицание)
            ')' .
            ')' .
            '~s', "\\1 ", $this->price);
        //    '~s', "\\1 </span><span class=f_price2>", $this->price);
        //$return = '<span class="f_price">' . $return . '</span>';
        return $return;
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
    public function getDeviceCategory()
    {
        return $this->hasOne(DeviceCategory::className(), ['id' => 'device_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceCases()
    {
        return $this->hasMany(DeviceCase::className(), ['device_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceFeatures()
    {
        return $this->hasMany(DeviceFeature::className(), ['device_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevicePhotos()
    {
        return $this->hasMany(DevicePhoto::className(), ['device_id' => 'id']);
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
