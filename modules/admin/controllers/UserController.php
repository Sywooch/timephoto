<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 30.03.15
 * Time: 7:49
 */

namespace app\modules\admin\controllers;

use app\models\AdditionalUser;
use app\modules\admin\components\AdminController;

class UserController extends AdminController
{
    public function actionEditField()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && !empty($_POST['value'])) {
            $additionalUser = AdditionalUser::findOne($_POST['pk']);
            if ($additionalUser) {
                $additionalUser->$_POST['name'] = $_POST['value'];
                $additionalUser->save();
            }
        }
    }
}