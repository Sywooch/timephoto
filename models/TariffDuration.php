<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tariff_duration}}".
 *
 * @property integer $id
 * @property integer $duration_id
 * @property integer $tariff_id
 * @property double $price
 *
 * @property Duration $duration
 * @property Tariff $tariff
 * @property Transaction[] $transactions
 */
class TariffDuration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tariff_duration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['duration_id', 'tariff_id', 'price'], 'required'],
            [['duration_id', 'tariff_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'duration_id' => 'Duration',
            'tariff_id' => 'Tariff',
            'price' => 'Price',
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
    public function getDuration()
    {
        return $this->hasOne(Duration::className(), ['id' => 'duration_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['tariff_duration_id' => 'id']);
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
