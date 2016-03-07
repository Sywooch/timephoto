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
use app\models\Permission;
use yii\helpers\ArrayHelper;


$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/css/bootstrap-editable.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/js/bootstrap-editable.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/jquery.dataTables.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/js/data-table.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/DataTables-1.10.2/css/data-table.css");
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);
?>

<div class="col-md-6 tariffs-panel-container">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Пользователи
            </div>
        </div>
        <div class="panel-body ">
            <table class="table table-stylized">
                <thead>
                <tr>
                    <th>Счет</th>
                    <th>Линые данные</th>
                    <th>E-mail</th>
                    <th>Активен</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="additional-users">
                <?php foreach ($additionalUsers as $additionalUser): ?>
                    <tr class="additional-user-row additional-user-info" additional-user-id="<?= $additionalUser['id'] ?>">
                        <td>
                            <a href="#" data-type="text" data-name="login" data-pk="<?= $additionalUser['id'] ?>" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                                <?= $additionalUser['login'] ?>
                            </a>
                        </td>
                        <td>
                            <a href="#" data-type="text" data-name="name" data-pk="<?= $additionalUser['id'] ?>" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                                <?= $additionalUser['name'] ? $additionalUser['name'] : 'Empty' ?>
                            </a>
                        </td>
                        <td>
                            <a href="#" data-type="text" data-name="email" data-pk="<?= $additionalUser['id'] ?>" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                                <?= $additionalUser['email'] ? $additionalUser['email'] : 'Empty' ?>
                            </a>
                        </td>
                        <td>
                            <a href="#" data-type="select" data-value="<?= $additionalUser['active'] ?>" data-name="active" data-pk="<?= $additionalUser['id'] ?>" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                                <?= $additionalUser['active'] ? 'Активен' : 'Не активен' ?>
                            </a>
                        </td>
                        <td>
                            <i class="fa fa-close trigger text-danger remove-additional-user" id="remove-additional-user" additional-user-id="<?= $additionalUser['id'] ?>"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                </tr>
            </table>
            <input type="text" class="form-control" id="additional-user-input" placeholder="Введите e-mail нового пользователя"><br>
            <button type="button" class="btn btn-success" id="add-additional-user"><i class="fa fa-plus"></i> Добавить пользователя</button>
            <?php /*<button type="button" class="btn btn-success" id="remove-additional-user"><i class="fa fa-minus"></i> Удалить пользователя</button>*/ ?>
        </div>
    </div>
