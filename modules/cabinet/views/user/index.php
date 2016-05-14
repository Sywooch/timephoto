<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 26.02.15
 * Time: 22:45
 */
/* @var $form CActiveForm */
/* @var $user User */
/* @var $tariffs Tariff[] */

use app\models\Tariff;
use yii\widgets\ActiveForm;

$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/switchery/switchery.min.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/switchery/switchery.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/masked-input/masked-input.min.js", ['position' => yii\web\View::POS_HEAD]);

// Handlebars is largely compatible with Mustache templates.
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

?>

<div class="col-md-12">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Учетные данные
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                            E-mail:
                        </div>
                        <div class="col-xs-6 text-content">
                            <?= $user->login; ?>
                        </div>
                        <div class="col-xs-6">
                            Счет:
                        </div>
                        <div class="col-xs-6 text-content">
                            <?= $user->getAccountNumber(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Смена пароля
                    </div>
                </div>
                <?php if (Yii::$app->session->hasFlash('PASSWORD_OK')): ?>
                    <div class="alert alert-success fade in">
                        <span class="close" data-dismiss="alert">×</span>
                        <i class="fa fa-check fa-2x pull-left"></i>

                        <p><?= Yii::$app->session->getFlash('PASSWORD_OK') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('PASSWORD_ERR')): ?>
                    <div class="alert alert-danger fade in">
                        <span class="close" data-dismiss="alert">×</span>
                        <i class="fa fa-times fa-2x pull-left"></i>

                        <p><?= Yii::$app->session->getFlash('PASSWORD_ERR') ?></p>
                    </div>
                <?php endif; ?>

                <div class="panel-body">

                    <?php $form = ActiveForm::begin([
                        'id' => 'password-form',
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

                    <div class="form-group">
                        <div class="">
                            <?php echo $form->field($user, 'oldPassword')->passwordInput(['class' => 'form-control', 'value' => '', 'placeholder' => 'Текущий пароль']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="">
                            <?php echo $form->field($user, 'newPassword')->passwordInput(['class' => 'form-control', 'placeholder' => 'Новый пароль']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="">
                            <?php echo $form->field($user, 'repeatPassword')->passwordInput(['class' => 'form-control', 'placeholder' => 'Повторите новый пароль']); ?>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-default">Сохранить</button>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="panel-title">
                        Экстренные
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row m-b-15">
                        <div class="col-xs-6 col-xs-offset-2">
                            Отключить все камеры
                        </div>
                        <div class="col-xs-4">
                            <i class="fa text-danger disable-all-cameras <?= !$user->cameras_enabled ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                        </div>
                    </div>
                    <div class="col-xs-12 text-center">
                        <button type="button" class="btn btn-link delete-all">Удалить все изображения</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Платежные данные
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6 p-t-10">
                                Баланс счета:
                            </div>
                            <div class="col-xs-6">
                                <h4><?= $user->getBalance(); ?> p.</h4>
                            </div>
                        </div>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'invoice-form',
                        'action' => ['/cabinet/user/invoice'],
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'options' => [
                            'target' => '_blank',
                            //'class' => 'form-horizontal new-camera-form',
                            //'enctype' => 'multipart/form-data'
                        ],
                        'fieldConfig' => [
                            'template' => "{input}\n{error}",
                            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><input type="radio" name="paymentType" id="robokassa" value="robokassa" checked>
                                    Электронный платеж</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group p-t-3">
                                <label><input type="radio" name="paymentType" id="invoice" value="invoice"> Выписать
                                    счет</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="funds-amount" name="amount"
                                       placeholder="Введите сумму">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <button type="submit" id="payment-btn" class="btn btn-success">Пополнить счет</button>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div class="panel panel-default panel-gray">
                <div class="panel-heading">
                    <div class="panel-title">
                        Тарифный план
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                Ваш тарифный план:
                                <span id="tariff-name"><?= $user->getTariffName(); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row <?= $user->getTariffExpireDate() ? '' : 'hidden' ?>">
                        <div class="col-xs-12 text-center">
                            действует до <span class="label label-success"
                                               id="tariff-expire-date"><?= $user->getTariffExpireDate() ?></span>
                        </div>
                    </div>
                    <div class="row m-t-20">
                        <div class="col-xs-12 text-center">
                            <a href="#modal-change-tariff" data-toggle="modal">Сменить тарифный план</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                Функционал тарифа
                            </th>
                            <th>
                                <?= $user->getTariffName() ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (Yii::$app->user->identity->tariff->getTransposed() as $tariffElement): ?>
                            <?php if (in_array($tariffElement['name'], Tariff::getNotWorkingAttributes())): ?>
                                <tr class="gray-attribute">
                            <?php else: ?>
                                <tr>
                            <?php endif; ?>
                            <td><?= $tariffElement['label'] ?></td>
                            <td><?= $tariffElement['value'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="disable-cameras-popover" style="display: none;">
        <div class="form-group">
            <input type="text" class="form-control" id="disable-cameras-password" value="" placeholder="Пароль">
        </div>
        <div class="form-group text-center">
            <button type="button" class="btn btn-default btn-sm" id="do-disable-cameras">ОК</button>
        </div>
    </div>
    <div id="delete-all-popover" style="display: none;">
        <div class="form-group">
            <input type="text" class="form-control" id="delete-all-password"  value="" placeholder="Пароль">
        </div>
        <div class="form-group text-center">
            <button type="button" class="btn btn-default btn-sm" id="do-delete-all">ОК</button>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-change-tariff">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Тарифы
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Функционал</th>
                                <?php foreach ($tariffs as $tariff): ?>
                                    <th><?= $tariff->name ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (Tariff::getAllTransposed() as $tariffAttribute): ?>
                                <tr>
                                    <th>
                                        <?= $tariffAttribute['label'] ?>
                                    </th>
                                    <?php foreach ($tariffAttribute['values'] as $tariffValue): ?>
                                        <td>
                                            <?= $tariffValue['value'] ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Цены
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped prices-table">
                            <thead>
                            <tr>
                                <th>Функционал</th>
                                <?php foreach ($tariffs as $tariff): ?>
                                    <th><?= $tariff->name ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (Tariff::getAllPricesTransposed() as $tariffDuration): ?>
                                <tr>
                                    <th>
                                        <?= $tariffDuration['name'] ?>
                                    </th>
                                    <?php foreach ($tariffDuration['prices'] as $durationPrice): ?>
                                        <td>
                                            <div><?= $durationPrice['price'] ?></div>
                                            <?php if ($durationPrice['selectable']): ?>
                                                <input type="radio" class="form-control tariff-switch-radio"
                                                       name="tariffDuration" value="<?= $durationPrice['id'] ?>"/>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-sm btn-success" id="tariff-switch" disabled="disabled">Сменить
                    тариф на выбранный
                </button>
                <button type="button" class="btn btn-white" data-dismiss="modal" aria-hidden="true">Отмена</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="payment-modal-body">

            </div>
        </div>
    </div>

    <script id="payment-modal-template" type="text/x-handlebars-template">
        <div class="row">
            <div class="col-md-12 text-center">
                <span>Сумма к оплате: <strong>{{out_summ}}Р.</strong></span>

                <form action='{{actionUrl}}' id="{{inv_id}}" method=POST>
                    <input type=hidden name=MrchLogin value={{mrh_login}}>
                    <input type=hidden name=OutSum value={{out_summ}}>
                    <input type=hidden name=InvId value={{inv_id}}>
                    <input type=hidden name=Desc value='{{inv_desc}}'>
                    <input type=hidden name=SignatureValue value="{{signature}}">
                    <input type=hidden name=IncCurrLabel value={{in_curr}}>
                    <input type=hidden name=Culture value={{culture}}>
                    <input type=hidden name=Shp_transaction value={{Shp_transaction}}>
                    <button type=submit class='btn btn-success'>Оплатить</button>
                </form>
            </div>
        </div>
    </script>

</div>

<script>
    var password = '';

    $(".disable-all-cameras").popover({
        placement: 'top', container: 'body', html: true, content: function () {
            return $('#disable-cameras-popover').html();
        }
    });
    $(".delete-all").popover({
        placement: 'top', container: 'body', html: true, content: function () {
            return $('#delete-all-popover').html();
        }
    });

    $(document).on('click', '#do-disable-cameras', function () {
        var trigger = $('.disable-all-cameras');
        if (trigger.hasClass('fa-toggle-on')) {
            $.post(yii.app.createUrl('cabinet/user/ajax-toggle-all-cameras'), {
                password: password, action: 'enable'
            }).done(function (response) {
                if (response == 'OK') {
                    trigger.removeClass('fa-toggle-on').addClass('fa-toggle-off');
                    $('.disable-all-cameras').popover('hide');
                    $('#disable-cameras-password').val('');
                }
            });
        } else {
            $.post(yii.app.createUrl('cabinet/user/ajax-toggle-all-cameras'), {
                password: password, action: 'disable'
            }).done(function (response) {
                if (response == 'OK') {
                    trigger.removeClass('fa-toggle-off').addClass('fa-toggle-on');
                    $('.disable-all-cameras').popover('hide');
                    $('#disable-cameras-password').val('');
                } else
                    sweetAlert("Неверный пароль", "", "error");
            });
        }
        password = '';
    });
    $(document).on('click', '#do-delete-all', function () {
        swal({
            title: "Вы уверены?",
            text: "Фотографии будут удалены",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Удалить",
            cancelButtonText: "Отмена",
            closeOnConfirm: false
        }, function () {
            $.post(yii.app.createUrl('cabinet/remove/delete-all'), {
                password: password
            }).done(function (response) {
                if (response == 'OK') {
                    swal("Удалено!", "Вы удалили все фото", "success");
                    $('.delete-all').popover('hide');
                    $('#delete-all-password').val('');
                } else
                    sweetAlert("Неверный пароль", "", "error");
            });
        });
    });
    $(document).on('change', '#disable-cameras-password, #delete-all-password', function (e) {
        password = $(this).val();
    });
    $(document).ready(function () {
        //$('#funds-amount').mask('9+');
        App.init();
    });

    $(document).on('click', '.tariff-switch-radio', function () {
        $('#tariff-switch').removeAttr('disabled');
    });
    $(document).on('click', '#tariff-switch', function () {
        var selectedRadio = $('.tariff-switch-radio:checked');
        if (selectedRadio.length > 0) {
            $.post(yii.app.createUrl('cabinet/user/ajax-change-tariff'), {
                tariffDurationId: $(selectedRadio).val()
            }).done(function (response) {
                response = JSON.parse(response);

                if (response.result == 'OK') {
                    swal("Тариф изменен", "Ваш текущий тариф - " + response.name, 'success');
                    $('#tariff-name').text(response.name);
                    $('#tariff-expire-date').text(response.expires);
                    $('#modal-change-tariff').modal('hide');
                    $('.tariff-switch-radio').each(function (index, radio) { //Deselect all
                        $(radio).prop('checked', false);
                    });
                } else if (response.result == 'LOW_FUNDS') {
                    swal("Тариф не был изменен", "У вас не хватает средств", 'error');
                }
            });
        }
    });

    $(document).on('click', '#payment-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var amount = $('#funds-amount').val();
        if (amount !== '') {
            if ($('#robokassa').prop('checked')) {
                $.post(yii.app.createUrl('cabinet/user/ajax-payment-modal'), {
                    amount: amount
                }).done(function (response) {

                    var template = Handlebars.compile($('#payment-modal-template').html());
                    $('#payment-modal-body').html(template(JSON.parse(response)));
                    $('#modal-payment').modal('show');
                });
            } else if ($('#invoice').prop('checked')) {
                $('#invoice-form').submit();
            }
        }
    })
</script>