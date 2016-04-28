<?php
/* @var $this CameraController */

use yii\widgets\Menu;

$this->registerCssFile(Yii::$app->homeUrl . "css/admin.css");
?>
<?php $this->beginContent('@app/views/layouts/admin.php'); ?>
    <div id="page-container" class="fade page-header-fixed admin-container">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <div class="site-logo">
                            <img src="<?= Yii::$app->homeUrl ?>images/oblacam.jpg">
                        </div>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?php /*
                $this->widget('zii.widgets.CMenu', array(
                    'encodeLabel' => false,
                    'items' => [
                        [
                            'label' => 'Клиенты',
                            'url'=>['/admin/client/index']
                        ],
                        [
                            'label' => 'Тарифы',
                            'url'=>['/admin/tariff/index']
                        ],
                        [
                            'label' => 'Магазин',
                            'url'=>['/admin/device/index']
                        ],
                        [
                            'label' => 'Контент',
                            'url'=>['/admin/page/index']
                        ],

                    ],
                    'htmlOptions' => ['class' => 'nav navbar-nav']
                ));
                */ ?>
                    <?php
                    echo Menu::widget([
                        'items' => [
                            [
                                'label' => 'Клиенты',
                                'url' => ['/admin/client/index']
                            ],
                            [
                                'label' => 'Тарифы',
                                'url' => ['/admin/tariff/index']
                            ],
                            [
                                'label' => 'Магазин',
                                'url' => ['/admin/device/index']
                            ],
                            [
                                'label' => 'Контент',
                                'url' => ['/admin/page/index']
                            ],
                            [
                                'label' => 'Настройки',
                                'url' => ['/admin/settings/index']
                            ],


                        ],
                        'options' => ['class' => 'nav navbar-nav'],
                    ]); ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <span
                                    class="badge badge-inverse badge-nick"><?= Yii::$app->user->identity->getName() ?></span>
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <li><a href="<?= $this->context->createUrl('/site/logout'); ?>"><i
                                            class="fa fa-sign-out"></i> Выйти</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>