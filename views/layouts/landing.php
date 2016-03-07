
<html>
<head>
    <title>Timephoto - облачный фото регистратор</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/fw/fa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/landing.css">
    <script src="/fw/jquery.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false&language=ru"></script>
</head>
<body>
    <div class="container" style="width: 1360px;">
        
<div class="row">
    <div class="col-md-12 header">
        <div class="logo"> <a href="/"><i class="fa fa-cloud"></i> TimePhoto</a></div>
        <div class="links">
                            <a href="/how" style="margin:20px;">Как это работает</a>
                            <a href="/For" style="margin:20px;">Кому это нужно</a>
                            <a href="/who" style="margin:20px;">Где это нужно</a>
                            <a href="/catalog/category/5" style="margin:20px;"> Каталог оборудования </a>
        </div>
        <div class="login pull-right" >
            <div class="signin">
                <a href="/site/login"><i class="fa fa-sign-in"></i></a>
            </div>
            <div class="links">
                <p><a href="/site/login">Вход</a></p>
                <p><a href="/site/registration">Регистрация</a></p>
            </div>
        </div>
    </div>
</div><!--
        <div class="row">
            <div class="col-md-12 header-links">
                <a href="#">Для чего это нужно</a>
                <a href="#">Как это работает</a>
                <a href="#">Подключение</a>
                <a href="#">Каталог</a>
                <a href="#">Контакты</a>
            </div>
        </div>-->
        <div class="row">
            <div class="col-md-12 first-image">
                <div class="slogan">
                    Всегда доступный <strong>облачный фоторегистратор</strong> для Ваших IP-видеокамер.

                </div>
                <div class="round-links">
                    <div>
                        <a href="/site/registration">Подключи свою камеру</a>
                    </div>
                    <div>
                        <a href="/catalog/category/5">Выбери нашу камеру</a>
                    </div>
                </div>
            </div>

            
        </div>
        <div class="row">
            <div class="text-1">
                <div class="pros">
                    <div class="col-md-12">
                        Почему фото, а не видео
                    </div>
                    <div class="div6">
                        <span>Высокое качество фото снимков  значительно лучше видео</span>
                    </div>
                    <div class="div6">
                        <span>Возможность передачи фотоснимков в местах со слабым сигналом</span>
                    </div>
                    <div class="div6">
                        <span>Быстрый и удобный просмотр архива событий за день</span>
                    </div>
                    <div class="div6">
                        <span>Хранение многолетних архивов в малом объеме памяти</span>
                    </div>
                </div>
                <div class="camera-types">
                    <div class="camera-type">
                        <a href="/catalog/category/5">  <img src="/images/landing/u121.png"></a>
                        <a href="/catalog/category/5"><p>Камеры для помещений</p></a>
                    </div>
                    <div class="camera-type">
                       <a href="/catalog/category/6">  <img src="/images/landing/u119.png"></a>
                       <a href="/catalog/category/6"> <p>Уличные камеры</p></a>
                    </div>
                    <div class="camera-type">
                       <a href="/catalog/category/8">  <img src="/images/landing/u123.png"></a>
                       <a href="/catalog/category/8"><p>3G/4G системы</p></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="image-2">
            <div class="round-links">
                <div>
                    <a href="/site/registration">Подключись бесплатно</a>
                </div>
                <div>
                    <a href="/site/registration">Смотри архив бесплатно</a>
                </div>
            </div>
            <div class="slogan">
                Легкий контроль из любой точки мира
            </div>
        </div>
        <div class="text-2">
         <div class="pros-2">
            <div class="text-element">
                Подключение камеры за 1 минуту
            </div>
            <div class="text-element">
                Оповещения по датчикам тревоги и движения
            </div>
            <div class="text-element">
                Передача прав просмотра своим пользователям
            </div>
            <div class="text-element">
                Автоматическое управление архивом
            </div>
            <div class="text-element">
                Удобные средства просмотра и аналитики
            </div>
            <div class="text-element">
                Подключение публичного доступа к камере
            </div>
         </div>
        </div>
        <div class="image-3">
            <div class="round-links">
                <div>
                    <a href="/site/registration">Подключи на свой сайт</a>
                </div>
                <div>
                    <a href="/site/registration">Продвинь свой бизнес</a>
                </div>
            </div>
            <div class="slogan">
                Подключи свой сайт к TimePhoto </br> для привлечения новых клиентов
            </div>
            <div class="monitor">
             <img src="/images/landing/image_u139.png" style="width: 518px;  height: 387px">
            </div>
            <div class="monitor-label">
            фото камера
            </div>
        </div>

        <div class="col-md-12 space">
        </div>

        <div class="row">
            <?= $this->render('@app/views/elements/footer'); ?>
        </div>

        <?php /*
        <div class="col-md-12 space">
            </div>
        <div class="map">
            <div class="gmap" id="gmap"></div>
            <div class="slogan" style="font-size:16pt;">Публичные камеры</div>
        </div>
        <div class="cameras">
            <div class="cameras-container">
                <div class="camera">
                    <img src="/images/no-image.jpg">
                </div>
                <div class="camera">
                    <img src="/images/no-image.jpg">
                </div>
                <div class="camera">
                    <img src="/images/no-image.jpg">
                </div>
            </div>
        </div>
    </div>
    */?>

    <script>
        $(document).ready(function(){
            var myLatlng = new google.maps.LatLng(55.770475, 37.612559);
            var myOptions = {
                zoom: 10,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap"), myOptions);
            overlay = new google.maps.OverlayView();
            overlay.draw = function() {};
            overlay.setMap(map);
        });
    </script>
</body>
</html>