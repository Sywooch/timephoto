<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;

use app\components\SMS;
use app\models\Camera;
use app\models\Image;
use app\models\User;
use Yii;

class AjaxController extends \app\modules\cabinet\components\CabinetController
{

    public function actionToggleCamera()
    {
        if (isset($_POST['id'])) {
            $user = User::findOne(Yii::$app->user->identity->userId);
            if ($user->cameras_enabled) {
                $camera = Camera::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id'], 'deleted' => 0]);
                if ($camera) {
                    if ($camera->enabled == 1) {
                        $camera->enabled = 0;
                    } else {
                        $camera->enabled = 1;
                    }
                    $camera->save();
                }
                echo 'OK';
            } else {
                echo 'DISABLED';
            }
        }
    }

    public function actionDeleteImages()
    {
        if (isset($_POST['id']) && isset($_POST['from']) && isset($_POST['to']) && isset($_POST['count'])) {
            $camera = Camera::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id'], 'deleted' => 0]);

            if ($camera) {
                $count = intval($_POST['count']);
                if ($count <= 10 && $count >= 1) {
                    $imagesToDelete = [];
                    $images = Image::find()->where('camera_id = :camera AND created > :from AND created < :to', [
                        ':camera' => $_POST['id'], 
                        ':from' => $_POST['from'], 
                        ':to' => $_POST['to']
                    ])->all();
                    for ($i = 0; $i < count($images); $i++) {
                        if (($i + 1) % $count !== 0) {
                            $imagesToDelete[] = $images[$i]->id;
                        }

                        if (count($imagesToDelete) > 0) {
                            echo Image::updateAll(['deleted' => 1], 'id IN (' . implode(',', $imagesToDelete) . ')');
                        } else {
                            echo 0;
                        }
                    }

                }
            } else {
                echo 0;
            }
        }
    }

    //Images
    public function actionGetImagesFromCamera($id, $page = 1, $limit = 12)
    {
        //@todo Check if a camera belongs to current user
        $images = Image::find()->where(['deleted' => 0, 'camera_id' => $id])->offset(($page - 1) * $limit)->limit($limit)->all();

        $json = [];
        foreach ($images as $image) {
            $json[] = ['id' => $image->id, 'created' => $image->created, 'img' => $image->getImageUrl(), 'thumb' => $image->getThumbUrl()];
        }

        echo json_encode($json);
    }

    public function actionSendVerificationCode()
    {
        if (!empty($_POST['phone'])) {
            //@todo Verify phone correct
            Yii::$app->user->identity->phone = $_POST['phone'];

            if (Yii::$app->user->identity->validate() && Yii::$app->user->identity->save()) {
                $sms = new SMS();
                $result = $sms->sendCode(Yii::$app->user->identity);

                echo 'OK';
            }

        }
    }

    public function actionCheckVerificationCode()
    {
        if (!empty($_POST['code'])) {
            if ($_POST['code'] == Yii::$app->user->identity->sms_code) {

                Yii::$app->user->identity->sms_code = null;
                Yii::$app->user->identity->save();

                echo json_encode(['result' => 'OK', 'phone' => Yii::$app->user->identity->phone]);
            } else {
                echo json_encode(['result' => 'WRONG_CODE']);
            }
        }
    }
}