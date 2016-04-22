<?php
/* @var $this ObjectController */
$this->title = Yii::$app->name . ' - Мои объекты';

$i = 1;

?>
<?php $this->beginContent('@public_cabinet/views/layouts/main.php'); ?>
    <div id="sidebar" class="sidebar">
        <div data-scrollbar="true" data-height="100%">
            <ul class="nav objects-nav">
                <li class="nav-header text-center">
                    <span><i class="fa fa-map-marker"></i> Список объектов</span>
                </li>
                <?php if (Yii::$app->user->identity->hasUnmappedLocations()): ?>
                    <li class="nav-header text-center marker-hint">
                        <span class="text-success text-center">перетащите маркер на карту&nbsp;&nbsp;<i
                                class="fa fa-close"></i></span>
                    </li>
                <?php endif; ?>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-map-marker"></i>
                        <span>Места</span>
                    </a>
                    <ul class="sub-menu locations-list noicon" style="display: block;">
                        <?php foreach ($this->context->locations as $location): ?>
                            <li class="locations-list-element" location-id="<?= $location->id ?>">
                                <a class="row">
                                    <?php if ($location->lat === null && $location->lon === null): ?>
                                        <i class="fa fa-map-marker map-marker pull-right text-success"
                                           location-id="<?= $location->id ?>"></i>
                                    <?php endif; ?>
                                    <div class="col-md-9 info p-l-0 p-r-0">
                                        <span class="edit-location-label"><?= $location->getName() ?></span>
                                        <input type="text" class="form-control input-sm edit-input"
                                               value="<?= $location->name ?>">
                                    </div>
                                    <div class="col-md-3 buttons p-l-0 p-r-0">
                                        <i class="fa fa-trash pull-right delete-location"
                                           location-id="<?= $location->id ?>"></i>
                                        <i class="fa fa-pencil pull-right edit-location"
                                           location-id="<?= $location->id ?>"></i>
                                        <i class="fa fa-close cancel-edit-location pull-right"></i>
                                        <i class="fa fa-check do-edit-location pull-right"></i>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li class="add-row">
                            <a class="row">
                                <div class="info p-l-0 p-r-0">
                                    <input type="text" class="form-control input-sm" id="add-location-input">
                                    <!--style="width: 0; display: none;"-->
                                    <span class="add-location-label">Добавить</span>
                                </div>
                                <div class="buttons p-l-0 p-r-0">
                                    <i class="fa fa-plus add-location pull-right"></i>
                                    <i class="fa fa-close cancel-location pull-right"></i>
                                    <i class="fa fa-check do-add-location pull-right"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-bookmark"></i>
                        <span>Категории</span>
                    </a>
                    <ul class="sub-menu categories-list noicon" style="display: block;">
                        <?php foreach ($this->context->categories as $category): ?>
                            <li class="categories-list-element" category-id="<?= $category->id ?>">
                                <a class="row">
                                    <div class="info p-l-0 p-r-0">
                                        <span class="edit-location-label"><?= $category->name ?></span>
                                        <input type="text" class="form-control input-sm edit-input"
                                               value="<?= $category->name ?>">
                                    </div>
                                    <div class="buttons p-l-0 p-r-0">
                                        <i class="fa fa-trash pull-right delete-category"
                                           category-id="<?= $category->id ?>"></i>
                                        <i class="fa fa-pencil pull-right edit-category"
                                           category-id="<?= $category->id ?>"></i>
                                        <i class="fa fa-close cancel-edit-category pull-right"></i>
                                        <i class="fa fa-check do-edit-category pull-right"></i>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li class="add-row">
                            <a class="row">
                                <div class="info p-l-0 p-r-0">
                                    <input type="text" class="form-control input-sm" id="add-category-input">
                                    <!--style="width: 0; display: none;"-->
                                    <span class="add-category-label">Добавить</span>
                                </div>
                                <div class="buttons p-l-0 p-r-0">
                                    <i class="fa fa-plus add-category pull-right"></i>
                                    <i class="fa fa-close cancel-category pull-right"></i>
                                    <i class="fa fa-check do-add-category pull-right"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- end sidebar nav -->
        </div>
    </div>
    <!-- end sidebar scrollbar -->
    <div id="content" class="gmap-content content">
        <?php echo $content; ?>
        <?php if (!empty($this->context->footer)): ?>
            <footer class="camera-footer">
                <?= $this->context->footer ?>
            </footer>
        <?php endif; ?>
    </div>
    <script>

        //ADD LOCATION
        $(document).on('click', '.add-location', function (e) {
            $('.add-location').hide();
            $('.add-location-label').hide();
            $('#add-location-input').show().animate({'width': '100%'}, 100);
            $('.do-add-location').show();
            $('.cancel-location').show();
        });
        $(document).on('click', '.cancel-location', function (e) {
            $('#add-location-input').animate({'width': '0'}, {
                duration: 100, complete: function () {
                    $('.do-add-location').hide();
                    $('.cancel-location').hide();
                    $('#add-location-input').hide();
                    $('.add-location').show();
                    $('.add-location-label').show();
                }
            });
        });

        $(document).on('click', '.marker-hint i', function () {
            $(this).parent().remove();
        });

        $(document).on('click', '.do-add-location', function (e) {
            var locationName = $('#add-location-input').val();
            $.post(yii.app.createUrl('public_cabinet/object/ajax-add-location'), {
                name: locationName
            }).done(function (response) {
                response = JSON.parse(response);
                var html = '<li class="locations-list-element" location-id="' + response.id + '">' + '<a class="row">' + '<i class="fa fa-map-marker map-marker pull-right text-success" location-id="' + response.id + '"></i>' + '<div class="col-md-9 info p-l-0 p-r-0">' + '<span class="edit-location-label">' + response.name.trunc(12) + '</span>' + '<input type="text" class="form-control input-sm edit-input" value="' + response.name + '">' + '</div>' + '<div class="col-md-3 buttons p-l-0 p-r-0">' + '<i class="fa fa-trash pull-right delete-location" location-id="' + response.id + '"></i>' + '<i class="fa fa-pencil pull-right edit-location" location-id="' + response.id + '"></i>' + '<i class="fa fa-close cancel-edit-location pull-right"></i>' + '<i class="fa fa-check do-edit-location pull-right"></i>' + '</div>' + '</a>' + '</li>';
                $('.locations-list .add-row').before(html);
                makeDraggable($('.locations-list-element:last i.map-marker'));
                $('#add-location-input').animate({'width': '0'}, {
                    duration: 100, complete: function () {
                        $('.do-add-location').hide();
                        $('.cancel-location').hide();
                        $('#add-location-input').hide();
                        $('.add-location').show();
                        $('.add-location-label').show();
                    }
                });
            });
        });

        //ADD CATEGORY
        $(document).on('click', '.add-category', function (e) {
            $('.add-category').hide();
            $('.add-category-label').hide();
            $('#add-category-input').show().animate({'width': '100%'}, 100);
            $('.do-add-category').show();
            $('.cancel-category').show();
        });
        $(document).on('click', '.cancel-category', function (e) {
            $('#add-category-input').animate({'width': '0'}, {
                duration: 100, complete: function () {
                    $('.do-add-category').hide();
                    $('.cancel-category').hide();
                    $('#add-category-input').hide();
                    $('.add-category').show();
                    $('.add-category-label').show();
                }
            });
        });
        $(document).on('click', '.do-add-category', function (e) {
            var categoryName = $('#add-category-input').val();
            $.post(yii.app.createUrl('public_cabinet/object/ajax-add-category'), {
                name: categoryName
            }).done(function (response) {
                response = JSON.parse(response);
                var html = '<li class="categories-list-element" category-id="' + response.id + '">' + '<a class="row">' + '<div class="info p-l-0 p-r-0">' + '<span class="edit-location-label">' + response.name.trunc(12) + '</span>' + '<input type="text" class="form-control input-sm edit-input" value="' + response.name + '">' + '</div>' + '<div class="buttons p-l-0 p-r-0">' + '<i class="fa fa-trash pull-right delete-category" category-id="' + response.id + '"></i>' + '<i class="fa fa-pencil pull-right edit-category" category-id="' + response.id + '"></i>' + '<i class="fa fa-close cancel-edit-category pull-right"></i>' + '<i class="fa fa-check do-edit-category pull-right"></i>' + '</div>' + '</a>' + '</li>';
                $('.categories-list .add-row').before(html);
                $('#add-category-input').animate({'width': '0'}, {
                    duration: 100, complete: function () {
                        $('.do-add-category').hide();
                        $('.cancel-category').hide();
                        $('#add-category-input').hide();
                        $('.add-category').show();
                        $('.add-category-label').show();
                    }
                });
            });
        });

        //EDIT CATEGORY
        $(document).on('click', '.edit-category', function (e) {
            var editCategoryButtons = $(this).parent().find('.delete-category, .edit-category');
            var editCategoryName = $(this).parent().parent().find('span');
            var editCategoryInput = $(this).parent().parent().find('input');
            var editCategoryOKCancel = $(this).parent().parent().find('.do-edit-category, .cancel-edit-category');

            $(editCategoryName).hide();
            $(editCategoryButtons).hide();
            $(editCategoryOKCancel).show();
            $(editCategoryInput).show().animate({'width': '100%'}, 100);
        });
        $(document).on('click', '.cancel-edit-category', function (e) {
            var editCategoryButtons = $(this).parent().find('.delete-category, .edit-category');
            var editCategoryName = $(this).parent().parent().find('span');
            var editCategoryInput = $(this).parent().parent().find('input');
            var editCategoryOKCancel = $(this).parent().parent().find('.do-edit-category, .cancel-edit-category');

            $(editCategoryInput).animate({'width': '0'}, {
                duration: 100, complete: function () {
                    $(editCategoryOKCancel).hide();
                    $(editCategoryInput).hide();
                    $(editCategoryButtons).show();
                    $(editCategoryName).show();
                }
            });
        });
        $(document).on('click', '.do-edit-category', function (e) {
            var editCategoryButtons = $(this).parent().find('.delete-category, .edit-category');
            var editCategoryName = $(this).parent().parent().find('span');
            var editCategoryInput = $(this).parent().parent().find('input');
            var editCategoryOKCancel = $(this).parent().parent().find('.do-edit-category, .cancel-edit-category');
            var categoryName = $(editCategoryInput).val();
            var categoryId = $(this).parent().parent().parent().attr('category-id');

            $.post(yii.app.createUrl('public_cabinet/object/ajax-edit-category'), {
                id: categoryId, name: categoryName
            }).done(function (response) {
                response = JSON.parse(response);

                $(editCategoryName).text(response.name);
                $(editCategoryInput).animate({'width': '0'}, {
                    duration: 100, complete: function () {
                        $(editCategoryOKCancel).hide();
                        $(editCategoryInput).hide();
                        $(editCategoryButtons).show();
                        $(editCategoryName).show();
                    }
                });
            });
        });

        //EDIT LOCATION
        $(document).on('click', '.edit-location', function (e) {
            var editLocationButtons = $(this).parent().find('.delete-location, .edit-location');
            var editLocationName = $(this).parent().parent().find('span');
            var editLocationInput = $(this).parent().parent().find('input');
            var editLocationOKCancel = $(this).parent().parent().find('.do-edit-location, .cancel-edit-location');

            $(editLocationName).hide();
            $(editLocationButtons).hide();
            $(editLocationOKCancel).show();
            $(editLocationInput).show().animate({'width': '100%'}, 100);
        });
        $(document).on('click', '.cancel-edit-location', function (e) {
            var editLocationButtons = $(this).parent().find('.delete-location, .edit-location');
            var editLocationName = $(this).parent().parent().find('span');
            var editLocationInput = $(this).parent().parent().find('input');
            var editLocationOKCancel = $(this).parent().parent().find('.do-edit-location, .cancel-edit-location');

            $(editLocationInput).animate({'width': '0'}, {
                duration: 100, complete: function () {
                    $(editLocationOKCancel).hide();
                    $(editLocationInput).hide();
                    $(editLocationButtons).show();
                    $(editLocationName).show();
                }
            });
        });
        $(document).on('click', '.do-edit-location', function (e) {
            var editLocationButtons = $(this).parent().find('.delete-location, .edit-location');
            var editLocationName = $(this).parent().parent().find('span');
            var editLocationInput = $(this).parent().parent().find('input');
            var editLocationOKCancel = $(this).parent().parent().find('.do-edit-location, .cancel-edit-location');
            var locationName = $(editLocationInput).val();
            var locationId = $(this).parent().parent().parent().attr('location-id');

            $.post(yii.app.createUrl('public_cabinet/object/ajax-edit-location'), {
                id: locationId, name: locationName
            }).done(function (response) {
                response = JSON.parse(response);

                if (typeof markers !== 'undefined') {
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].marker.id == locationId) {
                            markers[i].infoWindow.setContent(generateBalloon(response));
                            markers[i].marker.setTitle(response.name);
                            break;
                        }
                    }
                }

                $(editLocationName).text(response.name);
                $(editLocationInput).animate({'width': '0'}, {
                    duration: 100, complete: function () {
                        $(editLocationOKCancel).hide();
                        $(editLocationInput).hide();
                        $(editLocationButtons).show();
                        $(editLocationName).show();
                    }
                });
            });
        });

        //DELETE LOCATION
        $(document).on('click', '.delete-location', function (e) {
            var locationId = $(this).attr('location-id');
            var locationContainer = $(this).parent().parent();
            swal({
                title: "Вы уверены?",
                text: "Место будет удалено",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить",
                cancelButtonText: "Отмена",
                closeOnConfirm: false
            }, function () {
                $.post(yii.app.createUrl('public_cabinet/object/ajax-remove-location'), {
                    id: locationId
                }).done(function (response) {
                    if (typeof markers !== 'undefined') {
                        for (var i = 0; i < markers.length; i++) {
                            if (markers[i].marker.id == locationId) {
                                removeGeoLocation(i);
                                break;
                            }
                        }
                    }
                    $(locationContainer).remove();
                    swal("Удалено!", "Место удалено", "success");
                });
            });
        });

        //DELETE CATEGORY
        $(document).on('click', '.delete-category', function (e) {
            var categoryId = $(this).attr('category-id');
            var categoryContainer = $(this).parent().parent();
            swal({
                title: "Вы уверены?",
                text: "Категория будет удалена",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Удалить",
                cancelButtonText: "Отмена",
                closeOnConfirm: false
            }, function () {
                $.post(yii.app.createUrl('public_cabinet/object/ajax-remove-category'), {
                    id: categoryId
                }).done(function (response) {
                    $(categoryContainer).remove();
                    swal("Готово!", "Категория удалена", "success");
                });
            });
        });
    </script>
<?php $this->endContent(); ?>