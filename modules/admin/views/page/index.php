<?php
/* @var $this PageController */
/* @var $pages Page[] */

$this->registerJsFile(Yii::$app->homeUrl . "fw/tinymce/js/tinymce/tinymce.min.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);
?>
<div class="col-md-4">
    <div class="panel panel-inverse">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Ссылка</th>
                            <th class="text-center">Шапка</th>
                            <th class="text-center">Подвал</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="pages-table">
                        <?php foreach ($pages as $index => $page): ?>
                            <?php if ($index == 0): ?>
                                <tr class="page-row info" page-id="<?= $page->id ?>">
                            <?php else: ?>
                                <tr class="page-row" page-id="<?= $page->id ?>">
                            <?php endif; ?>
                            <td>
                                <?= $page->title ?>
                            </td>
                            <td>
                                <?= $page->url ?>
                            </td>
                            <td class="check">
                                <input type="checkbox" class="form-control"
                                       disabled="disabled" <?= $page->header ? 'checked="checked"' : '' ?>/>
                            </td>
                            <td class="check">
                                <input type="checkbox" class="form-control"
                                       disabled="disabled" <?= $page->footer ? 'checked="checked"' : '' ?>/>
                            </td>
                            <td class="text-center">
                                <i class="fa fa-close text-danger remove-page trigger" page-id="<?= $page->id ?>"></i>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12 text-center">
                    <button class="btn btn-success" id="new-page"><i class="fa fa-plus"></i> Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="panel panel-inverse">
        <div class="panel-body page-panel">
            <div class="row m-b-10">
                <label class="p-t-5 col-md-2">
                    <span class="pull-right">
                        Заголовок:
                    </span>
                </label>

                <div class="col-md-10">
                    <input type="text" class="form-control" id="title"
                           value="<?= isset($pages[0]) ? $pages[0]->title : '' ?>"/>
                </div>
            </div>
            <div class="row m-b-10">
                <label class="p-t-5 col-md-2">
                    <span class="pull-right">
                        URL:
                    </span>
                </label>

                <div class="col-md-10">
                    <input type="text" class="form-control" id="url"
                           value="<?= isset($pages[0]) ? $pages[0]->url : '' ?>"/>
                </div>
            </div>
            <div class="row m-b-10">
                <label class="p-t-5 col-md-2">
                    <span class="pull-right">
                        Шапка:
                    </span>
                </label>

                <div class="col-md-10">
                    <input type="checkbox" class="form-control"
                           id="header" <?= isset($pages[0]) ? $pages[0]->header ? 'checked="checked"' : '' : '' ?>/>
                </div>
            </div>
            <div class="row m-b-10">
                <label class="p-t-5 col-md-2">
                    <span class="pull-right">
                        Подвал:
                    </span>
                </label>

                <div class="col-md-10">
                    <input type="checkbox" class="form-control"
                           id="footer" <?= isset($pages[0]) ? $pages[0]->footer ? 'checked="checked"' : '' : '' ?>/>
                </div>
            </div>
            <textarea id="page-content"><?= isset($pages[0]) ? $pages[0]->content : '' ?></textarea>

            <div class="row m-t-10">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" id="save-page"><i class="fa fa-save"></i> Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="page-row-template" type="text/x-handlebars-template">
    <tr class="page-row info" page-id="{{id}}">
        <td>
            {{title}}
        </td>
        <td>
            {{url}}
        </td>
        <td class="check">
            <input type="checkbox" class="form-control" disabled="disabled" {{#if hasHeader}}checked="checked" {{/if}}/>
        </td>
        <td class="check">
            <input type="checkbox" class="form-control" disabled="disabled" {{#if hasFooter}}checked="checked" {{/if}}/>
        </td>
        <td class="text-center">
            <i class="fa fa-close text-danger remove-page trigger" page-id="{{id}}"></i>
        </td>
    </tr>
</script>


<script>
    var newPage = <?=isset($pages[0]) ? 'false' : 'true' ?>;
    var currentPageId = <?=isset($pages[0]) ? $pages[0]->id : 'null' ?>;
    var pageTemplate = Handlebars.compile($("#page-row-template").html());

    $(document).ready(function () {
        tinymce.init({
            selector: "textarea",
            plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
            language: "ru"
        });
        App.init();
    });

    $(document).on('click', '.page-row', function () {
        var pageRow = $(this);

        $.get(yii.app.createUrl('admin/page/get'), {
            id: $(pageRow).attr('page-id')
        }, '&', 'get').done(function (response) {
            response = JSON.parse(response);
            if (typeof response.id !== 'undefined') {
                $('#title').val(response.title);
                $('#url').val(response.url);
                $('#header').prop('checked', response.header == 1);
                $('#footer').prop('checked', response.footer == 1);
                tinymce.get('page-content').setContent(response.content);
                $('.pages-table tr').removeClass('info');
                $(pageRow).addClass('info');

                newPage = false;
                currentPageId = response.id;
            }
        });
    });

    $(document).on('click', '#new-page', function () {
        newPage = true;
        currentPageId = null;
        $('#title').val('');
        $('#url').val('');
        tinymce.get('page-content').setContent('');
    });

    $(document).on('click', '.remove-page', function () {
        var pageRow = $(this).parent().parent();
        var pageId = $(this).attr('page-id');

        $.get(yii.app.createUrl('admin/page/remove'), {
            id: pageId
        }, '&', 'get').done(function (response) {
            $(pageRow).remove();
            if ($('.page-row').length > 0)
                $('.page-row:first').trigger('click'); else
                $('#new-page').trigger('click');
        });
    });

    $(document).on('click', '#save-page', function () {
        $.post(yii.app.createUrl('admin/page/save'), {
            content: tinymce.get('page-content').getContent(),
            title: $('#title').val(),
            url: $('#url').val(),
            header: $('#header').prop('checked') ? 1 : 0,
            footer: $('#footer').prop('checked') ? 1 : 0,
            id: currentPageId
        }).done(function (response) {
            response = JSON.parse(response);
            if (typeof response.id !== 'undefined') {
                if (currentPageId == null) {
                    $('.pages-table tr').removeClass('info');
                    $('.pages-table').append(pageTemplate(response));
                } else {
                    $('.page-row[page-id=' + currentPageId + '] td:eq(0)').text(response.title);
                    $('.page-row[page-id=' + currentPageId + '] td:eq(1)').text(response.url);
                    $('.page-row[page-id=' + currentPageId + '] td:eq(2) input').prop('checked', response.header == 1);
                    $('.page-row[page-id=' + currentPageId + '] td:eq(3) input').prop('checked', response.footer == 1);
                }
                currentPageId = response.id;
                newPage = false;
                swal('Сохранено');
            }
        });
    });
</script>