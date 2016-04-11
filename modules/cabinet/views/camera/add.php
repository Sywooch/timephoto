<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 1:27
 */
/* @var $this CameraController */
/* @var $form CActiveForm */
/* @var $newCamera Camera */
/* @var $locations [] */
/* @var $categories [] */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$i = 1;

?>


<div class="page-default-wrap camera-add-wrap">
    <?php $this->context->showMessages($newCamera); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
        'options' => [
            'class' => 'form-horizontal new-camera-form',
            'enctype' => 'multipart/form-data'
        ],
        'fieldConfig' => [
            'template' => "{input}\n{error}",
            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="col-md-12 divide-bottom">
        <h3 class="page-header">
            Добавление камеры
        </h3>

        <div class="col-md-5">
            <div class="form-group">
                <div class="col-sm-5">
                    <?php echo Html::label($newCamera->attributeLabels()['memory_limit'], ['class' => 'control-label ']) ?>
                </div>
                <div class="col-sm-7">

                    <div class="memory-slider"></div>
                    <div class="m-t-5 text-center " id="volume"><?= Yii::$app->user->identity->getFreeSpace() ?>Gb.
                    </div>
                    <?php echo $form->field($newCamera, 'memory_limit')->textInput(['class' => 'hidden', 'id' => 'memory_limit', 'data-toggle' => "tooltip", 'data-placement' => "right",
                        'title' => "Определите лимит объема для хранения снимков. Если объем будет исчерпан, камера не сможет принять снимки"]); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="">
                    Использовано: <?= Yii::$app->user->identity->getTotalSize(); ?> Gb. <br>
                    Резерв на: <?= Yii::$app->user->identity->predictReservePeriod(); ?> дней
                </div>
                <div class="col-md-6">
                <span class="pull-right">
                </span>
                </div>
            </div>
            <div class="form-group divide-bottom">
                <div class="row">
                    <div class="col-sm-4">
                        <?php echo Html::label($newCamera->attributeLabels()['storage_time'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo $form->field($newCamera, 'storage_time')->textInput(['class' => 'form-control', "data-toggle" => "tooltip", "data-placement" => "bottom", "title" => "По истечении срока, кадры за указанный период будут удалены с указанной частотой"]) ?>
                    </div>
                    <div class="col-sm-2 measurment-label text-center">
                        дней
                    </div>
                    <div class="col-sm-4">
                        <?php echo $form->field($newCamera, 'delete')->dropDownList([
                            1 => 'Удалить в дианазоне',
                            2 => 'Оставить каждую 2-ю',
                            3 => 'Оставить каждую 3-ю',
                            4 => 'Оставить каждую 4-ю',
                            5 => 'Оставить каждую 5-ю',
                            6 => 'Оставить каждую 6-ю',
                            7 => 'Оставить каждую 7-ю',
                            8 => 'Оставить каждую 8-ю',
                            9 => 'Оставить каждую 9-ю',
                            10 => 'Оставить каждую 10-ю',
                        ], ['class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
            <div class="form-group divide-bottom">
                <?php echo $form->field($newCamera, 'comment')->textInput(['class' => 'form-control col-md-10', 'placeholder' => 'Комментарий к устройству']) ?>
            </div>
            <?php if (Yii::$app->user->identity->canWatermark()): ?>
                <div class="col-md-6 icon-upload">
                    <div class="row m-b-5">
                        <div class="col-md-12 text-center">
                            JPG, PNG, макс. 200x100px
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php echo $form->field($newCamera, 'icon_file')->fileInput(['style' => 'display: none;', 'id' => 'camera-icon-input']) ?>
                            <button type="button" class="btn btn-white" id="trigger-file"><i
                                    class="fa fa-folder-open"></i> Выбрать логотип
                            </button>
                            <i class="fa fa-close fa-2x text-danger remove-icon hidden" title="Удалить изображение"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center hidden" id="camera-icon-preview">
                    <img src=""/>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
                <div class="col-sm-12">
                    Дата регистрации камеры: <?= date("d.m.Y"); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newCamera->attributeLabels()['name'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newCamera, 'name')->textInput(['class' => 'form-control col-md-10', 'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Например, "Вид на бассейн", "Отдел Маркетинга"']); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newCamera->attributeLabels()['location_id'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newCamera, 'location_id')->dropDownList($locations, ['class' => 'form-control col-md-10',
                        'data-toggle' => "tooltip", 'data-placement' => "top", 'title' => 'Вы можете добавить объект, в котором расположена камера, по ссылке "Объекты"']); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newCamera->attributeLabels()['camera_category_id'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newCamera, 'camera_category_id')->dropDownList($categories, ['class' => 'form-control col-md-10', 'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Вы можете добавить тематику камеры по ссылке "Объекты"']); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newCamera->attributeLabels()['ftp_login'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-md-9">
                    <input type="text" disabled="disabled" id="Camera_ftp_login" data-toggle="tooltip"
                           data-placement="bottom" title="Используйте для соединения камеры с FTP сервером"
                           class="form-control text-center"
                           value="<?= Yii::$app->user->identity->accountNumber . '_' . $newCamera->getNextCameraNumberByUser() ?>"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newCamera->attributeLabels()['ftp_password'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newCamera, 'ftp_password')->textInput(['class' => 'form-control col-md-10', "data-toggle" => "tooltip", "data-placement" => "bottom", "title" => "Используйте для соединения камеры с FTP сервером"]); ?>
                </div>
            </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <?php echo Html::label($newCamera->attributeLabels()['format'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo $form->field($newCamera, 'format')->textInput(['class' => 'form-control col-md-10',
                            'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Используйте для распознания формата наименовая файлов']); ?>
                    </div>
                </div>
        </div>
    </div>
    <div class="col-md-12 ">
        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Добавить камеру</button>
    </div>

    <?php ActiveForm::end(); ?>


    <div class="clearfix"></div>
</div>


<script>
    $(document).ready(function () {
        $('.memory-slider').slider({
            max: <?=Yii::$app->user->identity->getFreeSpace()?>,
            min: 0.1,
            step: 0.1,
            value: <?=Yii::$app->user->identity->getFreeSpace()?>,
            slide: function (event, ui) {
                $("#volume").text(ui.value + ' Gb.');
                $('#memory_limit').val(ui.value);
            }
        });
        App.init();
    });

    $(document).on('click', '#trigger-file', function (e) {
        e.preventDefault();
        $('#camera-icon-input').trigger('click');
    });

    $(document).on('change', '#camera-icon-input', function (e) {
        $('.remove-icon').removeClass('hidden');
        readURL(this);
    });

    $(document).on('click', '.remove-icon', function () {
        $(this).addClass('hidden');
        var fileField = $('#camera-icon-input');
        fileField.replaceWith(fileField = fileField.clone(true));
        $('#camera-icon-preview').addClass('hidden').find('img').removeAttr('src');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#camera-icon-preview img').attr('src', e.target.result);
                $('#camera-icon-preview').removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>