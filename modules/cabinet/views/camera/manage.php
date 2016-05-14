<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 21.02.15
 * Time: 5:27
 */
/* @var $cameras Camera[] */

$i = 1;

$this->registerJsFile(Yii::$app->homeUrl . "js/slashman-glass.js", ['position' => yii\web\View::POS_HEAD]);

foreach ($cameras as $k => $camera) {
    if(!$camera->countImages()){
        unset($cameras[$k]);
    }
}
$cameras = array_values($cameras)

?>
<div class="page-default-wrap">
    <?php /*
  <div class="col-xs-12">
    <div class="page-header col-lg-6 col-md-6 col-sm-12 col-xs-12">
      Управление фотоархивом камер
    </div>
    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 well hints m-b-20 p-r-0 pull-right text-center">
      <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 text-center p-r-0">
        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Удаление каждой N-й фотографии за выбранную дату.
Будьте внимательны, после удаления, востановить невозможно"></i> Удаление
      </div>
      <div class="col-lg-4 col-md-7 col-sm-6 col-xs-12 text-center p-r-0">
        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Копирование каждой N фотографии за выбранную дату
на диск вашего устройства"></i> Копирование
      </div>
    </div>
  </div>
 */ ?>

    <?php if(!empty($cameras)) :?>

    <div class="col-md-12 " style="overflow-y: scroll" data-scrollbar="true" data-height="calc(100vh - 150px)">
        <?php foreach ($cameras as $position => $camera): ?>

        <?php if ($position == 0): ?>
        <div class="col-md-12"><h4><?= $camera['location']['name'] ?></h4></div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered manage-images-table">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Объем места</th>
                    <th>Интервал</th>
                    <th>Количество</th>
                    <th></th>
                    <?php if (Yii::$app->user->identity->canCopy()): ?>
                        <th></th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php elseif ($camera->location_id != $cameras[$position - 1]->location_id): ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-12"><h4><?= $camera['location']['name'] ?></h4></div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered manage-images-table">
                <tbody>
                <?php endif; ?>
                <tr camera-id="<?= $camera->id; ?>">
                    <td class="text-center text-middle name-column"><?= $camera->getName() ?></td>
                    <td class="text-center text-middle size-column">
                        <div class="progress progress-sm active">
                            <div
                                class="progress-bar progress-bar-<?= $camera->getOccupiedPercent() > 80 ? 'warning' : 'success' ?>"
                                style="width: <?= $camera->getOccupiedPercent() ?>%"></div>
                        </div>
                        <?= $camera->getTotalSize() ?> Gb. <strong>(<?= $camera->getOccupiedPercent() ?>%)</strong>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-lg-12 form-horizontal">
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">С</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control datetime from-time">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">По</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control datetime to-time">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class=" text-middle">
                        <select class="form-control count js-change-btn-type">
                            <option value="1">Все</option>
                            <?php if (Yii::$app->user->identity->getTariffId() !== 1): ?>
                                <?php for ($i = 2; $i <= min(10, $camera->countImages()); $i++): ?>
                                    <option value="<?= $i ?>">Оставить каждую <?= $i ?>-ю</option>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td class="text-center text-middle">
                        <a href="#" class="btn btn-danger delete-images"
                           data-toggle="tooltip" data-placement="bottom"
                           title="Удаление каждой N-й фотографии за выбранную дату. Будьте внимательны, после удаления, востановить невозможно"
                        >Удалить за период</a>
                    </td>
                    <?php if (Yii::$app->user->identity->canCopy()): ?>
                        <td class="text-center text-middle">
                            <a href="" class="btn btn-primary download-images"
                               data-toggle="tooltip" data-placement="bottom"
                               title="Копирование каждой N фотографии за выбранную дату на диск вашего устройства"
                            >Скопировать</a>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>

        <div class="alert-container">
            <div class="alert alert-warning" role="alert">Изображения отсутствуют...</div>
        </div>

    <?php endif;?>

    <div class="clearfix"></div>
</div>


<script>
    $(document).ready(function () {
        $(".datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    });

    $(document).on('click', '.delete-images', function () {
        var tr = $(this).closest('tr');

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
            $.post(yii.app.createUrl('cabinet/ajax/delete-images'), {
                id: $(tr).attr('camera-id'),
                from: $(tr).find('.from-time:first').val(),
                to: $(tr).find('.to-time:first').val(),
                count: $(tr).find('.count option:selected').val()
            }).done(function (response) {
                if (response !== '0') {
                    swal("Удаление", "Запрос на удаление фото отправлен", "success");
                } else {
                    sweetAlert("Не найдено", "Фотографий за указанный период не найдено", "error");
                }
            });
        });
    });

    $(document).on('change', '.from-time,.to-time', function () {
        var tr = $(this).closest('tr');
        updateRow(tr);
    });

    $(document).on('change', '.js-change-btn-type', function () {
        var tr = $(this).closest('tr');
        var val = $(this).val();
        if (val == 1) {
            tr.find('.delete-images').removeClass('btn-warning').addClass('btn-danger').html('Удалить в дианазоне');
        } else {
            tr.find('.delete-images').removeClass('btn-danger').addClass('btn-warning').html('Прореживать');
        }
    });
    $(document).on('change', '.count', function () {
        var tr = $(this).closest('tr');
        updateRow(tr);
    });

    $(document).ready(function () {
        $('tr').each(function (index, tr) {
            updateRow(tr);
        });
        App.init();
    });

    function updateRow(row) {
        var link = $(row).find('a.download-images');
        if ($(row).find('.from-time').val() != '' && $(row).find('.to-time').val() != '') {
            $(link).attr('href', yii.app.createUrl('cabinet/download/download-zip', {
                id: $(row).attr('camera-id'),
                from: $(row).find('.from-time').val(),
                to: $(row).find('.to-time').val(),
                count: $(row).find('.count option:selected').val()
            }));

            $('.download-images, .delete-images').removeAttr('disabled');
        } else {
            $(link).attr('href', '#');
            $('.download-images, .delete-images').attr('disabled', 'disabled');
        }
    }
</script>