<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 09.03.15
 * Time: 4:06
 */

namespace app\modules\admin\controllers;

use app\models\Camera;
use app\modules\admin\components\AdminController;

class CameraController extends AdminController
{
    public function actionEditField()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $camera = Camera::findOne($_POST['pk']);
            if ($camera) {
                $camera->$_POST['name'] = $_POST['value'];
                $camera->save();
            }
        }
    }
}