<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tariff}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cameras_quantity
 * @property integer $users_quantity
 * @property double $memory_limit
 * @property integer $public_cameras
 * @property integer $users_at_time
 * @property double $daily_traffic
 * @property integer $embed
 * @property integer $copy
 * @property integer $panorams
 * @property integer $edit
 * @property integer $sms
 * @property integer $email
 * @property integer $site_logo
 * @property integer $watermark
 *
 * @property TariffDuration[] $tariffDurations
 * @property User[] $users
 */
class Tariff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tariff}}';
    }

    /**
     *
     * App
     *
     */

    public static function getAllTransposed()
    {
        $tariffs = self::find()->all();
        $tariffsTransposed = [];

        if (count($tariffs) > 0) {
            $attributes = $tariffs[0]->attributes;
            foreach ($attributes as $attributeName => $attributeValue) {
                if (!in_array($attributeName, ['id', 'name'])) {
                    $tariffValues = [];
                    foreach ($tariffs as $tariff) {
                        if (in_array($attributeName, self::getBooleanAttributes())) {
                            $value = $tariff->$attributeName == '1' ? 'Да' : 'Нет';
                        } elseif (in_array($attributeName, self::getUnlimitedAttributes())) {
                            $value = $tariff->$attributeName == '-1' ? 'Безлимитно' : $tariff->$attributeName;
                        } else {
                            $value = $tariff->$attributeName;
                        }

                        $tariffValues[] = ['id' => $tariff->id, 'name' => $tariff->name, 'value' => $value];
                    }
                    $tariffsTransposed[] = ['name' => $attributeName, 'label' => $tariffs[0]->getAttributeLabel($attributeName), 'values' => $tariffValues];
                }
            }
        }

        return $tariffsTransposed;
    }

    public static function getBooleanAttributes()
    {
        return ['public_cameras', 'embed', 'copy', 'edit', 'panorams', 'sms', 'email', 'site_logo', 'watermark'];
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

    public static function getUnlimitedAttributes()
    {
        return ['cameras_quantity', 'users_quantity', 'users_at_time', 'daily_traffic'];
    }

    public static function getAllPricesTransposed()
    {
        $tariffs = self::find()->all();
        $durations = Duration::find()->all();
        $pricesTransposed = [];

        if (count($tariffs) > 0) {
            foreach ($durations as $duration) {
                $prices = [];
                foreach ($tariffs as $tariff) {
                    $price = [];
                    foreach ($tariff->tariffDurations as $tariffDuration) {
                        if ($tariffDuration->duration_id == $duration->id) {
                            $price = [
                                'name' => $tariff->name,
                                'id' => $tariffDuration->id,
                                'price' => $tariffDuration->price,
                                'selectable' => intval($tariffDuration->price) > 0,
                                'tariff_id' => $tariff->id
                            ];
                            break;
                        }
                    }
                    $prices[] = $price;
                }
                $pricesTransposed[] = ['name' => $duration->name, 'prices' => $prices];
            }
        }

        return $pricesTransposed;
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

    public static function getNotWorkingAttributes()
    {
        return ['users_quantity', 'panorams', 'embed', 'public_cameras', 'sms', 'email'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cameras_quantity', 'users_quantity', 'memory_limit', 'public_cameras', 'users_at_time', 'daily_traffic', 'embed', 'copy', 'panorams', 'edit', 'sms', 'email', 'site_logo', 'watermark'], 'required'],
            [['cameras_quantity', 'users_quantity', 'public_cameras', 'users_at_time', 'embed', 'copy', 'panorams', 'edit', 'sms', 'email', 'site_logo', 'watermark'], 'integer'],
            [['memory_limit', 'daily_traffic'], 'number'],
            [['name'], 'string', 'max' => 10]
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
            'cameras_quantity' => 'Количество камер',
            'users_quantity' => 'Количество пользователей',
            'memory_limit' => 'Объем места',
            'public_cameras' => 'Публичные камеры',
            'users_at_time' => 'Пользователей одновременно',
            'daily_traffic' => 'Траффик в сутки',
            'embed' => 'Встраивание',
            'copy' => 'Копирование',
            'panorams' => 'Панорамы',
            'edit' => 'Редактирование',
            'sms' => 'СМС-оповещения',
            'email' => 'Email-оповещения',
            'site_logo' => 'Показывать лого сайта',
            'watermark' => 'Логотип клиента',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariffDurations()
    {
        return $this->hasMany(TariffDuration::className(), ['tariff_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['tariff_id' => 'id']);
    }

    public function getTransposedByName($name)
    {
        foreach ($this->getTransposed() as $element) {
            if ($element['name'] == $name) {
                return $element['value'];
            }
        }

        return '';
    }

    public function getTransposed()
    {
        $attributes = $this->attributes;
        $tariffValues = [];
        foreach ($attributes as $attributeName => $attributeValue) {
            if (!in_array($attributeName, ['id', 'name'])) {

                if (in_array($attributeName, Tariff::getBooleanAttributes())) {
                    $value = $this->$attributeName == '1' ? 'Да' : 'Нет';
                } elseif (in_array($attributeName, Tariff::getUnlimitedAttributes())) {
                    $value = $this->$attributeName == '-1' ? 'Безлимитно' : $this->$attributeName;
                } else {
                    $value = $this->$attributeName;
                }

                $tariffValues[] = ['name' => $attributeName, 'label' => $this->getAttributeLabel($attributeName), 'value' => $value];
            }
        }

        return $tariffValues;
    }

}
