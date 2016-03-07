<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\components\AdminController;

class DefaultController extends AdminController
{
    public $layout = 'clients';

    public function actionIndex()
    {
        $users = User::findAll(['role' => 'USER']);

        $this->layout = 'clients';

        return $this->render('index', compact('users'));
    }
}