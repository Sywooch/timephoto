<?php
/* @var $filterTypes String[] */
/* @var $type String */
/* @var $date String */
/* @var $id Integer */
/* @var $imageId Integer */

$this->registerCssFile(Yii::$app->homeUrl . "fw/bootstrap-multiselect/css/bootstrap-multiselect.css");
$this->registerJsFile(Yii::$app->homeUrl . "fw/bootstrap-multiselect/js/bootstrap-multiselect.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

?>
<nav class="navbar navbar-default navbar-bottom">
    <div class="container-fluid">
        <div class="">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav footer-navbar footer-navbar-one-camera">
                    <li class="divider-vertical"></li>
                    <li class="p-l-5 p-r-5 p-t-1 ">
                        <button href="#" id="calendar-popover" class="btn btn-default"><i class="fa fa-calendar"></i>
                            Фильтр по дате
                        </button>
                    </li>
                    <li class="divider-vertical"></li>
                    <li class="p-l-5 p-r-5 p-t-1">
                        <div class="dropup type-filter-dropup ">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                                    data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-check-square-o"></i>
                                Фильтр по событию
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <?php if (in_array('ALL', $filterTypes)): ?>
                                    <li role="presentation">
                                        <a tabindex="0"><label class="checkbox"><input type="checkbox" value="all"
                                                                                       id="all-option" <?= in_array('all', explode(',', $type)) ? 'checked="checked"' : '' ?>>
                                                Все</label></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('ALERT', $filterTypes)): ?>
                                    <li role="presentation">
                                        <a tabindex="0"><label class="checkbox"><input type="checkbox"
                                                                                       value="alert" <?= in_array('alert', explode(',', $type)) || in_array('all', explode(',', $type)) ? 'checked="checked"' : '' ?>>
                                                По тревоге</label></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('MOVE', $filterTypes)): ?>
                                    <li role="presentation">
                                        <a tabindex="0"><label class="checkbox"><input type="checkbox"
                                                                                       value="move" <?= in_array('move', explode(',', $type)) || in_array('all', explode(',', $type)) ? 'checked="checked"' : '' ?>>
                                                По
                                                движению</label></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('SCHEDULE', $filterTypes)): ?>
                                    <li role="presentation">
                                        <a tabindex="0"><label class="checkbox"><input type="checkbox"
                                                                                       value="schedule" <?= in_array('schedule', explode(',', $type)) || in_array('all', explode(',', $type)) ? 'checked="checked"' : '' ?>>
                                                По расписанию</label></a>
                                    </li>
                                <?php endif; ?>
                                <li role="presentation" class="do-filter text-center">
                                    <a tabindex="0" href="#" id="type-filter">Фильтровать</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="divider-vertical"></li>
                    <?php if (Yii::$app->user->identity->checkPermission('access_copy')): ?>
                        <li class="p-l-5 p-r-5 p-t-1">
                            <button href="#" id="download-selected" class="btn btn-default"><i
                                    class="fa fa-download"></i> Скачать выделенное
                            </button>
                        </li>
                    <?php endif; ?>
                    <li class="divider-vertical"></li>
                    <?php if (Yii::$app->user->identity->checkPermission('access_delet')): ?>
                        <li class="p-l-5 p-r-5 p-t-1">
                            <button href="#" id="delete-selected" class="btn btn-default"><i
                                    class="fa fa-times-circle-o"></i> Удалить выделенное
                            </button>
                        </li>
                    <?php endif; ?>
                    <li class="divider-vertical"></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>

<script>

    var calendarDates = '';

    //CUSTOM CODE
    $(document).ready(function () {
        updateDownloadLink();
        images = getSelectedThumbnailsString();

        $('#calendar-popover').datepicker({
            multidate: true,
            language: 'ru',
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (target) {
            calendarDates = '';
            var first = true;
            $(target.dates).each(function (index, selectedDate) {
                if (!first)
                    calendarDates = calendarDates + ',';
                calendarDates = calendarDates + selectedDate.getFullYear() + '-' + ('0' + (selectedDate.getMonth() + 1)).slice(-2) + '-' + ('0' + selectedDate.getDate()).slice(-2);

                first = false;
            });
        });
        $('.type-filter-dropup .dropdown-menu input,.type-filter-dropup .dropdown-menu label').click(function (e) {
            e.stopPropagation();
        });
    });

    $(document).on('click', '#calendar-popover', function (e) {
        e.preventDefault();

        if (date == '')
            $('#clearDate').hide();
    });
    $(document).on('click', '#clearDate', function (e) {
        e.preventDefault();
        window.location.replace(yii.app.createUrl('cabinet/camera', {id: cameraId, view: 'one', type: type}));
    });
    $(document).on('click', '#dateDoFilter', function (e) {
        e.preventDefault();
        window.location.replace(yii.app.createUrl('cabinet/camera', {
            id: cameraId,
            view: 'one',
            type: type,
            date: calendarDates
        }));
    });

    $(document).on('click', '.thumb-check', function () {
        updateDownloadLink();
    });

    $(document).on('click', '#delete-selected', function (e) {
        e.preventDefault();


        $.post(yii.app.createUrl('cabinet/remove/batch-remove'), {
            Images: getSelectedThumbnailsString()
        }).done(function (response) {
            removeSelectedThumbnails();
        });

    });

    $(document).on('click', '#type-filter', function () {
        var type = '';
        var first = true;
        if (!$('#all-option').prop('checked')) {
            $('.type-filter-dropup input:checked').each(function (index, option) {
                type = first ? $(option).val() : type + ',' + $(option).val();
                first = false;
            });
            window.location.replace(yii.app.createUrl('cabinet/camera', {id: cameraId, view: 'one', type: type}));
        } else
            window.location.replace(yii.app.createUrl('cabinet/camera', {id: cameraId, view: 'one'}));
    });

    $(document).on('change', '.type-filter-dropup input[type=checkbox]', function () {
        if ($(this).attr('id') == 'all-option') {
            if ($('#all-option').prop('checked')) {
                $('.type-filter-dropup input[type=checkbox]').each(function (index, option) {
                    $(option).prop('checked', true);
                });
            }
        } else {
            $('#all-option').prop('checked', false);
        }
    });

    function getSelectedThumbnailsString() {
        var first = true;
        images = '';
        currentSelected = false;

        $('.thumb-check:checked').each(function (index, checkbox) {
            if (!first)
                images = images + ',';
            images = images + $(checkbox).attr('image-id');

            if ($(checkbox).hasClass('current-thumb-check')) //Если отмечена превьюшка текущей фотки
                currentSelected = true;

            first = false;
        });
        return images;
    }
    function updateDownloadLink() {
        var downloadBaseUrl = yii.app.createUrl('cabinet/download/download-zip', {images: images}, '&', 'get');
        $('#download-selected').attr('href', downloadBaseUrl + getSelectedThumbnailsString());
    }
    $('#download-selected').on('click', function () {
        var link = 'http://' + window.location.host + $(this).attr('href');
        window.open(link, '_blank');
    });
    function removeSelectedThumbnails() {
        $('.thumb-check:checked').each(function (index, checkbox) {
            $(checkbox).parents('.thumbnail-container').remove();
        });
    }


    //AJAX HANDLING


</script>