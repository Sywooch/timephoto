<?php
/* @var $this ClientController */
/* @var $users User[] */

$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/css/data-table.css");
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/css/bootstrap-editable.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/jquery.dataTables.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/data-table.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/js/bootstrap-editable.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);
?>
<div class="col-md-6 clients-panel-container">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Клиенты
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover data-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>E-mail</th>
                    <th>Камер</th>
                    <th>ПоКл</th>
                    <th>Место</th>
                    <th>Тариф</th>
                    <th>Баланс</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr class="client-row" client-id="<?= $user->id ?>">
                        <td><?= $user->getAccountNumber() ?></td>
                        <td><?= $user->login ?></td>
                        <td><?= $user->countCameras() ?></td>
                        <td>0</td>
                        <td><?= $user->getTotalSize() ?> Gb.</td>
                        <td><?= $user->tariff->name ?></td>
                        <td><?= $user->getBalance() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-md-6 client-info-container">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Клиент
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td id="client-account"></td>
                    <td id="client-created"></td>
                    <td>Активен: <a href="#" id="client-active" data-type="select" data-pk=""
                                    data-url="<?= $this->context->createUrl('/admin/client/edit-field') ?>"
                                    data-name="active"></a></td>
                    <td>Тариф: <a href="#" id="client-tariff" data-type="select" data-pk=""
                                  data-url="<?= $this->context->createUrl('/admin/client/edit-field') ?>"
                                  data-name="tariff_id"></a></td>
                </tr>
                <tr>
                    <td><a href="#" id="client-login" data-type="text" data-pk=""
                           data-url="<?= $this->context->createUrl('/admin/client/edit-field') ?>" data-name="login"></a>
                    </td>
                    <td><a href="#" id="client-password" data-type="text" data-pk=""
                           data-url="<?= $this->context->createUrl('/admin/client/edit-field') ?>"
                           data-name="password"></a></td>
                    <td id="client-balance"></td>
                    <td id="client-tariff-expires"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#cameras" data-toggle="tab">Камеры</a></li>
        <li class=""><a href="#users" data-toggle="tab">Пользователи</a></li>
        <li class=""><a href="#transactions" data-toggle="tab">Движение ДС</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="cameras">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Пароль</th>
                        <th>Объем</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody id="cameras-body">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="users">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Пароль</th>
                </tr>
                </thead>
                <tbody id="users-body">

                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="transactions">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Сумма</th>
                        <th>Дата</th>
                        <th>Операция</th>
                    </tr>
                    </thead>
                    <tbody id="transactions-body">

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Сумма" id="add-sum">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="№ Счета, дата" id="invoice-details">
                </div>
                <button type="button" class="btn btn-success" id="do-add-funds"><i class="fa fa-plus"></i> Зачислить на
                    счет
                </button>
            </div>
        </div>
    </div>
</div>

<script id="users-row-template" type="text/x-handlebars-template">
    {{#each users}}
    <tr class="users-row">
        <td>{{id}}</td>
        <td><a href="#" data-type="text" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/user/edit-field') ?>" data-name="login">{{login}}</a></td>
        <td><a href="#" data-type="text" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/user/edit-field') ?>"
               data-name="password">{{password}}</a></td>
    </tr>
    {{/each}}
</script>
<script id="transactions-row-template" type="text/x-handlebars-template">
    {{#each transactions}}
    {{#if in}}
    <tr class="success">
        {{else}}
    <tr class="danger">
        {{/if}}
        <td>{{id}}</td>
        <td>{{amount}}</td>
        <td>{{date}}</td>
        <td>{{action}}</td>
    </tr>
    {{/each}}
</script>
<script id="camera-row-template" type="text/x-handlebars-template">
    {{#each cameras}}
    <tr class="camera-row">
        <td>{{id}}</td>
        <td><a href="#" data-type="text" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/camera/edit-field') ?>" data-name="name">{{name}}</a></td>
        <td><a href="#" data-type="text" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/camera/edit-field') ?>" data-name="ftp_password">{{ftp_password}}</a>
        </td>
        <td>{{totalSize}} Gb. <strong>{{occupiedPercent}}%</strong></td>
        <td><a href="#" data-type="select" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/camera/edit-field') ?>" data-name="enabled"
               data-value="{{enabled}}"></a></td>
    </tr>
    {{/each}}
</script>

<script>
    $.fn.editable.defaults.mode = 'popup';
    var camerasTemplate = Handlebars.compile($("#camera-row-template").html());
    var usersTemplate = Handlebars.compile($("#users-row-template").html());
    var transactionsTemplate = Handlebars.compile($("#transactions-row-template").html());

    var currentClient = null;

    $(document).ready(function () {
        $('#client-login').editable();
        $('#client-password').editable();
        $('#client-tariff').editable({
            source: yii.app.createUrl('admin/tariff/json-get-all')
        }).on('save', function (e, params) {
            var response = JSON.parse(params.response);

            $('.client-row[client-id=' + response.pk + '] td:eq(5)').text(response.value);
        });
        $('#client-active').editable({
            source: [{value: 0, text: 'Нет'}, {value: 1, text: 'Да'}]
        });
        App.init();
    });

    $(document).on('click', '.client-row', function () {
        currentClient = $(this).attr('client-id');


        $.get(yii.app.createUrl('admin/ajax/get-client', {id: currentClient}, '&', 'get')).done(function (clientInfo) {
            clientInfo = JSON.parse(clientInfo);
            if (typeof clientInfo.client !== 'undefined') {
                $('#client-account').text(clientInfo.client.accountNumber);
                $('#client-created').text(clientInfo.client.created);
                $('#client-tariff-expires').text(clientInfo.client.expires);
                $('#client-balance').text(clientInfo.client.balance);
                $('#client-active').editable('setValue', clientInfo.client.active).editable('option', 'pk', clientInfo.client.id);


                $('#client-login').editable('setValue', clientInfo.client.login).editable('option', 'pk', clientInfo.client.id);
                $('#client-password').editable('setValue', clientInfo.client.password).editable('option', 'pk', clientInfo.client.id);
                $('#client-tariff').editable('setValue', clientInfo.client.tariff_id).editable('option', 'pk', clientInfo.client.id);

            }
            if (typeof clientInfo.cameras !== 'undefined') {
                $('#cameras-body').html(camerasTemplate(clientInfo));
                $('.camera-row td a[data-type=text]').editable();
                $('.camera-row td a[data-type=select]').editable({
                    source: [{value: 0, text: 'Неактивна'}, {value: 1, text: 'Активна'}]
                });

            }
            if (typeof clientInfo.users !== 'undefined') {
                $('#users-body').html(usersTemplate(clientInfo));
                $('.users-row td a[data-type=text]').editable();
            }
            if (typeof clientInfo.transactions !== 'undefined') {
                $('#transactions-body').html(transactionsTemplate(clientInfo));

            }
            $('.client-info-container').show();
        });
    });

    $(document).on('click', '#do-add-funds', function () {
        var sum = $('#add-sum').val();
        var info = $('#invoice-details').val();

        $.post(yii.app.createUrl('admin/client/add-funds'), {
            sum: sum, info: info, id: currentClient
        }).done(function (response) {
            response = JSON.parse(response);
            if (typeof response.result !== 'undefined') {
                if (response.result == 'OK') {
                    $('#transactions-body').append(transactionsTemplate(response));
                    $('#add-sum').val('');
                    $('#invoice-details').val('');
                }
            }
        });
    });
</script>