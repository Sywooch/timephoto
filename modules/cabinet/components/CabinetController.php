<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 06.08.15
 * Time: 1:02
 */

namespace app\modules\cabinet\components;

use app\components\Controller;
use Yii;

/**
 * Site controller
 */
class CabinetController extends Controller
{

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 'USER') {
                return true;
            }
            if (Yii::$app->user->identity->role == 'ADDITIONAL_USER') {
                return true;
            }
        }

        return $this->redirect(['/site/login']);
    }

}