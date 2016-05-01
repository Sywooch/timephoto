<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 1:27
 */
/* @var $form CActiveForm */
/* @var $camera Camera */
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
        'class' => 'form-horizontal new-camera-form',
        'enctype' => 'multipart/form-data'
    ],
    'fieldConfig' => [
        'template' => "{input}\n{error}",
        //'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>

<div class="page-default-wrap camera-edit-wrap">
    <div class="col-md-12 divide-bottom">
        <h3 class="page-header">
            Изменение камеры "<?= $camera->name ?>"
        </h3>

        <?php $this->context->showMessages($camera); ?>
        <div class="col-md-5 left-column">
            <div class="form-group">
                <div class="col-sm-5">
                    <?php echo Html::label($camera->attributeLabels()['memory_limit'], ['class' => 'control-label ']) ?>
                </div>
                <div class="col-sm-7">

                    <div class="memory-slider" data-toggle="tooltip" data-placement="right"
                         title="Определите лимит объема для хранения снимков. Если объем будет исчерпан, камера не сможет принять снимки"></div>
                    <div class="m-t-5 text-center " id="volume"><?= round($camera->memory_limit / 1024, 2) ?> Gb.</div>
                    <?php echo $form->field($camera, 'memory_limit')->textInput(['class' => 'hidden', 'id' => 'memory_limit']); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="">
                    Использовано: <?= Yii::$app->user->identity->getTotalSize(); ?> Gb. <br>
                    Резерв на: <?= Yii::$app->user->identity->predictReservePeriod(); ?> дней
                </div>

            </div>
            <div class="form-group divide-bottom">
                <div class="row">
                    <div class="col-sm-4">
                        <?php echo Html::label($camera->attributeLabels()['storage_time'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo $form->field($camera, 'storage_time')->textInput(['class' => 'form-control', 'id' => 'storage-time',
                            'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'По истечении срока, кадры за указанный период будут удалены с указанной частотой']); ?>
                    </div>
                    <div class="col-sm-2 measurment-label text-center">
                        дней
                    </div>
                    <div class="col-sm-4">
                        <?php echo $form->field($camera, 'delete')->dropDownList([
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
                        ], ['class' => 'form-control']); ?>
                    </div>
                </div>
            </div>
            <div class="form-group divide-bottom">
                <?php echo $form->field($camera, 'comment')->textArea(['class' => 'form-control col-md-10', 'placeholder' => 'Комментарий к устройству']) ?>
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
                            <?php echo $form->field($camera, 'icon_file')->fileInput(['style' => 'display: none;', 'id' => 'camera-icon-input']) ?>
                            <button type="button" class="btn btn-white" id="trigger-file"><i
                                    class="fa fa-folder-open"></i> Выбрать
                                логотип
                            </button>
                            <i class="fa fa-close fa-2x text-danger remove-icon <?= !$camera->icon_name ? 'hidden' : '' ?>"
                               title="Удалить изображение"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center <?= !$camera->icon_name ? 'hidden' : '' ?>" id="camera-icon-preview">
                    <input type="text" name="hasIcon" class="hidden" id="hasIconInput"
                           value="<?= $camera->icon_name ? 'yes' : 'no' ?>">
                    <img src="<?= Yii::$app->homeUrl . 'uploads/camera_icons/' . $camera->icon_name ?>"/>
                </div>
            <?php endif; ?>
                <div class="form-group checkboxes-public">
                    <div class="col-lg-9 col-md-12 col-sm-12 ">
                        <?php echo $form->field($camera, 'public')->checkbox(); ?>
                    </div>
                </div>
                <div class="form-group public-link-wrap <?=$camera->public ? "show" : "" ?>">
                    <div class="col-sm-3">
                        Публичная ссылка
                    </div>
                    <div class="col-sm-9">
                        <div class="">
                            <textarea rows="10" cols="50"><?= $camera->getPublicCode()?></textarea>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
                <div class="col-sm-12 ">
                    Дата регистрации камеры: <?= date("d.m.Y", strtotime($camera->created)); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($camera->attributeLabels()['name'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($camera, 'name')->textInput(['class' => 'form-control col-md-10',
                        'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Например, "Вид на бассейн", "Отдел Маркетинга"'
                    ]); ?>
                </div>
            </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <?php echo Html::label($camera->attributeLabels()['location_id'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                  <?php echo $form->field($camera, 'location_id')->dropDownList($locations, ['class' => 'form-control col-md-10',
                    // 'data-toggle' =>"tooltip", 'data-placement'=> "bottom", 'title'=> 'Вы можете добавить объект, в котором расположена камера, по ссылке "Объекты"'
                  ]);?>
                </div>
              </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <?php echo Html::label($camera->attributeLabels()['camera_category_id'], ['class' => 'control-label pull-left']) ?>
                </div>
                <div class="col-sm-9">
                    <?php echo $form->field($camera, 'camera_category_id')->dropDownList($categories, ['class' => 'form-control col-md-10',
                        //'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Вы можете добавить тематику камеры по ссылке "Объекты"'
                    ]); ?>
                </div>
            </div>
            <?php if (!$camera->camera_registrator_id): ?>
                <div class="form-group">
                    <div class="col-sm-3">
                        <?php echo Html::label($camera->attributeLabels()['ftp_login'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-md-9">
                        <?php echo $form->field($camera, 'ftp_login')->textInput(['class' => 'form-control text-center', 'disabled' => 'disabled',
                            'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Используйте для соединения камеры с FTP сервером']); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <?php echo Html::label($camera->attributeLabels()['ftp_password'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo $form->field($camera, 'ftp_password')->passwordInput(['class' => 'form-control col-md-10',
                            'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Используйте для соединения камеры с FTP сервером']); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <?php echo Html::label($camera->attributeLabels()['format'], ['class' => 'control-label pull-left']) ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo $form->field($camera, 'format')->textInput(['class' => 'form-control col-md-10', 'data-toggle' => "tooltip", ]); ?>

                    </div>
                    <div class="col-sm-12 alert-name-file">
                        <h5>Используйте маску для распознания имени файлов </h5>

                        "<strong>-" </strong>- пропустить (необрабатывать) символ в имени файла <br>
                        "<strong>yyyy</strong>" - указывает год в имени файла, например "2016"<br>
                        "<strong>yy</strong>" - указывает год в имени файла, например "16"<br>
                        "<strong>mm</strong>" - указывает месяц в имени файла, например "04"<br>
                        "<strong>dd</strong>" - указывает день в имени файла, например "01"<br>
                        "<strong>hh</strong>" - указывает часы в имени файла, например "23"<br>
                        "<strong>ii</strong>" - указывает минуты в имени файла, например "15"<br>
                        "<strong>ss</strong>" - указывает секунды в имени файла, например "15"<br>
                        маска "<strong>---------------yyyymmddhhiiss</strong>" преобразует имя файла "<strong>MyNewHomeCamera20160401231515</strong>", получив время и дату <strong>01/04/16 23:15:15</strong>"

                    </div>
                </div>



            <?php else: ?>
                <div class="form-group">
                    <div class="col-sm-3">
                        <?php echo Html::label($camera->attributeLabels()['camera_registrator_id'], ['class' => 'control-label pull-left',
                            'data-toggle' => "tooltip", 'data-placement' => "bottom", 'title' => 'Например, "Вид на бассейн", "Отдел Маркетинга"']) ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo $form->field($camera, 'camera_registrator_id')->textInput(['class' => 'form-control text-center', 'disabled' => 'disabled']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-success">Сохранить изменения</button>
        <a href="#" id="remove-camera" class="btn btn-danger">Удалить камеру</a>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>
<script>
    var cameraId = <?=$camera->id?>;
    $(document).ready(function () {
        $('.memory-slider').slider({
            max: <?=Yii::$app->user->identity->getFreeSpace($camera->id)?>,
            min: 0.1,
            step: 0.1,
            value: <?=round($camera->memory_limit / 1024, 2)?>,
            slide: function (event, ui) {
                $("#volume").text(ui.value + ' Gb.');
                $('#memory_limit').val(ui.value * 1024);
            }
        });
        App.init();

        $('[name="Camera[public]"]').on('change', function(){
            if($('[name="Camera[public]"][value="1"]').prop("checked")){
                $('.public-link-wrap').addClass('show');
            }else{
                $('.public-link-wrap').removeClass('show');
            }
        });
    });

    $(document).on('click', '#trigger-file', function (e) {
        e.preventDefault();
        $('#camera-icon-input').trigger('click');
    });

    $(document).on('change', '#camera-icon-input', function (e) {
        $('.remove-icon').removeClass('hidden');
        readURL(this);
        $('#hasIconInput').val('yes');
    });

    $(document).on('click', '.remove-icon', function () {
        $(this).addClass('hidden');
        var fileField = $('#camera-icon-input');
        fileField.replaceWith(fileField = fileField.clone(true));
        $('#camera-icon-preview').addClass('hidden').find('img').removeAttr('src');
        $('#hasIconInput').val('no');
    });
    $(document).on('click', '#remove-camera', function (e) {
        e.preventDefault();
        swal({
            title: "Вы уверены?",
            text: "Камера и все данные будут безвозвратно удалены.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Удалить",
            cancelButtonText: "Отмена",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                //window.location.href = yii.app.createUrl('cabinet/camera/delete', {id: cameraId});

                $.post(yii.app.createUrl('cabinet/camera/delete', {id: cameraId}), {}).done(function (response) {
                })

            }
        });


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