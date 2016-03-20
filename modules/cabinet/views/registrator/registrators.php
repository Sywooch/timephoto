<?php
/* @var $this CameraController */
/* @var $cameras Camera[] */

$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/isotope/isotope.css");
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/lightbox/css/lightbox.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/js/gallery.demo.min.js");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/lightbox/js/lightbox-2.6.min.js");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/isotope/jquery.isotope.min.js");

$i = 1;

?>
<div class="page-camera-index-wrap">
    <div class="page-header camera-page-header p-l-15 p-b-10">
        <!--    Ваши камеры-->
        <div class="row">
            <div class="col-md-9">
                Мои регистраторы
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
    <div class="cameras-thumbs" style="overflow-y: scroll;" data-height="calc(100vh - 153px)" data-scrollbar="true">
        <?php if (count($this->context->registrators) > 0): ?>
            <?php foreach ($this->context->registrators as $cameraNumber => $registrator): ?>

                <?php if ($cameraNumber == 0): ?>
                    <div class="col-md-12"><h3><?= $registrator->getLocationName() ?></h3></div>
                <?php elseif ($this->context->registrators[$cameraNumber - 1]['location']['name'] !== $registrator['location']['name']): ?>
                    <div class="col-md-12"><h3><?= $registrator->getLocationName() ?></h3></div>
                <?php endif; ?>

                <div class="col-md-3 camera-thumb" camera-id="<?= $registrator->id ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">
                                <i
                                    class="fa fa-camera pull-left p-t-3"></i> <?= $registrator->name; ?><?= $registrator->enabled ? '' : ' <span class="text-danger">[Камера отключена]</span>' ?>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="image">
                                <a href="<?= $this->context->createUrl(['/cabinet/registrator', 'id' => $registrator->id]) ?>">
                                    <img src="<? //= $registrator->getLastImage()->getThumbnailUrl(); ?>"
                                         class="img-responsive"/>
                                </a>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="big">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <? //= $registrator->getLastImage()->created; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <? //= $registrator->getCapturesQuantity(); ?> кадров/сутки
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <? //= $registrator->getTotalSize(); ?> Гб.
                                                (<? //= $registrator->getOccupiedPercent() ?>%)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="small">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <? //= $registrator->getLastImage()->created; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="row text-right">
                                                <? //= $registrator->getTotalSize(); ?> Гб.
                                                (<? //= $registrator->getOccupiedPercent() ?>%)
                                            </div>
                                            <div class="row text-right">
                                                <? //= $registrator->getCapturesQuantity(); ?> кадров/сутки
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
                                                        <a href="<?= $this->context->createUrl(['/cabinet/registrator', 'id' => $registrator->id]) ?>">
                                                            <i class="fa fa-folder-open"></i> Просмотреть архив
                                                        </a>
                                                    </li>
                                                    <?php if (Yii::$app->user->identity->canEdit()): ?>
                                                        <li>
                                                            <a href="<?= $this->context->createUrl('/cabinet/registrator/manage') ?>"><i
                                                                    class="fa fa-refresh"></i> Работа с архивом</a></li>
                                                    <?php endif; ?>
                                                    <?php if (Yii::$app->user->identity->role == 'USER'): ?>
                                                        <li>
                                                            <a href="<?= $this->context->createUrl(['/cabinet/registrator/edit', 'id' => $registrator->id]) ?>">
                                                                <i class="fa fa-cog"></i> Настройки регистратора
                                                            </a>
                                                        </li>
                                                        <?php /*?>
                                                        <li>
                                                          <a href="#" class="toggle-camera <?= $registrator->enabled ? 'active' : '' ?>"
                                                             camera-id="<?= $registrator->id ?>">
                                                                                    <span
                                                                                        class="<?= $registrator->enabled ? 'text-success' : 'text-danger' ?>">
                                                                                        <i class="fa fa-share-alt"></i> <span
                                                                                          class="text"><?= $registrator->enabled ? 'Выключить публичный доступ' : 'Включить публичный доступ' ?></span>
                                                                                    </span>
                                                          </a>
                                                        </li>
                                                        */ ?>
                                                        <li>
                                                            <a href="#"
                                                               class="toggle-camera <?= $registrator->enabled ? 'active' : '' ?>"
                                                               camera-id="<?= $registrator->id ?>">
                                                        <span
                                                            class="<?= $registrator->enabled ? 'text-success' : 'text-danger' ?>">
                                                            <i class="fa fa-power-off"></i> <span
                                                                class="text"><?= $registrator->enabled ? 'Выключить регистратор' : 'Включить регистратор' ?></span>
                                                        </span>
                                                            </a>
                                                        </li>
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
            <div class="alert alert-warning" role="alert">У регистратора пока нет подключенных камер...</div>
        <?php endif; //@todo ?>
    </div>

    <div class="clearfix"></div>
</div>


<script>
    $(document).on('click', '.toggle-camera', function (e) {
        e.preventDefault();
        var cameraOnOffButton = $(this);
        var cameraThumb = $('.camera-thumb[camera-id=' + $(cameraOnOffButton).attr('camera-id') + ']');
        $.post(
            yii.app.createUrl('cabinet/ajax/toggle-camera'),
            {
                id: $(cameraOnOffButton).attr('camera-id')
            }
        ).done(function (response) {
            if (response == 'OK') {
                if ($(cameraOnOffButton).hasClass('active')) {
                    $(cameraOnOffButton).removeClass('active').find('span').removeClass('text-success').addClass('text-danger').find('span.text').text('Включить камеру');
                    $(cameraThumb).find('.panel-title').append(' <span class="text-danger">[Камера отключена]</span>');
                }
                else {
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

        $('.camera-thumb')
            .removeClass('col-md-6')
            .removeClass('col-md-4')
            .removeClass('col-md-3')
            .removeClass('col-md-2')
            .addClass(activeClass).addClass('fadeInUp animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('fadeInUp animated');
        });
    });

    //настройки регистратора
    $(document).on('click', '.registrator-settings', function () {
        var id = $(this).attr('data-id');
        location.href = "/cabinet/registrator/edit/" + id;
    });

</script>