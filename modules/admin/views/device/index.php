<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 19.03.15
 * Time: 2:29
 */

/* @var $this DeviceController */
/* @var $deviceCategories DeviceCategory[] */
$this->registerCssFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/css/bootstrap-editable.css");
$this->registerJsFile(Yii::$app->homeUrl . "template/plugins/bootstrap3-editable/js/bootstrap-editable.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);
?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-title">
                    Раздел
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Активна</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="device-categories">
                            <?php if (count($deviceCategories) > 0): ?>
                                <?php foreach ($deviceCategories as $deviceCategory): ?>
                                    <tr class="device-category-row" device-category-id="<?= $deviceCategory->id ?>">
                                        <td>
                                            <a href="#" data-type="text" data-name="name"
                                               data-pk="<?= $deviceCategory->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-category') ?>">
                                                <?= $deviceCategory->name ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" data-type="select" data-name="active"
                                               data-pk="<?= $deviceCategory->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-category') ?>"
                                               data-value="<?= $deviceCategory->active ?>"></a>
                                        </td>
                                        <td><i class="fa fa-close trigger text-danger delete-device-category"
                                               device-category-id="<?= $deviceCategory->id ?>"></i></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="device-category-input">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success" id="add-device-category"><i
                                class="fa fa-plus"></i> Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-title">
                    Оборудование
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 table-responsive devices-table">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>На главной</th>
                                <th>Активно</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="devices">
                            <?php if (isset($deviceCategories[0])): ?>
                                <?php foreach ($deviceCategories[0]->devices as $device): ?>
                                    <tr class="device-row" device-id="<?= $device->id ?>">
                                        <td>
                                            <a href="#" data-type="text" data-name="name" data-pk="<?= $device->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>">
                                                <?= $device->name ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" data-type="text" data-name="price" data-pk="<?= $device->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>">
                                                <?= $device->price ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" data-type="select" data-name="main_page"
                                               data-pk="<?= $device->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>"
                                               data-value="<?= $device->main_page ?>"></a>
                                        </td>
                                        <td>
                                            <a href="#" data-type="select" data-name="active"
                                               data-pk="<?= $device->id ?>"
                                               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>"
                                               data-value="<?= $device->active ?>"></a>
                                        </td>
                                        <td><i class="fa fa-close trigger text-danger delete-device"
                                               device-id="<?= $device->id ?>"></i></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="device-input">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success" id="add-device"><i class="fa fa-plus"></i>
                            Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Характеристики
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Текст</th>
                            <th>Иконка</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="features">
                        <?php if (isset($deviceCategories[0]) && isset($deviceCategories[0]->devices[0])): ?>
                            <?php foreach ($deviceCategories[0]->devices[0]->deviceFeatures as $feature): ?>
                                <tr class="feature-row" feature-id="<?= $feature->id ?>">
                                    <td>
                                        <a href="#" data-type="text" data-name="name" data-pk="<?= $feature->id ?>"
                                           data-url="<?= $this->context->createUrl('/admin/device/edit-feature') ?>">
                                            <?= $feature->name ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" data-type="text" data-name="icon" data-pk="<?= $feature->id ?>"
                                           data-url="<?= $this->context->createUrl('/admin/device/edit-feature') ?>">
                                            <?= $feature->icon ?>
                                        </a>
                                    </td>
                                    <td><i class="fa fa-close trigger text-danger delete-feature"
                                           feature-id="<?= $feature->id ?>"></i></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <input type="text" class="form-control" id="feature-input">
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-success" id="add-feature"><i class="fa fa-plus"></i> Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Фото камеры
            </div>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Имя файла</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="photos">
                        <?php if (isset($deviceCategories[0]) && isset($deviceCategories[0]->devices[0])): ?>
                            <?php foreach ($deviceCategories[0]->devices[0]->devicePhotos as $photo): ?>
                                <tr class="photo-row" photo-id="<?= $photo->id ?>">
                                    <td><?= $photo->original_file_name ?></td>
                                    <td><i class="fa fa-close trigger text-danger delete-photo"
                                           photo-id="<?= $photo->id ?>"></i></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="POST" enctype="multipart/form-data" id="photo-form">
                        <input type="text" class="hidden" name="deviceId"
                               value="<?= isset($deviceCategories[0]->devices[0]) ? $deviceCategories[0]->devices[0]->id : '' ?>">
                        <input type="file" class="form-control" name="DevicePhoto[photo]" id="photo-input">
                    </form>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-success" id="add-photo"><i class="fa fa-plus"></i> Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Фото кейсов
            </div>
        </div>
        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Имя файла</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="cases">
                        <?php if (isset($deviceCategories[0]) && isset($deviceCategories[0]->devices[0])): ?>
                            <?php foreach ($deviceCategories[0]->devices[0]->deviceCases as $case): ?>
                                <tr class="case-row" case-id="<?= $case->id ?>">
                                    <td><?= $case->original_file_name ?></td>
                                    <td><i class="fa fa-close trigger text-danger delete-case"
                                           case-id="<?= $case->id ?>"></i></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="POST" enctype="multipart/form-data" id="case-form">
                        <input type="text" class="hidden" name="deviceId"
                               value="<?= isset($deviceCategories[0]->devices[0]) ? $deviceCategories[0]->devices[0]->id : '' ?>">
                        <input type="file" class="form-control" name="DeviceCase[case]" id="case-input">
                    </form>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-success" id="add-case"><i class="fa fa-plus"></i> Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-title">
                Описание
            </div>
        </div>
        <div class="panel-body table-responsive">
            <textarea class="form-control"
                      id="description-textarea"><?= isset($deviceCategories[0]->devices[0]) ? $deviceCategories[0]->devices[0]->description : '' ?></textarea>

            <div class="col-md-12 text-center m-t-4">
                <button type="button" class="btn btn-success" id="save-description">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<script id="device-row-template" type="text/x-handlebars-template">
    {{#each devices}}
    <tr class="device-row" device-id="{{id}}">
        <td>
            <a href="#" data-type="text" data-name="name" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>">
                {{name}}
            </a>
        </td>
        <td>
            <a href="#" data-type="text" data-name="price" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>">
                {{price}}
            </a>
        </td>
        <td>
            <a href="#" data-type="select" data-name="main_page" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>" data-value="{{main_page}}">
            </a>
        </td>
        <td>
            <a href="#" data-type="select" data-name="active" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-device') ?>" data-value="{{active}}">
            </a>
        </td>
        <td><i class="fa fa-close trigger text-danger delete-device" device-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>
<script id="device-category-row-template" type="text/x-handlebars-template">
    {{#each deviceCategories}}
    <tr class="device-category-row" device-category-id="{{id}}">
        <td>
            <a href="#" data-type="text" data-name="name" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-category') ?>">
                {{name}}
            </a>
        </td>
        <td>
            <a href="#" data-type="select" data-name="active" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-category') ?>" data-value="{{active}}"></a>
        </td>
        <td><i class="fa fa-close trigger text-danger delete-device-category" device-category-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>
<script id="feature-row-template" type="text/x-handlebars-template">
    {{#each device.features}}
    <tr class="feature-row" feature-id="{{id}}">
        <td>
            <a href="#" data-type="text" data-name="name" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-feature') ?>">
                {{name}}
            </a>
        </td>
        <td>
            <a href="#" data-type="text" data-name="icon" data-pk="{{id}}"
               data-url="<?= $this->context->createUrl('/admin/device/edit-feature') ?>">
                {{icon}}
            </a>
        </td>
        <td><i class="fa fa-close trigger text-danger delete-feature" feature-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>
<script id="photo-template" type="text/x-handlebars-template">
    {{#each device.photos}}
    <tr class="photo-row" photo-id="{{id}}">
        <td>{{original_file_name}}</td>
        <td><i class="fa fa-close trigger text-danger delete-photo" photo-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>
<script id="case-template" type="text/x-handlebars-template">
    {{#each device.cases}}
    <tr class="photo-row" case-id="{{id}}">
        <td>{{original_file_name}}</td>
        <td><i class="fa fa-close trigger text-danger delete-case" case-id="{{id}}"></i></td>
    </tr>
    {{/each}}
</script>

<script>
    $.fn.editable.defaults.mode = 'popup';
    var deviceCategoryTemplate = Handlebars.compile($("#device-category-row-template").html());
    var deviceTemplate = Handlebars.compile($("#device-row-template").html());
    var featureTemplate = Handlebars.compile($("#feature-row-template").html());
    var photoTemplate = Handlebars.compile($("#photo-template").html());
    var caseTemplate = Handlebars.compile($("#case-template").html());

    $(document).ready(function () {
        editableInit();
        App.init();
    });

    function editableInit() {
        $('a[data-type=text]').editable();
        $('a[data-type=select]').editable({
            source: [{value: 0, text: 'Нет'}, {value: 1, text: 'Да'}]
        });
    }

    $(document).on('click', '.device-category-row,.device-row,.feature-row,.photo-row,.case-row', function () {
        $(this).parent().find('tr').removeClass('info');
        $(this).addClass('info');
    });

    $(document).on('click', '.device-category-row', function () {
        categoryChanged();
    });
    $(document).on('click', '.device-row', function () {
        deviceChanged();
    });
    $(document).on('click', '#add-device-category', function () {
        addDeviceCategory();
    });
    $(document).on('click', '#add-device', function () {
        addDevice();
    });
    $(document).on('click', '#add-feature', function () {
        addFeature();
    });
    $(document).on('click', '#add-photo', function () {
        addPhoto();
    });
    $(document).on('click', '#add-case', function () {
        addCase();
    });
    $(document).on('click', '#save-description', function () {
        saveDescription();
    });

    function resetDevice() {
        if ($('.device-row').length > 0) {
            $('.device-row:first').trigger('click');
        } else {
            $('#features, #photos, #cases').html('');
        }
    }
    function resetDeviceCategory() {
        if ($('.device-category-row').length > 0) {
            $('.device-category-row:first').trigger('click');
        } else {
            $('#devices, #features, #photos, #cases').html('');
        }
    }

    $(document).on('click', '.delete-device-category', function () {
        var categoryContainer = $(this).parent().parent();
        var categoryId = $(this).attr('device-category-id');
        $.get(yii.app.createUrl('admin/device/delete-device-category'), {
            id: categoryId
        }, '&', 'get').done(function (response) {
            if (response == 'OK') {
                $(categoryContainer).remove();
                resetDeviceCategory();
            }
        });
    });
    $(document).on('click', '.delete-device', function () {
        var deviceContainer = $(this).parent().parent();
        var deviceId = $(this).attr('device-id');
        $.get(yii.app.createUrl('admin/device/delete-device'), {
            id: deviceId
        }, '&', 'get').done(function (response) {
            if (response == 'OK') {
                $(deviceContainer).remove();
                resetDevice();
            }
        });
    });
    $(document).on('click', '.delete-feature', function () {
        var featureContainer = $(this).parent().parent();
        var featureId = $(this).attr('feature-id');
        $.get(yii.app.createUrl('admin/device/delete-feature'), {
            id: featureId
        }, '&', 'get').done(function (response) {
            if (response == 'OK') {
                $(featureContainer).remove();
            }
        });
    });
    $(document).on('click', '.delete-photo', function () {
        var photoContainer = $(this).parent().parent();
        var photoId = $(this).attr('photo-id');
        $.get(yii.app.createUrl('admin/device/delete-photo'), {
            id: photoId
        }, '&', 'get').done(function (response) {
            if (response == 'OK') {
                $(photoContainer).remove();
            }
        });
    });
    $(document).on('click', '.delete-case', function () {
        var caseContainer = $(this).parent().parent();
        var caseId = $(this).attr('case-id');
        $.get(yii.app.createUrl('admin/device/delete-case'), {
            id: caseId
        }, '&', 'get').done(function (response) {
            if (response == 'OK') {
                $(caseContainer).remove();
            }
        });
    });

    function categoryChanged() {
        var activeDeviceCategory = $('.device-category-row.info:first');
        if (activeDeviceCategory.length > 0) {
            $.get(yii.app.createUrl('admin/device/ajax-get-category'), {
                id: $(activeDeviceCategory).attr('device-category-id')
            }, '&', 'get').done(function (response) {
                response = JSON.parse(response);
                $('#devices').html(deviceTemplate(response));
                $('#features').html(featureTemplate(response));
                $('#photos').html(photoTemplate(response));
                $('#cases').html(caseTemplate(response));

                editableInit();

                $('#description-textarea').val(response.device.description);
            });
        }
    }

    function deviceChanged() {
        var activeDevice = $('.device-row.info:first');
        var activeDeviceCategory = $('.device-category-row.info:first');

        if (activeDeviceCategory.length > 0 && activeDevice.length > 0) {
            $.get(yii.app.createUrl('admin/device/ajax-get-category'), {
                id: getCurrentDeviceCategoryId(), device_index: $(activeDevice).index()
            }, '&', 'get').done(function (response) {
                response = JSON.parse(response);
                $('#features').html(featureTemplate(response));
                $('#photos').html(photoTemplate(response));
                $('#cases').html(caseTemplate(response));

                editableInit();

                $('#description-textarea').val(response.device.description);
            });
        }
    }

    function addDeviceCategory() {
        var newCategoryName = $('#device-category-input').val();

        if (newCategoryName !== '') {
            $.post(yii.app.createUrl('admin/device/ajax-add-device-category'), {
                name: newCategoryName
            }).done(function (response) {
                response = JSON.parse(response);

                $("#device-categories").append(deviceCategoryTemplate(response));

                editableInit();

                $('#device-category-input').val('');
            });
        }
    }

    function addDevice() {
        var newDeviceName = $('#device-input').val();

        if (newDeviceName !== '') {
            $.post(yii.app.createUrl('admin/device/ajax-add-device'), {
                name: newDeviceName, categoryId: getCurrentDeviceCategoryId()
            }).done(function (response) {
                response = JSON.parse(response);

                $("#devices").append(deviceTemplate(response));

                editableInit();

                $('#device-input').val('');
            });
        }
    }

    function addFeature() {
        var newFeatureName = $('#feature-input').val();

        if (newFeatureName !== '') {
            $.post(yii.app.createUrl('admin/device/ajax-add-feature'), {
                name: newFeatureName, deviceId: getCurrentDeviceId()
            }).done(function (response) {
                response = JSON.parse(response);

                $("#features").append(featureTemplate(response));

                editableInit();

                $('#feature-input').val('');
            });
        }
    }

    function setDeviceId() {
        $('input[name=deviceId]').val(getCurrentDeviceId());
    }

    function addPhoto() {
        setDeviceId();
        var form = new FormData($('#photo-form')[0]);

        var request = $.ajax({
            url: yii.app.createUrl('admin/device/ajax-add-photo'),
            type: "POST",
            processData: false,
            cache: false,
            contentType: false,
            data: form
        });
        request.done(function (response) {
            response = JSON.parse(response);

            $('#photos').append(photoTemplate(response));
        });
    }

    function addCase() {
        setDeviceId();
        var form = new FormData($('#case-form')[0]);

        var request = $.ajax({
            url: yii.app.createUrl('admin/device/ajax-add-case'),
            type: "POST",
            processData: false,
            cache: false,
            contentType: false,
            data: form
        });
        request.done(function (response) {
            response = JSON.parse(response);

            $('#cases').append(caseTemplate(response));
        });
    }

    function saveDescription() {
        $.post(yii.app.createUrl('admin/device/ajax-save-description'), {
            description: $('#description-textarea').val(), deviceId: getCurrentDeviceId()
        }).done(function (response) {
        });
    }

    function getCurrentDeviceCategoryId() {
        var categoriesCount = $('.device-category-row').length;
        var activeDeviceCategory = $('.device-category-row.info:first');

        if (categoriesCount > 0) {
            if (activeDeviceCategory.length > 0) {
                return $(activeDeviceCategory).attr('device-category-id');
            } else
                return $('.device-category-row:first').attr('device-category-id');
        } else
            return null;
    }
    function getCurrentDeviceId() {
        var devicesCount = $('.device-row').length;
        var activeDevice = $('.device-row.info:first');

        if (devicesCount > 0) {
            if (activeDevice.length > 0) {
                return $(activeDevice).attr('device-id');
            } else
                return $('.device-row:first').attr('device-id');
        } else
            return null;
    }
</script>