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
}