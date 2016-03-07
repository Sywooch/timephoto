<?php

return [
  'components' => [

    'user' => [
      'class' => '\yii\web\User',
      'identityClass' => 'app\models\User',
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
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=timephoto_loc',
      'username' => 'root',
      'password' => 'password',
      'charset' => 'utf8',
    ],

  ],

];
