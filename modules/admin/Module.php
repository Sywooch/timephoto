<?php

namespace app\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $defaultRoute = 'client';

    public $title = 'Админка';

    public function init()
    {
        Yii::setAlias('@admin', dirname(__FILE__));

        parent::init();

        // custom initialization code goes here
    }
}
