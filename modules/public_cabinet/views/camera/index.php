<?php
/* @var $this CameraController */
/* @var $cameras Camera[] */

$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/isotope/isotope.css");
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/lightbox/css/lightbox.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/js/gallery.demo.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/lightbox/js/lightbox-2.6.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/isotope/jquery.isotope.min.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;


?>

<div class="page-camera-index-wrap">


    <div class="page-header camera-page-header p-l-15 p-b-10">
        <!--    Ваши камеры-->
        <div class="row">
            <div class="col-md-9">
                Мои камеры
            </div>
            <div class="col-md-3">
                <div class="dropdown change-view-wrap text-right">
                    <button class="btn btn-inverse m-r-10 btn-sm dropdown-toggle pull-right" type="button"
                            id="change-view"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Вид просмотра
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="change-view">
                        <li>
                            <div class="row view-menu">
                                <span>Вид просмотра </span>
                                <button class="btn btn-inverse btn-sm size-change" data-size="6" data-column="2">2
                                </button>
                                <button class="btn btn-inverse btn-sm size-change" data-size="4" data-column="3">3
                                </button>
                                <button class="btn btn-inverse btn-sm size-change active" data-size="3" data-column="4">
                                    4
                                </button>
                                <span>X</span>
                                <button class="btn btn-inverse btn-sm limit-change" data-size="4">4</button>
                                <button class="btn btn-inverse btn-sm limit-change" data-size="8">8</button>
                                <button class="btn btn-inverse btn-sm limit-change" data-size="16">16</button>
                                <button class="btn btn-inverse btn-sm limit-change" data-size="32">32</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="cameras-thumbs" style="overflow-y: scroll;" data-height="calc(100vh - 153px)" data-scrollbar="true">
    <?php if (count($cameras) > 0): ?>
        <?php foreach ($cameras as $cameraNumber => $camera): ?>
            <?php if ($cameraNumber == 0): ?>
                <div class="col-md-12"><h3><?= $camera->getLocationName() ?></h3></div>
            <?php elseif ($cameras[$cameraNumber - 1]['location_id'] !== $camera['location_id']): ?>
                <div class="col-md-12"><h3><?= $camera->getLocationName() ?></h3></div>
            <?php endif; ?>

            <div class="col-md-3 camera-thumb" camera-id="<?= $camera->id ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title text-center">
                            <i
                                class="fa fa-camera pull-left p-t-3"></i> <?= $camera->name; ?><?= $camera->enabled ? '' : ' <span class="text-danger">[Камера отключена]</span>' ?>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="image">
                            <a href="<?= $this->context->createUrl(['/public_cabinet/camera', 'id' => $camera->id]) ?>">
                                <img src="<?= $camera->getLastImage()->getThumbnailUrl(); ?>" class="img-responsive"/>
                            </a>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="big">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <?= date('d-m-y H:i', strtotime($camera->getLastImage()->created)); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <?= $camera->getCapturesQuantity(); ?> кадров/сутки
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <?= $camera->getTotalSize(); ?> Гб. (<?= $camera->getOccupiedPercent() ?>%)
                                        </div>
                                    </div>
                                </div>
                                <div class="small">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <?php
                                            $time = !empty($camera->getLastImage()->created) ? $camera->getLastImage()->created : $camera->created;
                                            ?>
                                            <?= date('d-m-y H:i', strtotime($time)); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row text-right">
                                            <?= $camera->getTotalSize(); ?> Гб. (<?= $camera->getOccupiedPercent() ?>%)
                                        </div>
                                        <div class="row text-right">
                                            <?= $camera->getCapturesQuantity(); ?> кадров/сутки
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row text-right camera-actions">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="fa fa-align-justify"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li>
                                                    <a href="<?= $this->context->createUrl(['/public_cabinet/camera', 'id' => $camera->id]) ?>">
                                                        <i class="fa fa-folder-open"></i> Просмотреть архив
                                                    </a>
                                                </li>
                                                <?php if (Yii::$app->user->identity->canEdit()): ?>
                                                    <li>
                                                        <a href="<?= $this->context->createUrl(['/public_cabinet/camera/manage']) ?>"><i
                                                                class="fa fa-refresh"></i> Работа с архивом</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">У вас пока нет подключенных камер...</div>
        <div class="row">
            <div class="no-camera-add">
                <div class="col-md-3">
                    <a href="<?= $this->context->createUrl(['/public_cabinet/camera/add']); ?>">
                        <div class="well">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4>Подключить камеру</h4>
                                </div>
                                <div class="col-md-12 text-center">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; //@todo ?>
</div>
<div class="clearfix"></div>
</div>

<script>
    var activeClass = 'col-md-3';

    $(document).on('click', '.toggle-camera', function (e) {
        e.preventDefault();
        var cameraOnOffButton = $(this);
        var cameraThumb = $('.camera-thumb[camera-id=' + $(cameraOnOffButton).attr('camera-id') + ']');
        $.post(yii.app.createUrl('public_cabinet/ajax/toggle-camera'), {
            id: $(cameraOnOffButton).attr('camera-id')
        }).done(function (response) {
            if (response == 'OK') {
                if ($(cameraOnOffButton).hasClass('active')) {
                    $(cameraOnOffButton).removeClass('active').find('span').removeClass('text-success').addClass('text-danger').find('span.text').text('Включить камеру');
                    $(cameraThumb).find('.panel-title').append(' <span class="text-danger">[Камера отключена]</span>');
                } else {
                    $(cameraOnOffButton).addClass('active').find('span').removeClass('text-danger').addClass('text-success').find('span.text').text('Выключить камеру');
                    $(cameraThumb).find('span.text-danger').remove();
                }
            }
        })
    });

    $(document).ready(function () {
        App.init();
        Gallery.init();
    });
    var activeClass = 'col-md-3';


    //Изменение размеров превью
    $(document).on('click', '.size-change', function () {
        var dataSize = $(this).attr('data-size');
        activeClass = 'col-md-' + dataSize;

        $('.size-change').removeClass('active');
        $(this).addClass('active');

        $('.camera-thumb').removeClass('col-md-6').removeClass('col-md-4').removeClass('col-md-3').removeClass('col-md-2').addClass(activeClass).addClass('fadeInUp animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('fadeInUp animated');
        });
    });
</script>