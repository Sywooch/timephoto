<?php

namespace app\modules\cabinet;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\cabinet\controllers';

    public $defaultRoute = 'camera/dashboard';

    public $title = 'Камера';

    public function init()
    {
        Yii::setAlias('@cabinet', dirname(__FILE__));

        parent::init();

        // custom initialization code goes here
    }
}
