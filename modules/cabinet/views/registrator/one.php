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
/* @var $date String */
/* @var $isLast Bool */
/* @var $currentImage Integer Номер текущей фотки в массиве $images */
/* @var $imageId Integer ID Текущей фотки */
/* @var $next Integer */
/* @var $previous Integer */
/* @var $currentPage Integer */
/* @var $pagesCount Integer */
/* @var $limit Integer Количество превьюшек на странице */


$this->title = Yii::$app->name . ' - ' . $camera->getName();

$this->registerCssFile(Yii::$app->homeUrl . "fw/datepicker/css/datepicker3.css");
$this->registerJsFile(Yii::$app->homeUrl . "fw/fs.js");
$this->registerJsFile(Yii::$app->homeUrl . "fw/datepicker/js/bootstrap-datepicker.js");
$this->registerJsFile(Yii::$app->homeUrl . "fw/datepicker/js/locales/bootstrap-datepicker.ru.js");
$this->registerJsFile(Yii::$app->homeUrl . "js/slashman-glass.js");
?>
<?php if (count($images) > 0): ?>
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
                            <?= $camera->getName() ?>
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
<?php endif; ?>
<div class="col-md-7 large-image">
    <div class="page-header m-b-5">
        <?php if ($camera->icon_name): ?>
            <img src="<?= Yii::$app->homeUrl ?>uploads/camera_icons/<?= $camera->icon_name ?>"
                 class="header-camera-icon">
        <?php endif; ?>
        <?= $camera->getName(); ?>
    </div>
    <?php if (isset($images[0])): ?>
        <div class="row text-center one-image">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 image-container">
                            <?php if ($currentImage): ?>
                                <img src="<?= $images[$currentImage]->getImageUrl() ?>"
                                     class="img-responsive magniflier"
                                     big-image="<?= $images[$currentImage]->getImageUrl() ?>" id="view-image"
                                     class="img-responsive"/>
                            <?php else: ?>
                                <img src="<?= $images[0]->getImageUrl() ?>" class="img-responsive magniflier"
                                     big-image="<?= $images[0]->getImageUrl() ?>" id="view-image"
                                     class="img-responsive"/>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3 text-center image-name view-date">
                            <?php if ($isLast): ?>
                                <?= $images[0]->created; ?>
                            <?php else: ?>
                                <?= $images[$currentImage]->created; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="range" id="range"></div>
                                </div>
                                <div class="col-md-3 p-t-5 duration">
                                    1 сек.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group pull-right">
                                <button class="btn btn-success toggle-slideshow" title="Слайдшоу"><i
                                        class="fa fa-play"></i></button>
                                <button
                                    class="btn btn-primary previousImage" <?= $previous == null ? 'disabled="disabled"' : '' ?>
                                    title="Предыдущее изображение">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <button
                                    class="btn btn-primary nextImage" <?= $next == null ? 'disabled="disabled"' : '' ?>
                                    title="Следующее изображение">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!isset($images[0])): ?>
        <div class="alert-container">
            <div class="alert alert-warning" role="alert">Изображения отсутствуют...</div>
        </div>
    <?php endif; ?>
</div>
<?php if (count($images) > 0): ?>
    <div class="col-md-5 camera-thumbnails">
        <div class="text-center m-t-10 m-b-5">
            <ul class="pagination m-b-5">
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


            <div class="btn-group pull-right">
                <button class="btn btn-white size-change active" data-size="6"><i class="fa fa-th-large" title="2"></i>
                </button>
                <button class="btn btn-white size-change" data-size="4"><i class="fa fa-th" title="3"></i></button>
            </div>
        </div>
        <div class="image-thumbnails" style="overflow-y: scroll;" data-height="calc(100vh - 143px)">
            <?php for ($i = ($currentPage - 1) * $limit; $i < min($currentPage * $limit, count($images)); $i++): ?>
                <div class="col-md-6 thumbnail-container left-thumb <?= $images[$i]->id == $imageId ? 'current' : '' ?>"
                     image-id="<?= $images[$i]->id ?>">
                    <div class="panel panel-default">
                        <div class="panel-body" image-index="<?= $i ?>">
                            <div class="row image-container">
                                <img src="<?= $images[$i]->getThumbnailUrl() ?>" class="img-responsive cam-thumb"
                                     full-img="<?= $images[$i]->getImageUrl() ?>"/>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-10">
                                    <?= $images[$i]->created; ?>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" class="pull-right thumb-check"
                                           image-id="<?= $images[$i]->id ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
