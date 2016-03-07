<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 1:27
 */
/* @var $this CameraController */
/* @var $form CActiveForm */
/* @var $newRegistrator Camera */
/* @var $locations [] */
/* @var $categories [] */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$i = 1;

?>

<div class="page-default-wrap camera-add">
    <?php $this->context->showMessages($newRegistrator); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
        'options' => [
            'class' => 'form-horizontal new-registrator-form',
            'enctype' => 'multipart/form-data'
        ],
        'fieldConfig' => [
            'template' => "{input}\n{error}",
            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="col-md-12 divide-bottom">
        <h3 class="page-header">
            Добавление регистратора
        </h3>
        <div class="col-md-5">
            <div class="form-group">
                <div class="col-sm-4">
                    <?php echo Html::label($newRegistrator->attributeLabels()['memory_limit'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom"
                       title="Определите лимит объема для хранения снимков. Если объем будет исчерпан, камера не сможет принять снимки"></i>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-md-3 p-t-5" id="volume"><?= Yii::$app->user->identity->getFreeSpace() ?>Gb.
                        </div>
                        <div class="col-md-9 p-t-10">
                            <div class="memory-slider"></div>
                        </div>
                    </div>
                    <?php echo $form->field($newRegistrator, 'memory_limit')->textInput(['class' => 'hidden', 'id' => 'memory_limit', 'value' => Yii::$app->user->identity->getFreeSpace()]); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 p-t-10">
                    Использовано: <?= Yii::$app->user->identity->getTotalSize(); ?> Gb.
                </div>
                <div class="col-md-6">
                <span class="pull-right">
                Резерв на: <?= Yii::$app->user->identity->predictReservePeriod(); ?> дней
                <i class="fa fa-question-circle control-label p-l-20" data-toggle="tooltip" data-placement="bottom"
                   title="Аналиттика вычисляет резерв по частоте кадров за последние сутки относительно оставшегося объема памяти и среднего размера файлов"></i>
                </span>
                </div>
            </div>
            <div class="form-group divide-bottom">
                <?php echo $form->field($newRegistrator, 'comment')->textArea(['class' => 'form-control col-md-10', 'placeholder' => 'Комментарий к устройству']) ?>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newRegistrator->attributeLabels()['prefix'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom" title='"'></i>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newRegistrator, 'prefix')->textInput(['id' => 'prefix']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
                <div class="col-sm-12 ">
                    Дата регистрации регистратора: <?= date("d.m.Y"); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newRegistrator->attributeLabels()['name'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom" title='Например, "Вид на бассейн", "Отдел Маркетинга"'></i>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newRegistrator, 'name')->textInput(['class' => 'form-control col-md-10']) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newRegistrator->attributeLabels()['location_id'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom"
                       title='Вы можете добавить объект, в котором расположена камера, по ссылке "Объекты"'></i>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newRegistrator, 'location_id')->dropDownList($locations, ['class' => 'form-control col-md-10']); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newRegistrator->attributeLabels()['ftp_login'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom" title="Используйте для соединения регистратора с FTP сервером"></i>
                </div>
                <div class="col-md-9">
                    <input type="text" disabled="disabled" id="Registrator_ftp_login" class="form-control text-center"
                           value="<?= $newRegistrator->getNewFtpLogin() ?>"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($newRegistrator->attributeLabels()['ftp_password'], ['class' => 'control-label pull-left']) ?>
                    <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip"
                       data-placement="bottom" title="Используйте для соединения регистратора с FTP сервером"></i>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($newRegistrator, 'ftp_password')->textInput(['class' => 'form-control col-md-10']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Добавить регистратор</button>
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
        $('#registrator-icon-input').trigger('click');
    });

    $(document).on('change', '#registrator-icon-input', function (e) {
        $('.remove-icon').removeClass('hidden');
        readURL(this);
    });

    $(document).on('click', '.remove-icon', function () {
        $(this).addClass('hidden');
        var fileField = $('#registrator-icon-input');
        fileField.replaceWith(fileField = fileField.clone(true));
        $('#registrator-icon-preview').addClass('hidden').find('img').removeAttr('src');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#registrator-icon-preview img').attr('src', e.target.result);
                $('#registrator-icon-preview').removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>