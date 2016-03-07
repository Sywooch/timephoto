<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 23.03.15
 * Time: 17:41
 */

/* @var $id integer */
/* @var $amount double */
/* @var $date String */
/* @var $amountString String */

$i = 1;

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/css/bootstrap.min.css">
    <title>Счет</title>
</head>
<body>
<div class="container">
    <p><strong>ЗАО Солнечные системы</strong></p>

    <p>Адрес: 115522, Москва г, Каширское ш, д. 32, кор. 2.</p>

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <td>ИНН 7724011842</td>
                    <td colspan="2">КПП 772401001</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td><strong>Получатель</strong></td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td>ЗАО "Солнечные Системы"</td>
                    <td colspan="3"></td>
                    <td>Сч. №</td>
                    <td>40702810440150628801</td>
                </tr>
                <tr>
                    <td><strong>Банк получателя</strong></td>
                    <td colspan="3"></td>
                    <td>БИК</td>
                    <td>044525555</td>
                </tr>
                <tr>
                    <td>ПАО "ПРОМСВЯЗЬБАНК" Г.МОСКВА</td>
                    <td colspan="3"></td>
                    <td>Сч. №</td>
                    <td>30101810400000000555</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center" style="margin: 30px 0;">
            <h3>СЧЕТ № ОС <?= $id ?> от <?= $date ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Наименование услуги</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Услуги облачного сервиса. Пополнение клиентского счета №<?= Yii::$app->user->identity->getAccountNumber() ?>.</td>
                    <td><?= $amount ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Итого:</strong></td>
                    <td><?= $amount ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Всего НДС (18%):</strong></td>
                    <td><?= round($amount / 100 * 18) ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Всего к оплате с НДС:</strong></td>
                    <td><?= $amount ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <strong><?= $amountString ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="margin: 10px 0;">
            Руководитель предприятия _________________________ (Кретлов Б.С.)
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            Главный бухгалтер&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________ (Кретлов Б.С.)
        </div>
    </div>
</div>
</body>
</html>