<?php endif; ?>

<script>
    var jsonImages = JSON.parse('<?=$json?>');
    var images = '';
    var currentSelected = false;
    var currentImage = <?=$currentImage?>;
    var date = '<?=$date?>';
    var cameraId = '<?=$camera->id?>';
    var imageId = '<?=$imageId?>';
    var type = '<?=$type?>';
    var pagesCount = <?=$pagesCount?>;
    var currentPage = <?=$currentPage?>;
    var limit = <?=$limit?>;
    var sort = 'desc';
    var magnifierEnabled = false;
    var currentScale = 1;
    var previousScale = 1;
    var slideshowTimerId = null;
    var slideshowInterval = 1;


    $(document).ready(function () {
        App.init();
        //Fullscreen //@todo Replace fullScreen container with something special :)
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
            }
            else {
                turnOnMagnifier();
                $(this).addClass('active');
            }
        });

        $('.range').slider({
            max: 5,
            min: 1,
            step: 1,
            value: 2,
            slide: function (event, slider) {
                $('.duration').text(getTime(slider.value) + ' сек.');
            },
            stop: function (event, slider) {
                if (this.id == 'range')
                    $('#full-screen-range').slider("option", "value", slider.value);
                else
                    $('#range').slider("option", "value", slider.value);
                resetSlideShow();
            }
        });
        doMagnify();
        zoomDraggable();

    });

    function turnOffMagnifier() {
        magnifierEnabled = false;
    }
    function turnOnMagnifier() {
        magnifierEnabled = true;
    }

    //Thumb size change

    $(document).on('click', '.size-change', function () {
        var dataSize = $(this).attr('data-size');
        activeClass = 'col-md-' + dataSize;

        $('.size-change').removeClass('active');
        $(this).addClass('active');

        $('.left-thumb')
            .removeClass('col-md-6')
            .removeClass('col-md-4')
            .addClass(activeClass).addClass('fadeInUp animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('fadeInUp animated');
        });
    });

    //AJAX
    $(document).on('click', '.nextImage', function () {
        showNextImage();
    });

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


    $(document).on('click', '.previousImage', function () {
        showPreviousImage();
    });

    $(document).on('click', '.thumbnail-container .panel-body', function () {
        showImageByIndex(parseInt($(this).attr('image-index')));
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
        }
        else {
            startSlideShow();
        }
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
            time = parseInt($('#range').slider("option", "value"));
        else
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
            'width': (currentScale * 100).toString() + '%',
            //'top': parseInt(parseInt($(image).css('top')) / previousScale * currentScale).toString() + 'px',
            //'left': parseInt(parseInt($(image).css('left')) / previousScale * currentScale).toString() + 'px'
        }, 1000);
    }


    //Показ следующей фотки
    function showNextImage() {
        if (currentImage + 1 != jsonImages.length) {
            if ($('.thumbnail-container:last').attr('image-id') == jsonImages[currentImage].id)
                nextPage();

            var nextImageData = jsonImages[currentImage + 1];
            currentImage++;
            changeImage(nextImageData, currentImage);
        }
        else
            stopSlideShow();
    }

    function showPreviousImage() {
        if (currentImage > 0) {
            if ($('.thumbnail-container:first').attr('image-id') == jsonImages[currentImage].id)
                previousPage();

            var previousImageData = jsonImages[currentImage - 1];
            currentImage--;
            changeImage(previousImageData, currentImage);
        }
    }

    function showImageByIndex(index) {
        changeImage(jsonImages[index], index)
    }

    function changeImage(imageObject, index) {

        $('#view-image-big img:first').attr('src', imageObject.big);
        $('#view-image').attr('src', imageObject.big);
        currentImage = index;

        updateMagnifier();
        updateArrows();
        updateBigDate();
        updateSelectedThumbnail();
        newImg = true;
    }

    function updateArrows() {
        if (currentImage == 0 || jsonImages.length == 0)
            $('.previousImage').attr('disabled', 'disabled');
        else
            $('.previousImage').removeAttr('disabled');

        if (currentImage >= jsonImages.length - 1)
            $('.nextImage').attr('disabled', 'disabled');
        else
            $('.nextImage').removeAttr('disabled');
    }

    function updateBigDate() {
        var newDate = jsonImages[currentImage].created;
        if (currentImage == 0)
            newDate = newDate;
        $('.view-date').text(newDate);
    }

    function updateSelectedThumbnail() {
        $('.thumbnail-container.current').removeClass('current');
        $('.thumbnail-container[image-id=' + jsonImages[currentImage].id + ']').addClass('current');
    }

    function updateMagnifier() {
        if (magnifierEnabled) {
            turnOffMagnifier();
            turnOnMagnifier();
        }
        else
            turnOffMagnifier();
    }
    function previousPage() {
        if (currentPage !== 1)
            moveToPage(currentPage - 1);
    }
    function nextPage() {
        if (currentPage !== pagesCount)
            moveToPage(currentPage + 1);
    }
    function moveToPage(page) {

        $('.loading').removeClass('hidden');
        $.get(
            yii.app.createUrl('cabinet/camera/get-json-images', {id: cameraId, page: page, limit: limit})
        ).done(function (images) {
            jsonImages = JSON.parse(images);

            var imageThumbnailsContainer = $('.camera-thumbnails .image-thumbnails:first');
            var html = '';

            for (var i = 0; i < jsonImages.length; i++) {
                html = html +
                    '<div class="col-md-6 thumbnail-container " image-id="' + jsonImages[i].id + '">' +
                    '<div class="panel panel-default">' +
                    '<div class="panel-body" image-index="' + i + '">' +
                    '<div class="row image-container">' +
                    '<img src="' + jsonImages[i].thumb + '" class="img-responsive cam-thumb" full-img="' + jsonImages[i].big + '">' +
                    '</div>' +
                    '</div>' +
                    '<div class="panel-footer">' +
                    '<div class="row">' +
                    '<div class="col-md-10">' + jsonImages[i].created + '</div>' +
                    '<div class="col-md-2">' +
                    '<input type="checkbox" class="pull-right thumb-check" image-id="' + jsonImages[i].id + '">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
            $(imageThumbnailsContainer).html(html);

            currentPage = page;

            updatePagination();
            $('.loading').addClass('hidden');
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

        html += '<li class="previous-page' + previousDisabled + '">' +
            '<a href="#" aria-label="Previous">' +
            '<span aria-hidden="true"><i class="fa fa-chevron-left"></i></span>' +
            '</a>' +
            '</li>';

        for (var i = Math.max(1, currentPage - 3); i <= Math.min(pagesCount, currentPage + 3); i++) {
            var activeClass = '';
            if (i == currentPage)
                activeClass = ' active';

            html += '<li page-number="' + i + '" class="go-to-page' + activeClass + '"><a href="#">' + i + '</a></li>';
        }
        html += '<li class="next-page' + nextDisabled + '">' +
            '<a href="#" aria-label="Next">' +
            '<span aria-hidden="true"><i class="fa fa-chevron-right"></i></span>' +
            '</a>' +
            '</li>';

        $(paginationContainer).html(html);
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
            },
            stop: function () {
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
                        animateAttrs.top = '0';
                    else
                        animateAttrs.top = (-$(img).height() + winHeight).toString() + 'px';
                }
                $(img).animate(animateAttrs, 100);
            }
        });
    }
</script>