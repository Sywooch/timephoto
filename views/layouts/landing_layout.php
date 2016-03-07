<?php
/* @var $this SiteController */
/* @var $content String */

$i = 1;

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
<!--    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />-->
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="<?php echo Yii::$app->homeUrl; ?>template/landing/plugins/bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo Yii::$app->homeUrl; ?>template/landing/plugins/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo Yii::$app->homeUrl; ?>css/landing.css" rel="stylesheet" />
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery.js"></script>
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery-migrate-1.2.1.min.js"></script>
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/slippry/slippry.min.js"></script>
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/js/bootstrap.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    <title>Oblacam - Каталог</title>
</head>
<body>
<?=$content;?>
</body>
</html>