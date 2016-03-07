<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 31.03.15
 * Time: 3:44
 */

$i = 1;

?>
<html>
<head>
    <title>Oblacam</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="<?php echo Yii::$app->homeUrl; ?>resources/css/axure_rp_page.css" type="text/css" rel="stylesheet">
    <link href="<?php echo Yii::$app->homeUrl; ?>data/styles.css" type="text/css" rel="stylesheet">
    <link href="<?php echo Yii::$app->homeUrl; ?>files/newland_gr/styles.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/fa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>css/landing.css">
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false&language=ru"></script>
</head>
<body>

<div class="container" style="width: 1360px;">
    <div class="row">
        <div class="col-md-12 header">
            <div class="logo"><i class="fa fa-cloud"></i> Oblacam</div>
            <div class="login pull-right">
                <div class="signin">
                    <i class="fa fa-sign-in"></i>
                </div>
                <div class="links">
                    <p><a href="<?= $this->createUrl('/site/login') ?>">Вход</a></p>

                    <p><a href="<?= $this->createUrl('/site/registration') ?>">Регистрация</a></p>
                </div>
            </div>
        </div>
    </div>
    <?= $content ?>
</div>
</body>
</html>