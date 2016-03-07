<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property integer $id
 * @property integer $additional_user_id
 * @property string $title
 * @property string $machine_name
 * @property string $description
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string', 'max' => 255],
            [['machine_name'], 'string', 'max' => 50],
            [['additional_user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'additional_user_id' => 'ID Пользователя',
            'title' => 'Наименование',
            'machine_name' => 'Машинное имя',
            'description' => 'Описание',
        ];
    }
}