</div>
<div class="col-md-6 additional-user-info-container" id="additional-user-info-container">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Установки
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 ">
                    <table class="table  transactions-table table-stylized">
                        <thead>
                        <tr>
                            <th>Регистрация</th>
                            <th>Пароль</th>
                            <th>Ограничения</th>
                            <th>Трафик</th>
                        </tr>
                        </thead>
                        <tbody id="settings-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Камеры
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 ">
                    <table class="table  transactions-table table-stylized">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Имя камеры</th>
                            <th>Уровень</th>
                        </tr>
                        </thead>
                        <tbody id="cameras-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="additional-user-row-template" type="text/x-handlebars-template">
    {{#each additional_users}}
    <tr class="additional-user-row" additional-user-id="{{id}}">
        <td>
            <a href="#" data-type="text" data-name="login" data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                {{login}}
            </a>
        </td>
        <td>
            <a href="#" data-type="text" data-name="name" data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                {{name}}
            </a>
        </td>
        <td>
            <a href="#" data-type="text" data-name="email" data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                {{email}}
            </a>
        </td>
        <td>
            <a href="#" data-type="select" data-name="active" data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                {{active}}
            </a>
        </td>
        <td><i class="fa fa-close trigger text-danger delete-additional-user" additional-user-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>

<script id="additional-user-cameras-template" type="text/x-handlebars-template">
    {{#each cameras}}
    <tr>
        <td>{{id}}</td>
        <td>{{name}}</td>
        <td class="{{permissions_class}}">
            <a href="#" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-set-additional-user-permession') ?>"
               data-camera-id="{{id}}" data-title="Укажите права" class="permisions editable editable-click"
               title="Укажите права" data-value="{{permissions_id}}">
                {{permissions}}
            </a>
        </td>
    </tr>
    {{/each}}
</script>

<script id="additional-user-settings-template" type="text/x-handlebars-template">
    <tr>
        <td>{{created}}</td>
        <td>
            <a href="#" data-name="password" class="password-edit editable editable-click" data-pk="{{id}}"
               data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
            </a>
        </td>
        <td>
            <a href="#" data-name="traffic_limit_after_block" class="limit-edit editable editable-click" data-pk="{{id}}"
                           data-pk="{{id}}" data-url="<?= $this->context->createUrl('/cabinet/user/ajax-edit-additional-user') ?>">
                {{limit}}
            </a>
        </td>
        <td>{{traffic}}</td>
    </tr>
</script>



<?php
$permissions_classes = Permission::find()->select(['id', 'machine_name'])->asArray()->all();
foreach($permissions_classes as &$v){
    switch($v['machine_name']){
        case 'access_view': $v['class'] = 'success'; break;
        case 'access_copy': $v['class'] = 'info'; break;
        case 'access_delet': $v['class'] = 'danger'; break;
    }
}
$permissions_classes = json_encode(ArrayHelper::map($permissions_classes, 'id', 'class'));


$permissions_collection = Permission::find()->select(['id as value', 'title as text'])->asArray()->all();
$permissions_json = json_encode($permissions_collection);
$js = <<<JS
    var permissions_json = $permissions_json;
    var permissions_classes = $permissions_classes;
JS;

Yii::$app->view->registerJs($js,\yii\web\View::POS_END);
?>


<script>
    var addtionalUsersTemplate = Handlebars.compile($("#additional-user-row-template").html());
    var camerasTemplate = Handlebars.compile($("#additional-user-cameras-template").html());
    var userAdditionalSettingsTemplate = Handlebars.compile($("#additional-user-settings-template").html());
    $.fn.editable.defaults.mode = 'popup';

    $(document).ready(function () {

        $(document).on('click', '#add-additional-user', function () {
            addAditionalUser();
        });

        $(document).on('click', '#remove-additional-user', function () {
            removeAdditionalUser(this);
        });

        $(document).on('click', '.additional-user-info', function () {
            infoAdditionalUser(this);
        });

        editableInit();

        App.init();
    });

    function editableInit() {

        $('a[data-type=text]').editable({emptytext: 'Не указано'});

        $('.limit-edit').editable({type: 'text', emptytext: '0'});

        $('a[data-name=active]').editable({
            source: [{value: 0, text: 'Не активен'}, {value: 1, text: 'Активен'}]
        });

        $('.password-edit').editable({
            type: 'text',
            emptytext: 'Изменить пароль',
        });


        $('.permisions').editable({
            params: function(params) {
                var data = {};
                data['permission_id'] = params.value;
                data['camera_id'] = $(this).data('camera-id');
                data['additional_user_id'] = $('#additional-user-info-container').attr('additional-user-id');
                return data;
            },
            success: function(response, newValue) {
                $(this).closest('td').removeAttr('class').addClass(permissions_classes[newValue]);
            },
            type: 'select',
            send: 'always',
            emptytext: 'Нет доступа',
            source: permissions_json
        });


    }

    function addAditionalUser() {
        var newUserName = $('#additional-user-input').val();

        if (newUserName !== '') {
            $.post(yii.app.createUrl('cabinet/user/ajax-add-additional-user'), {
                additionalUser: newUserName
            }).done(function (response) {

                response = JSON.parse(response);

                $("#additional-users").append(addtionalUsersTemplate(response));
                editableInit();
                $('#additional-user-input').val('');
            });
        }
    }

    function removeAdditionalUser(self) {
        var additionalUserId = $(self).attr('additional-user-id');
        var additionalUserContainer = $(self).parent().parent();
        $.post(yii.app.createUrl('cabinet/user/ajax-remove-additional-user'), {
            pk: additionalUserId
        }).done(function (response) {

            response = JSON.parse(response);
            if (response.result == 'OK') {
                $(additionalUserContainer).remove();
            }

        });
    }

    function infoAdditionalUser(self) {
        var additionalUserId = $(self).closest('tr').attr('additional-user-id');
        $.post(yii.app.createUrl('cabinet/user/ajax-get-additional-user-info'), {
            pk: additionalUserId
        }).done(function (additionalUserInfo) {

              additionalUserInfo = JSON.parse(additionalUserInfo);

              var clientInfo = additionalUserInfo.clientInfo;

              if (typeof clientInfo.cameras !== 'undefined') {
                  $('#cameras-body').html(camerasTemplate(clientInfo));
                  /*$('.camera-row td a[data-type=text]').editable();
                  $('.camera-row td a[data-type=select]').editable({
                      source: [{value: 0, text: 'Неактивна'}, {value: 1, text: 'Активна'}]
                  });*/
              }

              if (typeof clientInfo.settings !== 'undefined') {
                  $('#settings-body').html(userAdditionalSettingsTemplate(clientInfo.settings));
              }

              $('.additional-user-info-container').show().attr('additional-user-id',additionalUserId);

            editableInit();

          });


    }

</script>