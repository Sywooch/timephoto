<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 23.03.15
 * Time: 21:22
 */

namespace app\controllers;

use app\components\Controller;
use app\models\Device;
use app\models\DeviceCategory;
use app\components\Controller as BaseContoller;
use yii\helpers\Json;
use yz\shoppingcart\ShoppingCart;
use yii\web\NotFoundHttpException;
use Yii;

class CameraController extends BaseContoller
{

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }


}