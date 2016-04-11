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
    <script src="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/js/bootstrap.min.js"></script>
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
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-12 devices">
            <div class="col-md-6 product-wrap">
                <div class="col-md-5">
                    <div class="col-md-12 images">
                        <div class="row text-center">
                            <?php foreach ($device->devicePhotos as $index => $photo): ?>
                                <div class="col-md-4 device-preview">
                                    <img class="img-responsive" src="<?= Yii::$app->homeUrl . 'uploads/device_photos/' . $photo->file_name ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="device-image-container">
                            <a href="#"  data-toggle="modal" data-target="#preview-modal">
                                <img class="img-responsive"
                                     src="<?= Yii::$app->homeUrl . 'uploads/device_photos/' . $device->devicePhotos[0]->file_name ?>">
                            </a>
                        </div>
                        <a href="<?=Url::to(['/catalog/add-to-cart', 'id'=>$device->id])?>" class="btn btn-cart">Купить</a>
                    </div>
                </div>
                <div class="col-md-7 product-description">
                    <h1><?= $device->name ?></h1>
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

                    <p><?= $device->description ?></p>
                </div>
            </div>
            <div class="col-md-6 cart">
                <?php
                foreach (Yii::$app->cart->getPositions() as $pos) {
                    echo 'id: ' . $pos->name . ', cost: ' . $pos->getCost().', qqq: ' . $pos->getQuantity();
                }
                //echo $itemsCount = Yii::$app->cart->getCount();
                //echo $total = Yii::$app->cart->getCost();
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <?= $this->render('@app/views/elements/footer'); ?>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <img src="#" alt="">
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.thumb-circle', function () {
        $(this).parent().find('i').removeClass('active');
        $(this).addClass('active').parent().parent().find('.images img').addClass('hidden');
        $(this).parent().parent().find('.images img:eq(' + $(this).index() + ')').removeClass('hidden');
    });

    $('.device-preview img').on('click', function () {
        $('#device-image-container img').attr('src', $(this).attr('src'));
    });

    $('#preview-modal').on('show.bs.modal', function () {
       $('#preview-modal img').attr('src', $('#device-image-container img').attr('src'));
        console.log('asdas');
    })
</script>
</body>
</html>
