<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.03.15
 * Time: 22:49
 */
/* @var $this UserController */
/* @var $form CActiveForm */

use app\models\Camera;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/masked-input/masked-input.min.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

?>

<div class="col-lg-8">
    <div class="col-lg-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-title">
                    Статистика
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Место</th>
                        <th>Камеры</th>
                        <th>Пользователи</th>
                        <th>Трафик</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Использовано</th>
                        <td><?= Yii::$app->user->identity->getTotalSize(Camera::SIZE_FORMAT_GB) ?> Gb</td>
                        <td><?= Yii::$app->user->identity->countCameras() ?></td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <th>Доступно</th>
                        <td><?= Yii::$app->user->identity->tariff->getTransposedByName('memory_limit') ?> Gb</td>
                        <td><?= Yii::$app->user->identity->tariff->getTransposedByName('cameras_quantity') ?></td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php if (Yii::$app->user->identity->canSms()): ?>
        <div class="col-lg-6">
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Привязать к телефону
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                Текущий номер:
                            </div>
                            <div class="col-xs-6" id="phone-number">
                                <?= Yii::$app->user->identity->getPhoneNumber(); ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!Yii::$app->user->identity->getPhoneNumber()): ?>
                        <div id="set-new-phone">
                            <div class="form-group">
                                <input type="text" name="newPhone" class="form-control" id="masked-input-phone"
                                       placeholder="Новый номер"/>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="button" id="send-code" class="btn btn-primary">Выслать код</button>
                            </div>
                            <div class="form-group <?= !Yii::$app->user->identity->hasSmsCode() ? 'faded' : '' ?>"
                                 id="code-container">
                                <div class="row">
                                    <div class="col-xs-12 m-t-20 m-b-20">
                                        <label for="code-check" class="p-t-5 col-xs-6">
                                            Код потдверждения:
                                        </label>

                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" id="code-check" placeholder="Код"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="button" id="submit-code" class="btn btn-success">Подтвердить номер
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-title">
                        SMS-оповещения
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-8">
                                Оповещение о входе в систему
                            </div>
                            <div class="col-xs-4">
                                <i class="fa fa-toggle-off trigger text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-lg-6">
        <?php if (Yii::$app->user->identity->canWatermark()): ?>
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Логотип
                    </div>
                </div>
                <div class="panel-body">

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ],
                        'fieldConfig' => [
                            'template' => "{input}\n{error}",
                            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-8">
                                Не выводить логотип сайта
                            </div>
                            <div class="col-xs-4">
                                <i class="fa <?= Yii::$app->user->identity->siteLogoHidden() ? 'fa-toggle-on' : 'fa-toggle-off' ?> trigger text-primary"
                                   id="toggle-site-logo"></i>
                                <?php echo $form->field(Yii::$app->user->identity, 'hide_site_logo')->checkbox(['class' => 'hidden', 'id' => 'hide_site_logo']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10 m-b-10">
                        <div class="col-xs-12 text-center">
                            Вывести Ваш логотип в шапке сайта
                        </div>
                    </div>
                    <div class="row m-t-10 m-b-10">
                        <div class="col-xs-11">
                            <?php echo $form->field(Yii::$app->user->identity, 'custom_logo_file')->fileInput(['class' => 'form-control', 'id' => 'custom-logo-input']); ?>
                        </div>
                        <div class="col-xs-1 p-t-5">
                            <i class="fa fa-close text-danger trigger <?= !Yii::$app->user->identity->hasCustomLogo() ? 'hidden' : '' ?> remove-icon"></i>
                        </div>
                    </div>
                    <div
                        class="col-xs-12 text-center <?= !Yii::$app->user->identity->hasCustomLogo() ? 'hidden' : '' ?> m-b-20"
                        id="custom-logo-preview">
                        <input type="text" name="hasCustomLogo" class="hidden" id="hasCustomLogo"
                               value="<?= Yii::$app->user->identity->hasCustomLogo() ? 'yes' : 'no' ?>">
                        <img
                            src="<?= Yii::$app->homeUrl . 'uploads/custom_logos/' . Yii::$app->user->identity->custom_logo ?>"
                            class="custom-logo-preview"/>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>

    $(document).ready(function () {
        $("#masked-input-phone").mask("+7 (999) 999-99-99");
        App.init();
    });
    $(document).on('click', '#toggle-site-logo', function () {
        var toggle = $(this);
        var checkBox = $('#hide_site_logo');
        if ($(this).hasClass('fa-toggle-off')) {
            $(checkBox).prop('checked', true);
            $(toggle).removeClass('fa-toggle-off').addClass('fa-toggle-on');
        } else {
            $(checkBox).prop('checked', false);
            $(toggle).removeClass('fa-toggle-on').addClass('fa-toggle-off');
        }
    });
    $(document).on('click', '#send-code', function () {

        $.post(yii.app.createUrl('cabinet/ajax/send-verification-code'), {
            phone: $('#masked-input-phone').val()
        }).done(function (response) {
            if (response == 'OK') {
                if ($('#code-container').hasClass('faded')) {
                    $('#code-container').css('display', 'block').animate({'height': 'auto'}, 200).removeClass('faded');
                }
            }
        });
    });
    $(document).on('click', '#submit-code', function () {

        $.post(yii.app.createUrl('cabinet/ajax/check-verification-code'), {
            code: $('#code-check').val()
        }).done(function (response) {
            response = JSON.parse(response);

            if (response.result == 'OK') {
                $('#phone-number').text(response.phone);
                $('#set-new-phone').animate({height: 0}, {
                    duration: 200, complete: function () {
                        $(this).remove()
                    }
                });
            }
        });
    });


    $(document).on('change', '#custom-logo-input', function (e) {
        $('.remove-icon').removeClass('hidden');
        readURL(this);
        $('#hasCustomLogo').val('yes');
    });

    $(document).on('click', '.remove-icon', function () {
        $(this).addClass('hidden');
        var fileField = $('#custom-logo-input');
        fileField.replaceWith(fileField = fileField.clone(true));
        $('#custom-logo-preview').addClass('hidden').find('img').removeAttr('src');
        $('#hasCustomLogo').val('no');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#custom-logo-preview img').attr('src', e.target.result);
                $('#custom-logo-preview').removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>