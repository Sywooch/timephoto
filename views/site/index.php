<?php
/* @var $this SiteController */

$this->title = Yii::$app->name;
$this->registerJsFile("http://maps.google.com/maps/api/js?sensor=false&language=ru");
?>


<!-- begin #home -->
<div id="home" class="content has-bg home">
    <!-- begin content-bg -->
    <div class="content-bg">
        <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/2p.jpg" alt="Home"/>
        <!--        <img src="--><?php //echo Yii::$app->homeUrl; ?><!--/template/landing/img/home-bg.jpg" alt="Home" />-->
    </div>
    <!-- end content-bg -->
    <!-- begin container -->
    <div class="container home-content">
        <h3 class="bg-green-darker slogan">Фотонаблюдение важных объектов через интернет</h3>

        <div class="home-bottom">
            <div class="left-btns">
                <a href="#" class="btn btn-theme">Подключи свою<br/> камеру</a>
                <a href="#" class="btn btn-outline">Выбери нашу<br/> камеру</a>
            </div>
            <div class="right-slogan">
                Сократи риски возникновения чрезвычайных ситуаций
            </div>
        </div>
        <!--<p>
            We have created a multi-purpose theme that take the form of One-Page or Multi-Page Version.<br />
            Use our <a href="#">theme panel</a> to select your favorite theme color.
        </p>
        <a href="#" class="btn btn-theme">Explore More</a> <a href="#" class="btn btn-outline">Purchase Now</a><br />
        <br />
        or <a href="#">subscribe</a> newsletter-->
    </div>
    <!-- end container -->
</div>
<!-- end #home -->
<!-- beign #service -->
<div id="service" class="content" data-scrollview="true">
    <!-- begin container -->
    <div class="container">
        <h2 class="content-title">Приемущества</h2>
        <!-- begin row -->
        <div class="row">
            <!-- begin col-4 -->
            <div class="col-md-4 col-sm-4 col-md-offset-2 col-sm-offset-2">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Качество</h4>

                        <p class="desc">Качество фото намного выше качества видео.</p>
                    </div>
                </div>
            </div>
            <!-- end col-4 -->
            <!-- begin col-4 -->
            <div class="col-md-4 col-sm-4 col-md-offset-2 col-sm-offset-2">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-paint-brush"></i></div>
                    <div class="info">
                        <h4 class="title">Надежность</h4>

                        <p class="desc">Гарантия передачи даже при слабом сигнале.</p>
                    </div>
                </div>
            </div>
            <!-- end col-4 -->
        </div>
        <!-- end row -->
        <!-- begin row -->
        <div class="row">
            <!-- begin col-4 -->
            <div class="col-md-4 col-sm-4 col-md-offset-2 col-sm-offset-2">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-paint-brush"></i></div>
                    <div class="info">
                        <h4 class="title">Скорость</h4>

                        <p class="desc">Быстрота просмотра фотоархива.</p>
                    </div>
                </div>
            </div>
            <!-- end col-4 -->
            <!-- begin col-4 -->
            <div class="col-md-4 col-sm-4 col-md-offset-2 col-sm-offset-2">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-paint-brush"></i></div>
                    <div class="info">
                        <h4 class="title">Хранение</h4>

                        <p class="desc">Многолетние архивы в малом объеме памяти.</p>
                    </div>
                </div>
            </div>
            <!-- end col-4 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end #about -->
<!-- begin #home -->
<div id="home" class="content has-bg home">
    <!-- begin content-bg -->
    <div class="content-bg">
        <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/1s.jpg" alt="Home"/>
        <!--        <img src="--><?php //echo Yii::$app->homeUrl; ?><!--/template/landing/img/home-bg.jpg" alt="Home" />-->
    </div>
    <!-- end content-bg -->
    <!-- begin container -->
    <div class="container home-content">
        <h3 class="bg-green-darker slogan">Наблюдение из любой точки мира</h3>

        <div class="home-bottom">
            <div class="left-btns">
                <a href="#" class="btn btn-theme">Подключись<br/> бесплатно</a>
                <a href="#" class="btn btn-outline">Смотри архив<br/> бесплатно</a>
            </div>
            <div class="right-slogan">
                Контроль - функция успеха!
            </div>
        </div>
        <!--<p>
            We have created a multi-purpose theme that take the form of One-Page or Multi-Page Version.<br />
            Use our <a href="#">theme panel</a> to select your favorite theme color.
        </p>
        <a href="#" class="btn btn-theme">Explore More</a> <a href="#" class="btn btn-outline">Purchase Now</a><br />
        <br />
        or <a href="#">subscribe</a> newsletter-->
    </div>
    <!-- end container -->
