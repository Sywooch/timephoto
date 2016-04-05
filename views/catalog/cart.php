<?php
/* @var $categories DeviceCategory[] */
/* @var $devices Device[] */
/* @var $activeCategory Integer */

use yii\helpers\Url;
$i = 1;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Oblacam - Каталог</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/fa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/css/bootstrap.min.css">
    <link href="<?= Yii::$app->homeUrl ?>css/catalog.css" type="text/css" rel="stylesheet"/>
    <link href="<?= Yii::$app->homeUrl ?>css/landing.css" type="text/css" rel="stylesheet"/>
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery.js"></script>
</head>
<body>
<div class="container catalog">
    <?= $this->render('@app/views/elements/header'); ?>
    <div class="col-md-12">
        <h3>КАТАЛОГ ONLINE-ФОТОКАМЕР</h3>
    </div>
    <div class="col-md-12 categories">
        <?php foreach ($categories as $category): ?>
            <a href="<?= Url::to(['/catalog/index', 'category' => $category['id']]) ?>"><?= $category['name'] ?></a>
            <?php

        endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-12 devices">

        </div>
    </div>

    <div class="row">
        <?= $this->render('@app/views/elements/footer'); ?>
    </div>
</div>

</body>
</html>
