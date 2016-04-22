<?php
/* @var $this CameraController */

// Handlebars is largely compatible with Mustache templates.
$this->registerJsFile(Yii::$app->homeUrl . "fw/handlebars.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

?>
<?php $this->beginContent('@public_cabinet/views/layouts/main.php'); ?>
    <!-- end sidebar scrollbar -->
    <div id="content" class="content">
        <div class="loading hidden m-l-10"><i class="fa fa-spinner fa-spin"></i> Загрузка</div>
        <?php echo $content; ?>
        <?php if (!empty($this->context->footer)): ?>
            <footer class="camera-footer">
                <?= $this->context->footer ?>
            </footer>
        <?php endif; ?>
    </div>

    <script id="camera-template" type="text/x-handlebars-template">
        <div class="col-md-3 camera-thumb" camera-id="{{id}}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <i class="fa fa-camera pull-left p-t-3"></i> {{name}}{{#if enabled}}{{else}} <span
                            class="text-danger">[Камера отключена]</span>{{/if}}
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="image">
                        <a href="{{href}}">
                            <img src="{{thumb}}" class="img-responsive"/>
                        </a>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="row">
                                    {{last_image_date}}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row text-right">
                                    {{totalSize}} Гб. ({{occupiedPercent}}%)
                                </div>
                                <div class="row text-right">
                                    {{quantity}} кадров/сутки
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php if (Yii::$app->user->identity->role == 'USER'): ?>
                                    <div class="row text-right camera-actions">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-align-justify"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li>
                                                    <a href="{{href}}">
                                                        <i class="fa fa-folder-open"></i> Просмотреть архив
                                                    </a>
                                                </li>
                                                {{#if canEdit}}
                                                <li><a href="{{manage_href}}"><i class="fa fa-refresh"></i> Работа с
                                                        архивом</a></li>
                                                {{/if}}
                                                <li>
                                                    <a href="{{edit_href}}">
                                                        <i class="fa fa-cog"></i> Настройки камеры
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="toggle-camera {{#if enabled}}active{{/if}}"
                                                       camera-id="{{id}}">
                                                    <span class="{{#if enabled}}text-success{{else}}text-danger{{/if}}">
                                                        <i class="fa fa-power-off"></i> <span class="text">{{#if enabled}}Выключить камеру{{else}}Включить камеру{{/if}}</span>
                                                    </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        var jsonCameras = JSON.parse('<?=$this->context->jsonCameras?>');
        var activeCamera = '<?=$this->context->activeCamera?>';
        var groupMode = 'locations';
        var cameraTemplate = Handlebars.compile($('#camera-template').html());
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).on('click', '#cameras-list', function () {
            $('#cameras-list').addClass('active');
            $('#cameras-thumbs').removeClass('active');

            $('#cameras-nav').addClass('nav-list');
        });
        $(document).on('click', '#cameras-thumbs', function () {
            $('#cameras-list').removeClass('active');
            $('#cameras-thumbs').addClass('active');

            $('#cameras-nav').removeClass('nav-list');
        });
        $(document).on('focus', '#cameras-search-input', function () {
            var searchInput = $(this);
            $(searchInput).parent().animate({'width': '100%'}, 150);
            $('#cameras-buttons').hide();
        });
        $(document).on('focusout', '#cameras-search-input', function () {
            var searchInput = $(this);
            $(searchInput).parent().removeClass('col-md-12').addClass('col-md-4');
            $(searchInput).parent().animate({'width': '33.333%'}, {
                duration: 150, complete: function () {
                    $('#cameras-buttons').show();
                }
            });
        });
        $(document).on('click', '#regroup', function () {
            var html = '';
            var bodyHtml = '';
            if (groupMode == 'categories') {
                groupMode = 'locations';

                if (jsonCameras.length > 0) {
                    jsonCameras.sort(locationSort);
                    jsonCameras.forEach(function (camera, index, array) {
                        var activeClass = '';
                        if (activeCamera == camera.id)
                            activeClass = 'class="active"';

                        if (index == 0) {
                            html += '<li class="has-sub camera-list-element">' + '<a href="javascript:;">' + '<b class="caret pull-right"></b>' + '<i class="fa fa-map-marker"></i>' + '<span class="group-name">' + camera.location_name.trunc(20) + '</span>' + '</a>' + '<ul class="sub-menu" style="display: block;">';

                            bodyHtml += '<div class="col-md-12"><h3>' + camera.location_name + '</h3></div>';

                        } else {
                            if (jsonCameras[index - 1].location_id !== camera.location_id) {
                                html += '</ul></li><li class="has-sub camera-list-element">' + '<a href="javascript:;">' + '<b class="caret pull-right"></b>' + '<i class="fa fa-map-marker"></i>' + '<span class="group-name">' + camera.location_name.trunc(20) + '</span>' + '</a>' + '<ul class="sub-menu" style="display: block;">';

                                bodyHtml += '<div class="col-md-12"><h3>' + camera.location_name + '</h3></div>';
                            }
                        }
                        html += '<li ' + activeClass + '>' + '<a href="' + yii.app.createUrl('public_cabinet/camera/', {id: camera.id}) + '">' + '<span class="camera-name">' + camera.name.trunc(20) + '</span>' + '<img src="' + camera.thumb + '" class="img-responsive">' + '</a>' + '</li>';

                        bodyHtml += cameraTemplate(camera);
                    });
                    $('.camera-list-element').remove();
                    $('.camera-list-header').after(html);
                    $('.cameras-thumbs').html(bodyHtml);
                }

            } else {
                groupMode = 'categories';

                if (jsonCameras.length > 0) {
                    jsonCameras.sort(categorySort);
                    jsonCameras.forEach(function (camera, index, array) {
                        var activeClass = '';
                        if (activeCamera == camera.id)
                            activeClass = 'class="active"';

                        if (index == 0) {
                            html += '<li class="has-sub camera-list-element">' + '<a href="javascript:;">' + '<b class="caret pull-right"></b>' + '<i class="fa fa-bookmark"></i>' + '<span class="group-name">' + camera.category_name.trunc(20) + '</span>' + '</a>' + '<ul class="sub-menu" style="display: block;">';

                            bodyHtml += '<div class="col-md-12"><h3>' + camera.category_name + '</h3></div>';
                        } else {
                            if (jsonCameras[index - 1].camera_category_id !== camera.camera_category_id) {
                                html += '</ul></li><li class="has-sub camera-list-element">' + '<a href="javascript:;">' + '<b class="caret pull-right"></b>' + '<i class="fa fa-bookmark"></i>' + '<span class="group-name">' + camera.category_name.trunc(20) + '</span>' + '</a>' + '<ul class="sub-menu" style="display: block;">';

                                bodyHtml += '<div class="col-md-12"><h3>' + camera.category_name + '</h3></div>';
                            }
                        }
                        html += '<li ' + activeClass + '>' + '<a href="' + yii.app.createUrl('public_cabinet/camera/', {id: camera.id}) + '">' + '<span class="camera-name">' + camera.name.trunc(20) + '</span>' + '<img src="' + camera.thumb + '" class="img-responsive">' + '</a>' + '</li>';

                        bodyHtml += cameraTemplate(camera);
                    });
                    $('.camera-list-element').remove();
                    $('.camera-list-header').after(html);
                    $('.cameras-thumbs').html(bodyHtml);
                }
            }
        });
        $(document).on('keyup', '#cameras-search-input', function (e) {
            var searchString = $(this).val();
            $('.group-name').show();
            $('.camera-name').show();

            $('.group-name').each(function (groupIndex, group) {
                var hasChildren = false;

                $(group).parent().parent().find('ul.sub-menu li span.camera-name').each(function (cameraIndex, camera) {
                    if ($(camera).text().indexOf(searchString) >= 0) {
                        hasChildren = true;
                        $(camera).parent().parent().show();
                    } else
                        $(camera).parent().parent().hide();
                });

                if (!hasChildren && $(group).text().indexOf(searchString) < 0) {
                    $(group).parent().parent().hide();
                }
                if (hasChildren) {
                    $(group).parent().parent().show();
                }
                if ($(group).text().indexOf(searchString) >= 0) {
                    $(group).parent().parent().show();
                    $(group).parent().parent().find('ul.sub-menu li').show();
                }
            })
        });
        function categorySort(a, b) {
            if (a.camera_category_id < b.camera_category_id)
                return -1;
            if (a.camera_category_id > b.camera_category_id)
                return 1;
            return 0;
        }
        function locationSort(a, b) {
            if (a.location_id < b.location_id)
                return -1;
            if (a.location_id > b.location_id)
                return 1;
            return 0;
        }
    </script>
<?php $this->endContent(); ?>