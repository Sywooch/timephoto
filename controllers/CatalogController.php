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
use yz\shoppingcart\ShoppingCart;
use yii\web\NotFoundHttpException;
use Yii;

class CatalogController extends BaseContoller
{
    public $layout = 'landing';


    public function actionIndex($category = null)
    {
        $categories = DeviceCategory::find()->asArray()->all();

        $where = [];

        if ($category) {
            $where = ['device_category_id' => $category];
        }

        $devices = Device::find()->where($where)->all();

        $activeCategory = $category;

        return $this->renderPartial('index', compact('devices', 'categories', 'activeCategory'));
    }

    public function actionProductView($id){
        $categories = DeviceCategory::find()->asArray()->all();

        $device = Device::find()->where(['id'=>$id])->one();

        return $this->renderPartial('product', compact('device', 'categories', 'activeCategory'));



    }

    public function actionAddToCart($id)
    {
        $cart = Yii::$app->cart;

        $model = Device::findOne($id);
        if ($model) {
            $cart->put($model, 1);
            return $this->redirect(['/catalog/product-view', 'id'=>$id]);
        }
        throw new NotFoundHttpException();
    }


}