<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;

use app\models\Image;
use app\models\User;
use app\modules\cabinet\components\CabinetController;
use Yii;

class RemoveController extends CabinetController
{

    public function actionBatchRemove()
    {
        $images_ids = str_replace('images=', '', Yii::$app->request->post('Images'));

        if (!empty($images_ids)) {
            $images = Image::find()->innerJoinWith('camera')->where([
                '`image`.deleted' => 0,
                '`camera`.user_id' => Yii::$app->user->identity->userId,
                '`image`.id' => explode(',', $images_ids),
            ])->all();

            foreach ($images as $image) {
                $img = Image::findOne($image['id']);
                $img->deleted = '1';
                $img->save(0);

                unlink($image->absoluteFileName);

                unlink($image->absoluteThubnailFileName);
            }
        }
    }

    public function actionDeleteAll()
    {
        if (isset($_POST['password'])) {
            $user = User::findOne(Yii::$app->user->identity->userId);

            if ($user->password == $_POST['password']) {
                Image::updateAll(['deleted' => 1], 'camera_id IN (SELECT id FROM camera WHERE user_id = :userId)', [':userId' => Yii::$app->user->identity->userId]);
                echo 'OK';
            }
        }
    }
}