</div>
<!-- end #home -->


<!-- begin #team -->
<div id="team" class="content" data-scrollview="true">
    <!-- begin container -->
    <div class="container">
        <h2 class="content-title">Применение</h2>

        <p class="content-desc">
            Вы можете использовать нашими услугами при помощи различных устройств
        </p>
        <!-- begin row -->
        <div class="row">
            <!-- begin col-4 -->
            <div class="col-md-4 col-sm-4">
                <!-- begin team -->
                <div class="team">
                    <div class="image" data-animation="true" data-animation-type="flipInX">
                        <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/iS-DS-2CD2432F-IW.png" alt="В помещении"/>
                    </div>
                    <div class="info">
                        <h3 class="name">Для помещений</h3>
                    </div>
                </div>
                <!-- end team -->
            </div>
            <div class="col-md-4 col-sm-4">
                <!-- begin team -->
                <div class="team">
                    <div class="image" data-animation="true" data-animation-type="flipInX">
                        <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/iS-DS-2CD2032-I.png" alt="На улице"/>
                    </div>
                    <div class="info">
                        <h3 class="name">Уличные камеры</h3>
                    </div>
                </div>
                <!-- end team -->
            </div>
            <div class="col-md-4 col-sm-4">
                <!-- begin team -->
                <div class="team">
                    <div class="image" data-animation="true" data-animation-type="flipInX">
                        <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/D20_router_4.png" alt="3G/4G системы"/>
                    </div>
                    <div class="info">
                        <h3 class="name">3G/4G системы</h3>
                    </div>
                </div>
                <!-- end team -->
            </div>
            <!-- end col-4 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end #team -->


<!-- beign #action-box -->
<div id="action-box" class="content has-bg" data-scrollview="true">
    <!-- begin content-bg -->
    <div class="content-bg">
        <img src="<?php echo Yii::$app->homeUrl; ?>template/landing/img/action-bg.jpg" alt="Action"/>
    </div>
    <!-- end content-bg -->
    <!-- begin container -->
    <div class="container" data-animation="true" data-animation-type="fadeInRight">
        <!-- begin row -->
        <div class="row action-box">
            <!-- begin col-9 -->
            <div class="col-md-7 col-sm-7">
                <div class="icon-large text-theme">
                    <i class="icon-trophy"></i>
                </div>
                <h3 class="m-b-40">Хотите привлечь новых клиентов к своему бизнесу?</h3>

                <div class="row">
                    <!-- begin col-4 -->
                    <div class="col-xs-12">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn">1</div>
                            <div class="info">
                                <h4 class="title">Заголовок</h4>

                                <p class="desc">Описание</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn">2</div>
                            <div class="info">
                                <h4 class="title">Заголовок</h4>

                                <p class="desc">Описание</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn">3</div>
                            <div class="info">
                                <h4 class="title">Заголовок</h4>

                                <p class="desc">Описание</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-4 -->
                </div>
            </div>
            <!-- end col-9 -->
            <!-- begin col-3 -->
            <div class="col-md-5 col-sm-5">
                <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/mac2.png" class="img-responsive">
            </div>
            <!-- end col-3 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end #action-box -->

<!-- beign #service -->
<div id="service" class="content" data-scrollview="true">
    <!-- begin container -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <img src="<?php echo Yii::$app->homeUrl; ?>images/landing/3gad.png" class="img-responsive">
            </div>
        </div>
        <!-- begin row -->
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Подключение камеры за 1 минуту</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Оповещения по датчикам тревоги и движения</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Передача прав просмотра собственным пользователям</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Автоматическое управление архивом</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Удобные средства просмотра и аналитики</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="service">
                    <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                    <div class="info">
                        <h4 class="title">Подключение публичного доступа к камере</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container -->
