<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 1:27
 */
/* @var $form CActiveForm */
/* @var $registrator Camera */
/* @var $locations [] */
/* @var $categories [] */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$i = 1;

?>

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
        Изменение регистратора "<?= $registrator->name ?>"
    </h3>

    <?php $this->context->showMessages($registrator); ?>

    <div class="col-md-5">
        <div class="form-group">
            <div class="col-sm-4">
                <?php echo Html::label($registrator->attributeLabels()['memory_limit'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title="Определите лимит объема для хранения снимков. Если объем будет исчерпан, камера не сможет принять снимки"></i>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-md-3 p-t-5" id="volume"><?= round($registrator->memory_limit / 1024, 2) ?> Gb.</div>
                    <div class="col-md-9 p-t-10">
                        <div class="memory-slider"></div>
                    </div>
                </div>
                <?php echo $form->field($registrator, 'memory_limit')->textInput(['class' => 'hidden', 'id' => 'memory_limit']) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 p-t-10">
                Использовано: <?= Yii::$app->user->identity->getTotalSize(); ?> Gb.
            </div>
            <div class="col-md-6 text-center">
                <span class="pull-right">
                Резерв на: <?= Yii::$app->user->identity->predictReservePeriod(); ?> дней
                <i class="fa fa-question-circle control-label p-l-20" data-toggle="tooltip" data-placement="bottom"
                   title="Аналиттика вычисляет резерв по частоте кадров за последние сутки относительно оставшегося объема памяти и среднего размера файлов"></i>
                </span>
            </div>
        </div>
        <div class="form-group divide-bottom">
            <?php echo $form->field($registrator, 'comment')->textInput(['class' => 'form-control col-md-10', 'placeholder' => 'Комментарий к устройству']) ?>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo Html::label($registrator->attributeLabels()['prefix'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title='Например, "Вид на бассейн", "Отдел Маркетинга"'></i>
            </div>
            <div class="col-sm-9">
                <?php echo $form->field($registrator, 'prefix')->textInput(['class' => 'form-control col-md-10']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-md-offset-1">
        <div class="form-group">
            <div class="col-sm-12 ">
                Дата регистрации регистратора: <?= date("d.m.Y", $registrator->create_date); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo Html::label($registrator->attributeLabels()['name'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title='Например, "Вид на бассейн", "Отдел Маркетинга"'></i>
            </div>
            <div class="col-sm-9">
                <?php echo $form->field($registrator, 'name')->textInput(['class' => 'form-control col-md-10']) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo Html::label($registrator->attributeLabels()['location_id'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title='Вы можете добавить объект, в котором расположена камера, по ссылке "Объекты"'></i>
            </div>
            <div class="col-sm-9">
                <?php echo $form->field($registrator, 'location_id')->dropDownList($locations, ['class' => 'form-control col-md-10']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo Html::label($registrator->attributeLabels()['ftp_login'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title="Используйте для соединения камеры с FTP сервером"></i>
            </div>
            <div class="col-md-9">
                <?php echo $form->field($registrator, 'ftp_login')->textInput(['class' => 'form-control text-center', 'disabled' => 'disabled']); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo Html::label($registrator->attributeLabels()['ftp_password'], ['class' => 'control-label pull-left']) ?>
                <i class="fa fa-question-circle control-label pull-right" data-toggle="tooltip" data-placement="bottom"
                   title="Используйте для соединения камеры с FTP сервером"></i>
            </div>
            <div class="col-sm-9">
                <?php echo $form->field($registrator, 'ftp_password')->textInput(['class' => 'form-control col-md-10']) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 text-center">
    <button type="submit" class="btn btn-success">Сохранить изменения</button>
    <a href="#" id="remove-registrator" class="btn btn-danger">Удалить регистратор</a>
</div>
<?php ActiveForm::end(); ?>

<script>
    var registratorId = <?=$registrator->id?>;
    $(document).ready(function () {
        $('.memory-slider').slider({
            max: <?=Yii::$app->user->identity->getFreeSpace($registrator->id)?>,
            min: 0.1,
            step: 0.1,
            value: <?=round($registrator->memory_limit / 1024, 2)?>,
            slide: function (event, ui) {
                $("#volume").text(ui.value + ' Gb.');
                $('#memory_limit').val(ui.value * 1024);
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
        $('#hasIconInput').val('yes');
    });

    $(document).on('click', '.remove-icon', function () {
        $(this).addClass('hidden');
        var fileField = $('#registrator-icon-input');
        fileField.replaceWith(fileField = fileField.clone(true));
        $('#registrator-icon-preview').addClass('hidden').find('img').removeAttr('src');
        $('#hasIconInput').val('no');
    });
    $(document).on('click', '#remove-registrator', function (e) {
        e.preventDefault();
        swal({
                title: "Вы уверены?",
                text: "Регистратор и все данные будут безвозвратно удалены.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить",
                cancelButtonText: "Отмена",
                closeOnConfirm: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = yii.app.createUrl('public_cabinet/registrator/delete', {id: registratorId});
                }
            });
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