<?php
/**
 * Created by PhpStorm.
 * User: SlashMan
 * Date: 13.02.2015
 * Time: 16:24
 */
/* @var $images Image[] */
/* @var $camera Camera */
/* @var $json String */
/* @var $view String */
/* @var $type String */
/* @var $currentPage Integer */
/* @var $currentImage Integer */
/* @var $pagesCount Integer */
/* @var $limit Integer Количество превьюшек на странице */

$this->title = Yii::$app->name . ' - ' . $camera->name;

$this->registerCssFile(Yii::$app->homeUrl . "fw/datepicker/css/datepicker3.css");
$this->registerJsFile(Yii::$app->homeUrl . "fw/fs.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerCssFile(Yii::$app->homeUrl . "fw/animate.css");
$this->registerJsFile(Yii::$app->homeUrl . "fw/datepicker/js/bootstrap-datepicker.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "fw/datepicker/js/locales/bootstrap-datepicker.ru.js", ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl . "js/slashman-glass.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

$boot_class = 'col-md-3';
if (!empty($_COOKIE['GalleryColumn'])) {
    $boot_class = 'col-md-' . 12 / $_COOKIE['GalleryColumn'];
}

$column2 = $column3 = $column4 = $column6 = '';
switch ($_COOKIE['GalleryColumn']) {
    case '2' :
        $column2 = 'active';
        break;
    case '3' :
        $column3 = 'active';
        break;
    case '4' :
        $column4 = 'active';
        break;
    case '8' :
        $column8 = 'active';
        break;
    default:
        $column4 = 'active';
        break;
}

$size4 = $size8 = $size16 = $size32 = '';
switch ($_COOKIE['GalleryHeight']) {
    case '4' :
        $size4 = 'active';
        break;
    case '8' :
        $size8 = 'active';
        break;
    case '16' :
        $size16 = 'active';
        break;
    case '32' :
        $size32 = 'active';
        break;
    default:
        $size8 = 'active';
        break;
}
?>
<?php if (count($images) > 0) : ?>
    <div class="full-screen-container" id="view-image-big" style="display: none;">
        <div class="big-image-container">
            <?php if ($currentImage): ?>
                <img src="<?= $images[$currentImage]->getImageUrl() ?>"/>
            <?php else: ?>
                <img src="<?= $images[0]->getImageUrl() ?>"/>
            <?php endif; ?>
        </div>
        <div class="actions-bar">
            <div class="col-md-12">
                <div class="col-md-2">
                    <button class="btn btn-default pull-left exit-full-screen"><i class="fa fa-compress"></i></button>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <button class="btn btn-default zoom-plus"><i class="fa fa-search-plus"></i></button>
                        <button class="btn btn-default zoom-minus"><i class="fa fa-search-minus"></i></button>
                        <button class="btn btn-default zoom-reset"><i class="fa fa-refresh"></i></button>
                    </div>
                    <div class="col-md-8 full-screen-info">
                        <div class="col-md-1 text-center" id="zoom-rate">
                            100%
                        </div>
                        <div class="col-md-6 text-center">
                            <?= $camera->name ?>
                        </div>
                        <div class="col-md-5 text-center view-date">
                            <?php if ($isLast): ?>
                                <?= $images[0]->created; ?>
                            <?php else: ?>
                                <?= $images[$currentImage]->created; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 full-screen-range">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="range" id="full-screen-range"></div>
                        </div>
                        <div class="col-md-4 duration">
                            1 сек.
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <button class="btn btn-default toggle-slideshow"><i class="fa fa-play"></i></button>
                    <button class="btn btn-default previousImage"><i class="fa fa-step-backward"></i></button>
                    <button class="btn btn-default nextImage"><i class="fa fa-step-forward"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-message fade" id="image-modal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">×
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row text-center one-image">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 image-container">
                                        <?php if ($currentImage): ?>
                                            <img src="<?= $images[$currentImage]->getImageUrl() ?>"
                                                 class="img-responsive magniflier"
                                                 big-image="<?= $images[$currentImage]->getImageUrl() ?>"
                                                 id="view-image" class="img-responsive"/>
                                        <?php else: ?>
                                            <img src="<?= $images[0]->getImageUrl() ?>"
                                                 class="img-responsive magniflier"
                                                 big-image="<?= $images[0]->getImageUrl() ?>" id="view-image"
                                                 class="img-responsive"/>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="btn-group pull-left">
                                            <button href="#" class="btn btn-default" id="lens" title="Экранная лупа">
                                                <i class="fa fa-search-plus"></i>
                                            </button>
                                            <button id="fullScreen" class="btn btn-default" title="На весь экран">
                                                <i class="fa fa-expand"></i>
                                            </button>
                                            <?php if ($view !== 'thumbs'): ?>
                                                <a href="<?= $this->context->createUrl(['/cabinet/camera', 'id' => $camera->id, 'view' => 'thumbs']) ?>"
                                                   class="btn btn-default" title="Предпросмотр изображений">
                                                    <i class="fa fa-th"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center image-name view-date">
                                        <?php if ($isLast): ?>
                                            <?= date("d-m-y H:i:s", strtotime($images[0]->created)); ?>
                                        <?php else: ?>
                                            <?= date('d-m-y H:i:s', strtotime($images[$currentImage]->created)); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="range" id="range"></div>
                                                <div class="duration">
                                                    1 сек.
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="btn-group pull-right">
                                            <button
                                                class="btn btn-default previousImage"
                                                title="Предыдущее изображение">
                                                <i class="fa fa-backward"></i>
                                            </button>
                                            <button class="btn btn-default toggle-slideshow" title="Слайдшоу"><i
                                                    class="fa fa-play"></i></button>
                                            <button
                                                class="btn btn-default nextImage"
                                                title="Следующее изображение">
                                                <i class="fa fa-forward"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="col-md-12 camera-thumbnails-full">
    <div class="image-thumbnails" style="overflow-y: scroll;" data-height="calc(100vh - 143px)" data-scrollbar="true">
        <div class="col-md-12">
            <div class="page-header col-md-3">
                <?php if ($camera->icon_name): ?>
                    <img src="<?= Yii::$app->homeUrl ?>uploads/camera_icons/<?= $camera->icon_name ?>"
                         class="header-camera-icon">
                <?php endif; ?>
                <?= $camera->name; ?>
            </div>
            <div class="text-center col-md-5">
                <ul class="pagination pagination-sm">
                    <li class="disabled previous-page">
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
                        </a>
                    </li>
                    <?php for ($i = max(1, $currentPage - 3); $i <= min($pagesCount, $currentPage + 3); $i++): ?>
                        <li page-number="<?= $i ?>" class="go-to-page<?= $currentPage == $i ? ' active' : '' ?>"><a
                                href="#"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <li>
                        <a href="#" aria-label="Next" class="next-page">
                            <span aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 p-l-0 p-r-0">
                <div class="dropdown change-view-wrap text-right">
                    <button class="btn btn-inverse m-r-10 btn-sm dropdown-toggle pull-right" type="button"
                            id="change-view"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Вид просмотра
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="change-view">
                        <li>
                            <div class="row view-menu">
                                <a href="#" class="js-sort" data-sort="asc">Прямая сортировка </a>
                                <button class="btn btn-inverse btn-sm <?= $column2 ?> size-change" data-size="6" data-column="2">2</button>
                                <button class="btn btn-inverse btn-sm <?= $column3 ?> size-change" data-size="4" data-column="3">3</button>
                                <button class="btn btn-inverse btn-sm <?= $column4 ?> size-change" data-size="3" data-column="4">4</button>
                                <button class="btn btn-inverse btn-sm <?= $column6 ?> size-change" data-size="2" data-column="6">6</button>
                                <span>X</span>
                                <button class="btn btn-inverse btn-sm limit-change  <?= $size4 ?>" data-size="4">4</button>
                                <button class="btn btn-inverse btn-sm limit-change  <?= $size8 ?>" data-size="8">8</button>
                                <button class="btn btn-inverse btn-sm limit-change  <?= $size16 ?>" data-size="16">16</button>
                                <button class="btn btn-inverse btn-sm limit-change  <?= $size32 ?>" data-size="32">32</button>
                            </div>
                        </li>
                    </ul>
                    <a href="<?= $this->context->createUrl(['/cabinet/camera', 'id' => $camera->id, 'view' => 'one']) ?>"
                       class="btn btn-sm btn-inverse m-r-10 pull-right">Режим галереи</a>
                </div>
            </div>
        </div>
        <?php if (count($images) > 0): ?>
            <div class="thumbnails-list">
                <?php for ($i = ($currentPage - 1) * $limit; $i < min($currentPage * $limit, count($images)); $i++): ?>
                    <div class="<?= $boot_class ?> thumbnail-container" image-id="<?= $images[$i]->id ?>">
                        <div class="panel panel-default">
                            <div class="panel-body" image-index="<?= $i ?>">
                                <div class="image-container show-modal">
                                    <img src="<?= $images[$i]->getThumbnailUrl() ?>" class="img-responsive cam-thumb"/>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= date('d-m-y H:i', strtotime($images[$i]->created)); ?>
                                        <input type="checkbox" class="pull-right thumb-check"
                                               image-id="<?= $images[$i]->id ?>"/>
                                        <span class="pull-right favorite-star">
                                            <?= $this->render('favorite', ['id' => $images[$i]->id, 'f_fav' => $images[$i]->f_fav]); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script id="image-container-template" type="">

</script>
<script>

    var jsonImages = JSON.parse('<?=$json?>');
    var activeClass = 'col-md-3';

    var pagesCount = <?=$pagesCount?>;
    var currentPage = <?=$currentPage?>;
    var limit = $.cookie('GalleryColumn') * $.cookie('GalleryHeight');
    var sort = 'desc';
    var cameraId = <?=$id?>;
    var magnifierEnabled = false;
    var currentScale = 1;
    var previousScale = 1;
    var slideshowTimerId = null;
    var slideshowInterval = 1;
    var type = '<?=$type?>';
    var boot_class = '<?=$boot_class?>';

    $(document).on('click', '.limit-change', function () {
        var dataSize = $(this).attr('data-size');
        $.cookie('GalleryHeight', dataSize);
        location.reload();
    });

    $(document).ready(function () {

        $('#fullScreen').click(function () {
            $('#view-image-big').css('display', 'block').fullScreen(true);
        });
        $(document).on('click', '.exit-full-screen', function () {
            $('#view-image-big').css('display', 'none').fullScreen(false);
        });
        $('#view-image-big').bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function (e) {
            var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
            var event = state ? 'FullscreenOn' : 'FullscreenOff';

            if (!state)
                $('#view-image-big').css('display', 'none');
        });

        $('#lens').click(function () {
            if ($(this).hasClass('active')) {
                turnOffMagnifier();
                $(this).removeClass('active');
            } else {
                turnOnMagnifier();
                $(this).addClass('active');
            }
        });

        $('.range').slider({
            max: 5, min: 1, step: 1, value: 2, slide: function (event, slider) {
                $('.duration').text(getTime(slider.value) + ' сек.');
            }, stop: function (event, slider) {
                if (this.id == 'range')
                    $('#full-screen-range').slider("option", "value", slider.value); else
                    $('#range').slider("option", "value", slider.value);
                resetSlideShow();
            }
        });

        $('#image-modal').on('hide.bs.modal', function (e) {
            stopSlideShow();
        });

        doMagnify();
        zoomDraggable();
        updatePagination();
    });
    function turnOffMagnifier() {
        magnifierEnabled = false;
    }
    function turnOnMagnifier() {
        magnifierEnabled = true;
    }

    //Клик по превью
    $(document).on('click', '.show-modal', function () {
        $('#image-modal').modal('show');

    });
    //Изменение размеров превью
    $(document).on('click', '.js-sort', function () {
        var self = $(this);
        sort = self.data('sort');
        moveToPage(1);
        if (sort == 'asc') {
            self.data('sort', 'desc');
            self.html('Обратная сортировка');
        } else {
            self.data('sort', 'asc');
            self.html('Прямая сортировка');
        }
    });

    $(document).on('click', '.size-change', function () {
        var dataSize = $(this).attr('data-size');
        var column = $(this).attr('data-column');
        activeClass = 'col-md-' + dataSize;
        boot_class = 'col-md-' + dataSize;

        $('.size-change').removeClass('active');
        $(this).addClass('active');

        $.cookie('GalleryColumn', column);

        $('.thumbnail-container').removeClass('col-md-6').removeClass('col-md-4').removeClass('col-md-3').removeClass('col-md-2').addClass(activeClass).addClass('fadeInUp animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('fadeInUp animated');
        });
        updatePagination();
    });

    $(document).on('click', '.zoom-plus', function () {
        previousScale = currentScale;
        var image = $('#view-image-big img:first');

        if (currentScale < 4)
            currentScale += 0.5;
        //zoomDraggable();
        setNewFullScreenScale();

    });
    $(document).on('click', '.zoom-minus', function () {
        previousScale = currentScale;
        var image = $('#view-image-big img:first');

        if (currentScale > 1) {
            currentScale -= 0.5;
            setNewFullScreenScale();
        }

        if (currentScale == 1)
            resetFullscreenImage();
    });
    $(document).on('click', '.zoom-reset', function () {
        previousScale = currentScale;
        resetFullscreenImage();
    });
    $(document).on('click', '.next-page', function () {
        nextPage();
    });
    $(document).on('click', '.previous-page', function () {
        previousPage();
    });

    $(document).on('click', '.go-to-page', function () {
        moveToPage(parseInt($(this).attr('page-number')));
    });
    $(document).on('click', '.toggle-slideshow', function () {
        if ($(this).hasClass('active')) {
            stopSlideShow();
        } else {
            startSlideShow();
        }
    });

    $(document).on('click', '.nextImage', function () {
        showNextImage();
    });
    $(document).on('click', '.previousImage', function () {
        showPreviousImage();
    });

    $(document).on('click', '.thumbnail-container .panel-body', function () {
        showImageByIndex(parseInt($(this).attr('image-index')));
    });
    function startSlideShow() {
        slideshowTimerId = setInterval(showNextImage, getTime() * 1000);
        $('.toggle-slideshow').addClass('active').removeClass('btn-success').addClass('btn-warning').html('<i class="fa fa-pause"></i>');
    }
    function stopSlideShow() {
        if (slideshowTimerId !== null)
            clearInterval(slideshowTimerId);
        slideshowTimerId = null;
        $('.toggle-slideshow').removeClass('active').addClass('btn-success').removeClass('btn-warning').html('<i class="fa fa-play"></i>');
    }
    function resetSlideShow() {
        if (slideshowTimerId !== null) {
            clearInterval(slideshowTimerId);
            slideshowTimerId = setInterval(showNextImage, getTime() * 1000);
        }
    }
    function getTime(value) {
        var time;
        if (typeof value === 'undefined')
            time = parseInt($('#range').slider("option", "value")); else
            time = value;

        switch (time) {
            case 1:
                return 0.5;
            case 2:
                return 1;
            case 3:
                return 2;
            case 4:
                return 3;
            case 5:
                return 5;
            default:
                return 5;
        }
    }
    function resetFullscreenImage() {
        var image = $('#view-image-big img:first');
        currentScale = 1;
        //$(image).draggable('destroy');
        $(image).css('left', '0');
        $(image).css('top', '0');
        setNewFullScreenScale();
    }

    function setNewFullScreenScale() {
        var image = $('#view-image-big img:first');
        //$(image).css('width', (currentScale * 100).toString() + '%');

        $('#zoom-rate').text((currentScale * 100).toString() + '%');

        $(image).animate({
            'width': (currentScale * 100).toString() + '%', //'top': parseInt(parseInt($(image).css('top')) / previousScale * currentScale).toString() + 'px',
            //'left': parseInt(parseInt($(image).css('left')) / previousScale * currentScale).toString() + 'px'
        }, 1000);
    }


    //Показ следующей фотки
    function showNextImage() {

        if(currentImage == (jsonImages.length-1) ){
            nextPage();
            currentImage = 0;
        }else{
            currentImage++;
        }

        var nextImageData = jsonImages[currentImage];
        changeImage(nextImageData, currentImage);

    }

    function showPreviousImage() {
        if(currentImage == 0){
            previousPage();
            currentImage = jsonImages.length - 1;
        }else{
            currentImage--;
        }

        var previousImageData = jsonImages[currentImage];
        changeImage(previousImageData, currentImage);
    }

    function showImageByIndex(index) {
        changeImage(jsonImages[index], index);
    }

    function changeImage(imageObject, index) {

        $('#view-image-big img:first').attr('src', imageObject.big);
        $('#view-image').attr('src', imageObject.big);
        currentImage = index;

        updateMagnifier();
        updateBigDate();
        newImg = true;
    }

    function updateMagnifier() {
        if (magnifierEnabled) {
            turnOffMagnifier();
            turnOnMagnifier();
        } else
            turnOffMagnifier();
    }
    function zoomDraggable() {
        $("#view-image-big img:first").draggable({
            drag: function () {
                var img = $("#view-image-big img:first");
                var offset = $(img).offset();

                var winWidth = $(window).width();
                var winHeight = $(window).height();

                var dragBorderWidth = winWidth;
                var dragBorderHeight = winHeight - 100;

                if (offset.left - winWidth > 0) {
                    $(img).css('left', '0');
                    return false;
                }
                if (parseInt($(img).css('top')) - winHeight > 0) {
                    $(img).css('top', '0');
                    return false;
                }
                if (Math.abs(offset.left) - winWidth > $(img).width() - winWidth) {
                    $(img).css('left', (-$(img).width() + winWidth).toString() + 'px');
                    return false;
                }
                if (Math.abs(offset.top) - winHeight > $(img).height() - winHeight) {
                    $(img).css('top', (-$(img).height() + winHeight).toString() + 'px');
                    return false;
                }
            }, stop: function () {
                var img = $("#view-image-big img:first");
                var offset = $(img).offset();

                var winWidth = $(window).width();
                var winHeight = $(window).height();
                var heightDifference = winHeight > $(img).height() ? winHeight - $(img).height() : 0;
                var widthDifference = winWidth > $(img).width() ? winWidth - $(img).width() : 0;
                var animateAttrs = {};
                if (offset.left > 0) {
                    animateAttrs.left = '0';
                }
                if (parseInt($(img).css('top')) > 0) {
                    animateAttrs.top = '0';
                }
                if (Math.abs(offset.left) > $(img).width() - winWidth + widthDifference && offset.left < 0) {
                    animateAttrs.left = (-$(img).width() + winWidth).toString() + 'px';
                }
                if (Math.abs(parseInt($(img).css('top'))) > $(img).height() - winHeight + heightDifference && parseInt($(img).css('top')) < 0) //was offset.top
                {
                    if (heightDifference !== 0)
                        animateAttrs.top = '0'; else
                        animateAttrs.top = (-$(img).height() + winHeight).toString() + 'px';
                }
                $(img).animate(animateAttrs, 100);
            }
        });
    }
    function updateArrows() {
        if (currentImage == 0 || jsonImages.length == 0)
            $('.previousImage').attr('disabled', 'disabled'); else
            $('.previousImage').removeAttr('disabled');

        if (currentImage >= jsonImages.length - 1)
            $('.nextImage').attr('disabled', 'disabled'); else
            $('.nextImage').removeAttr('disabled');
    }

    function updateBigDate() {
        var newDate = jsonImages[currentImage].created;
        if (currentImage == 0)
            newDate = newDate;
        $('.view-date').text(newDate);
    }
    function previousPage() {
        if (currentPage === 1){
            moveToPage(pagesCount);
        }else{
            moveToPage(currentPage - 1);
        }
    }
    function nextPage() {
        if (currentPage >= pagesCount){
            moveToPage(1);
        }else{
            moveToPage(currentPage + 1);
        }
    }
    function moveToPage(page) {
        $.ajax({
            async: false,
            url: yii.app.createUrl('cabinet/camera/get-json-images', {
                    id: cameraId,
                    page: page,
                    limit: limit,
                    type: type,
                    sort: sort
                }, '&', 'get'),
            success: function (images) {
                jsonImages = JSON.parse(images);

                var imageThumbnailsContainer = $('.thumbnails-list');
                var html = '';

                //for(var i = (page - 1) * limit; i < Math.min(page * limit, jsonImages.length); i++)
                for (var i = 0; i < jsonImages.length; i++) {
                    html = html + '<div class="' + boot_class + ' thumbnail-container" image-id="' + jsonImages[i].id + '">' + '<div class="panel panel-default">' + '<div class="panel-body" image-index="' + i + '">' + '<div class="row image-container show-modal">' + '<img src="' + jsonImages[i].thumb + '" class="img-responsive cam-thumb"/>' + '</div>' + '</div>' + '<div class="panel-footer">' + '<div class="row">' + '<div class="col-md-10">' + jsonImages[i].created + '</div>' + '<div class="col-md-2">' + '<input type="checkbox" class="pull-right thumb-check" image-id="' + jsonImages[i].id + '"/>' + '</div>' + '</div>' + '</div>' + '</div>' + '</div>';
                }
                $(imageThumbnailsContainer).html(html);

                currentPage = page;

                updatePagination();
            }
        });
    }

    function updatePagination() {
        var paginationContainer = $('.pagination');

        var html = '';
        var previousDisabled = '';
        var nextDisabled = '';

        if (currentPage == 1)
            previousDisabled = ' disabled';
        if (currentPage == pagesCount)
            nextDisabled = ' disabled';

        html += '<li class="previous-page' + previousDisabled + '">' + '<a href="#" aria-label="Previous">' + '<span aria-hidden="true"><i class="fa fa-chevron-left"></i></span>' + '</a>' + '</li>';

        for (var i = Math.max(1, currentPage - 3); i <= Math.min(pagesCount, currentPage + 3); i++) {
            var activeClass = '';
            if (i == currentPage)
                activeClass = ' active';

            html += '<li page-number="' + i + '" class="go-to-page' + activeClass + '"><a href="#">' + i + '</a></li>';
        }
        html += '<li class="next-page' + nextDisabled + '">' + '<a href="#" aria-label="Next">' + '<span aria-hidden="true"><i class="fa fa-chevron-right"></i></span>' + '</a>' + '</li>';

        $(paginationContainer).html(html);
    }
</script>