</div>
<!-- end #about -->

<div id="header" class="header navbar navbar-inverse">
    <!-- begin container -->
    <div class="container">
        <!-- begin navbar-header -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- end navbar-header -->
        <!-- begin navbar-collapse -->
        <div class="collapse navbar-collapse" id="header-navbar">
            <?php
            /*$this->widget('zii.widgets.CMenu', array(
                'encodeLabel' => false,
                'items' => [
                    [
                        'label' => 'Вход',
                        'url'=>['/site/login']
                    ],
                    [
                        'label' => 'Регистрация',
                        'url'=>['/site/registration']
                    ],
                    [
                        'label' => 'Каталог оборудования',
                        'url'=>['/catalog/index'],
                    ],
                    [
                        'label' => 'Публичные камеры',
                        'url'=> '#',
                    ],
                    [
                        'label' => 'Помощь',
                        'url'=> '#',
                    ],
                    [
                        'label' => 'Партнерам',
                        'url'=> '#',
                    ],
                    [
                        'label' => 'Контакты',
                        'url'=> '#',
                    ],

                ],
                'htmlOptions' => ['class' => 'nav navbar-nav landing-nav']
            ));*/
            ?>
        </div>
        <!-- end navbar-collapse -->
    </div>
    <!-- end container -->
</div>
<!-- end #header -->

<!-- begin #work -->
<div id="work" class="content" data-scrollview="true">
    <!-- begin container -->
    <div class="container" data-animation="true" data-animation-type="fadeInDown">
        <h2 class="content-title">Публичные камеры</h2>
        <!-- begin row -->
        <div class="row row-space-10">
            <!-- begin col-3 -->
            <div class="col-md-4 col-sm-6">
                <!-- begin work -->
                <div class="work">
                    <div class="image">
                        <a href="#"><img src="<?php echo Yii::$app->homeUrl; ?>images/1.jpg" alt="Work 1"/></a>
                    </div>
                    <div class="desc">
                        <span class="desc-title">Aliquam molestie</span>
                        <span class="desc-text">Lorem ipsum dolor sit amet</span>
                    </div>
                </div>
                <!-- end work -->
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-4 col-sm-6">
                <!-- begin work -->
                <div class="work">
                    <div class="image">
                        <a href="#"><img src="<?php echo Yii::$app->homeUrl; ?>images/2.jpg" alt="Work 1"/></a>
                    </div>
                    <div class="desc">
                        <span class="desc-title">Quisque at pulvinar lacus</span>
                        <span class="desc-text">Lorem ipsum dolor sit amet</span>
                    </div>
                </div>
                <!-- end work -->
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-4 col-sm-6">
                <!-- begin work -->
                <div class="work">
                    <div class="image">
                        <a href="#"><img src="<?php echo Yii::$app->homeUrl; ?>images/3.jpg" alt="Work 1"/></a>
                    </div>
                    <div class="desc">
                        <span class="desc-title">Vestibulum et erat ornare</span>
                        <span class="desc-text">Lorem ipsum dolor sit amet</span>
                    </div>
                </div>
                <!-- end work -->
            </div>
            <!-- end col-3 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end #work -->

<!-- begin #milestone -->
<div id="milestone" class="content bg-black-darker has-bg" data-scrollview="true">
    <!-- begin content-bg -->
    <div class="content-bg">
        <img src="<?php echo Yii::$app->homeUrl; ?>template/landing/img/milestone-bg.jpg" alt="Milestone"/>
    </div>
    <!-- end content-bg -->
    <!-- begin container -->
    <div class="container">
        <!-- begin row -->
        <div class="row">
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-3 milestone-col">
                <div class="milestone">
                    <div class="number" data-animation="true" data-animation-type="number" data-final-number="1292">1,292</div>
                    <div class="title">Пользователей</div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-3 milestone-col">
                <div class="milestone">
                    <div class="number" data-animation="true" data-animation-type="number" data-final-number="9039">9,039</div>
                    <div class="title">Клиентов</div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-3 milestone-col">
                <div class="milestone">
                    <div class="number" data-animation="true" data-animation-type="number" data-final-number="89291">89,291</div>
                    <div class="title">Изображений</div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-3 milestone-col">
                <div class="milestone">
                    <div class="number" data-animation="true" data-animation-type="number" data-final-number="129">129</div>
                    <div class="title">Камер</div>
                </div>
            </div>
            <!-- end col-3 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end #milestone -->

