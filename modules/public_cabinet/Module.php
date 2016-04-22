<?php

namespace app\modules\public_cabinet;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\public_cabinet\controllers';

    public $defaultRoute = 'camera/camera';

    public $title = 'Камера';

    public function init()
    {
        Yii::setAlias('@public_cabinet', dirname(__FILE__));

        parent::init();

        // custom initialization code goes here
    }
}
