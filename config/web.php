<?php
use yii\helpers\ArrayHelper;

if (file_exists(__DIR__ . '/params_local.php')) {
    $params = require(__DIR__ . '/params_local.php');
} else {
    $params = require(__DIR__ . '/params.php');
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'OTZrQutzkU4st5IFXttZOyHn9-xX3-1Y',
        ],
        'user' => [
            'class' => '\yii\web\User',
            'identityClass' => 'app\components\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_mydomain',
                'domain' => '.cam.loc',
                'path' => '/',
            ]
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '__session',
            'cookieParams' => [
                'path' => '/',
                'domain' => '.cam.loc',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // Подменяет стандартную обработку ЧПУ
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'rules' => [

                //'<controller:\w+>/<id:\d+>' => '<controller>/view',
                //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',

                // Автоматическое создание превью, для публичных камер
                'camera/<id>/image/<preset:[a-z0-z_-]+>' => 'imageapi/image/get',

                'cabinet/camera/edit/<id>' => 'cabinet/camera/edit',
                'cabinet/object' => 'cabinet/object/index',
                'public_cabinet/camera' => 'public/camera/index',
                'public_cabinet/public' => 'public/camera/public',
                'public_cabinet/camera' => 'public_cabinet/camera/index',
                'public_cabinet/public' => 'public_cabinet/camera/public',
                'cabinet/user/account' => 'cabinet/user/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'registration' => 'site/registration',
                'catalog/category/<category:\d+>' => 'catalog/index',
                'catalog/product/<id:\d+>' => 'catalog/product-view',
                '<view:\w+>' => 'site/page',

            ],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
            'cartId' => 'my_application_cart',
        ],
        // Генерация картинок для превьюшек или заданного по пресету размеру
        'image' => require(__DIR__ . '/image_cache.php'),
    ],
    'modules' => [
        'cabinet' => [
            'class' => 'app\modules\cabinet\Module',
        ],
        'public_cabinet' => [
            'class' => 'app\modules\public_cabinet\Module',
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'imageapi' => [
            'class' => 'app\modules\imageapi\Module',
        ],
    ],
    'params' => $params,
];


// Для уникальных настроек хоста
if (file_exists(__DIR__ . '/local.php')) {
    $config = ArrayHelper::merge($config, require(__DIR__ . '/local.php'));
}

if (YII_ENV_DEV) {

    // configuration adjustments for 'dev' environment
    /*$config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];*/

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