<!-- begin #pricing -->
<div id="pricing" class="content" data-scrollview="true">
    <!-- begin container -->
    <div class="container">
        <h2 class="content-title">Тарифы</h2>

        <p class="content-desc">
        </p>
        <!-- begin pricing-table -->
        <ul class="pricing-table col-4">
            <li data-animation="true" data-animation-type="fadeInUp">
                <div class="pricing-container">
                    <h3>Starter</h3>

                    <div class="price">
                        <div class="price-figure">
                            <span class="price-number">FREE</span>
                        </div>
                    </div>
                    <ul class="features">
                        <li>1GB Storage</li>
                        <li>2 Clients</li>
                        <li>5 Active Projects</li>
                        <li>5 Colors</li>
                        <li>Free Goodies</li>
                        <li>24/7 Email support</li>
                    </ul>
                    <div class="footer">
                        <a href="#" class="btn btn-inverse btn-block">Buy Now</a>
                    </div>
                </div>
            </li>
            <li data-animation="true" data-animation-type="fadeInUp">
                <div class="pricing-container">
                    <h3>Basic</h3>

                    <div class="price">
                        <div class="price-figure">
                            <span class="price-number">$9.99</span>
                            <span class="price-tenure">per month</span>
                        </div>
                    </div>
                    <ul class="features">
                        <li>2GB Storage</li>
                        <li>5 Clients</li>
                        <li>10 Active Projects</li>
                        <li>10 Colors</li>
                        <li>Free Goodies</li>
                        <li>24/7 Email support</li>
                    </ul>
                    <div class="footer">
                        <a href="#" class="btn btn-inverse btn-block">Buy Now</a>
                    </div>
                </div>
            </li>
            <li class="highlight" data-animation="true" data-animation-type="fadeInUp">
                <div class="pricing-container">
                    <h3>Premium</h3>

                    <div class="price">
                        <div class="price-figure">
                            <span class="price-number">$19.99</span>
                            <span class="price-tenure">per month</span>
                        </div>
                    </div>
                    <ul class="features">
                        <li>5GB Storage</li>
                        <li>10 Clients</li>
                        <li>20 Active Projects</li>
                        <li>20 Colors</li>
                        <li>Free Goodies</li>
                        <li>24/7 Email support</li>
                    </ul>
                    <div class="footer">
                        <a href="#" class="btn btn-theme btn-block">Buy Now</a>
                    </div>
                </div>
            </li>
            <li data-animation="true" data-animation-type="fadeInUp">
                <div class="pricing-container">
                    <h3>Lifetime</h3>

                    <div class="price">
                        <div class="price-figure">
                            <span class="price-number">$999</span>
                        </div>
                    </div>
                    <ul class="features">
                        <li>Unlimited Storage</li>
                        <li>Unlimited Clients</li>
                        <li>Unlimited Projects</li>
                        <li>Unlimited Colors</li>
                        <li>Free Goodies</li>
                        <li>24/7 Email support</li>
                    </ul>
                    <div class="footer">
                        <a href="#" class="btn btn-inverse btn-block">Buy Now</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <!-- end container -->
</div>
<!-- end #pricing -->


<div id="gmap" class="gmap-landing"></div>


<script>
    $(document).ready(function () {

        initialize();
        App.init();

    });
    function initialize() {
        var myLatlng = new google.maps.LatLng(55.770475, 37.612559);
        var myOptions = {
            zoom: 10, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("gmap"), myOptions);
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {
        };
        overlay.setMap(map);
    }

</script>