<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 09.03.15
 * Time: 4:07
 */
/* @var $this TariffController */
/* @var $tariffs Tariff[] */
/* @var $transactions Transaction[] */
/* @var $tariffsTransposed Array[] */

use app\models\Tariff;

$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/css/bootstrap-editable.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/js/bootstrap-editable.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/jquery.dataTables.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/data-table.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/css/data-table.css");
?>

<div class="col-md-6 tariffs-panel-container">
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
                <?php foreach ($tariffsTransposed as $tariffAttribute): ?>
                    <tr>
                        <th>
                            <?= $tariffAttribute['label'] ?>
                        </th>
                        <?php foreach ($tariffAttribute['values'] as $tariffValue): ?>
                            <td>
                                <a class="tariff-value" href="#"
                                    <?php if (in_array($tariffAttribute['name'], Tariff::getBooleanAttributes())): ?>
                                        data-type="select"
                                    <?php else: ?>
                                        data-type="text"
                                    <?php endif; ?>
                                   data-name="<?= $tariffAttribute['name'] ?>"
                                   data-pk="<?= $tariffValue['id'] ?>"
                                   data-url="<?= $this->context->createUrl('/admin/tariff/edit-field') ?>"
                                    <?php if (in_array($tariffAttribute['name'], Tariff::getUnlimitedAttributes())): ?>
                                        data-title="-1 - Без ограничений"
                                    <?php endif; ?>
                                >
                                    <?= $tariffValue['value'] ?>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                </tr>
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
                                <a class="tariff-price" href="#"
                                   data-type="text"
                                   data-name="price"
                                   data-pk="<?= $durationPrice['id'] ?>"
                                   data-url="<?= $this->context->createUrl('/admin/tariff/edit-price') ?>"
                                >
                                    <?= $durationPrice['price'] ?>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Платежи
            </div>
        </div>
        <div class="panel-body">
            <!--<div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <input type="text" class="form-control" placeholder="Фильтр">
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped transactions-table data-table" id="data-table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Метод</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Клиент</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= $transaction->id ?></td>
                                <td><?= $transaction->method ?></td>
                                <td><?= $transaction->amount ?></td>
                                <td><?= date('H:i:s d.m.Y', strtotime($transaction->created)) ?></td>
                                <td><?= $transaction->user->getAccountNumber() ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $.fn.editable.defaults.mode = 'popup';
    $(document).ready(function () {
        $('a.tariff-value[data-type=text]').editable({
            display: function (value, response) {
                return response;
            }
        });
        $('a.tariff-value[data-type=select]').editable({
            source: [{value: 0, text: 'Нет'}, {value: 1, text: 'Да'}]
        });
        $('a.tariff-price').editable();
        App.init();
    });
</script>