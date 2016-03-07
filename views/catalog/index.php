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
            <a href="<?= Url::to(['/catalog/index', 'category' => $category['id']]) ?>" <?= $category['id'] == $activeCategory ? 'class="current-link"' : '' ?>><?= $category['name'] ?></a>
        <?php

        endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-12 devices">
            <?php foreach ($devices as $device): ?>
                <div class="col-md-6 device">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 images">
                                    <?php foreach ($device->devicePhotos as $index => $photo): ?>
                                        <img
                                            src="<?= Yii::$app->homeUrl . 'uploads/device_photos/' . $photo->file_name ?>"
                                            class="img-responsive <?= $index == 0 ? '' : 'hidden' ?>">
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-12 image-circles text-center">
                                    <?php foreach ($device->devicePhotos as $index => $photo): ?>
                                        <i class="fa fa-circle thumb-circle <?= $index == 0 ? 'active' : '' ?>"></i>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="row price-row">
                                <div class="col-md-12 text-center">
                                    <span class="price"><?= $device->price ?> P.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 name">
                                    <h4><?= $device->name ?></h4>
                                </div>
                                <?php foreach ($device->deviceFeatures as $feature): ?>
                                    <div class="col-md-12 feature">
                                        <div class="col-md-2 feature-icon">
                                            <i class="<?= $feature->icon ?>"></i>
                                        </div>
                                        <div class="col-md-10 feature-text">
                                            <?= $feature->name; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row">

        <?= $this->render('@app/views/elements/footer'); ?>
    </div>
</div>

<script>
    $(document).on('click', '.thumb-circle', function () {
        $(this).parent().find('i').removeClass('active');
        $(this).addClass('active').parent().parent().find('.images img').addClass('hidden');
        $(this).parent().parent().find('.images img:eq(' + $(this).index() + ')').removeClass('hidden');

    });
</script>
</body>
</html>
