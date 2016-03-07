<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 06.08.15
 * Time: 1:02
 */

namespace app\modules\admin\components;

use app\components\Controller;
use Yii;

class AdminController extends Controller
{

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 'SUPERADMIN') {
                return true;
            }
        }

        return $this->redirect(['/site/login']);
    }

}