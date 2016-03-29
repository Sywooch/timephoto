<?php

namespace app\modules\imageapi\controllers;

use app\models\Camera;
use app\models\Image;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ImageController extends \yii\web\Controller
{

    public function actionGet($id, $preset)
    {
        $camera = Camera::findOne(['id' => $id]);

        if(!$camera){
            throw new NotFoundHttpException('Камера не найдена');
        }

        $lastImageName = $camera->getLastImage();

        if(!$lastImageName){
            throw new NotFoundHttpException('Изображений нет');
        }

        $lastImagePath = $lastImageName->getAbsoluteFileName();

        if(!file_exists($lastImagePath)){
            throw new NotFoundHttpException('Изображение отсутствует');
        }

        echo Yii::$app->image->getImage($preset, $lastImagePath, true);

        end();
    }

}