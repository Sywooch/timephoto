<html>
<head>
    <title>Timephoto - облачный фото регистратор</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/fa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>css/landing.css">
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false&language=ru"></script>
</head>
<body>
<div class="container" style="width: 1360px;">

    <?= $this->render('@app/views/elements/header'); ?>

    <div class="row">
        <?= $page->content?>
    </div>

    <div class="row">
        <?= $this->render('@app/views/elements/footer'); ?>
    </div>

</div>
</body>
</html>