<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "additional_user_permission".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $additional_user_id
 * @property integer $permission_id
 * @property integer $camera_id
 */
class AdditionalUserPermission extends \yii\db\ActiveRecord
{
    const ACCESS_VIEW = 3;

     const ACCESS_COPY = 4;

     const ACCESS_DELETE = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%additional_user_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['additional_user_id', 'permission_id', 'camera_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID Пользователя',
            'additional_user_id' => 'ID Дополнительнго пользователя',
            'permission_id' => 'ID Разрешения',
            'camera_id' => 'ID Камеры',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermission()
    {
        return $this->hasOne(Permission::className(), ['id' => 'permission_id']);
    }

}
