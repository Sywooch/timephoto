<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property integer $id
 * @property double $amount
 * @property string $created
 * @property string $method
 * @property string $type
 * @property string $description
 * @property string $status
 * @property integer $user_id
 * @property integer $tariff_duration_id
 *
 * @property TariffDuration $tariffDuration
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'user_id'], 'required'],
            [['amount'], 'number'],
            [['created'], 'safe'],
            [['description'], 'string'],
            [['user_id', 'tariff_duration_id'], 'integer'],
            [['method', 'type', 'status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'created' => 'Created',
            'method' => 'Method',
            'type' => 'Type',
            'description' => 'Description',
            'status' => 'Status',
            'user_id' => 'User',
            'tariff_duration_id' => 'Tariff Duration',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getTariffDuration()
    {
        return $this->hasOne(TariffDuration::className(), ['id' => 'tariff_duration_id']);
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

}
