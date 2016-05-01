<?php
/* @var $this CameraController */

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
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
                    <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
                        <?php /* if (!Yii::$app->user->identity->siteLogoHidden()): */?>
                            <div class="site-logo"></div>
                        <?php /* endif; */ ?>
                        <?php /* if (Yii::$app->user->identity->hasCustomLogo() && Yii::$app->user->identity->siteLogoHidden()): ?>
                            <div class="custom-logo">
                                <img
                                    src="<?= Yii::$app->homeUrl ?>uploads/custom_logos/<?= Yii::$app->user->identity->custom_logo ?>">
                            </div>
                        <?php endif; */?>
                    </a>
                    <a href="javascript:;" class="custom-sidebar-toggle" data-click="sidebar-minify">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <?php if (isset($this->blocks['menu_bar'])): ?>
                        <?= $this->blocks['menu_bar'] ?>
                    <?php endif; ?>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown navbar-user">
                            <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
                                <?= Yii::$app->user->identity->active ? '' : ' <span class="text-danger"><i class="fa fa-lock"></i> Заблокирован</span>' ?>
                                <?php if (Yii::$app->user->identity->role == 'USER'): ?>
                                    клиент
                                <?php else: ?>
                                    пользователь
                                <?php endif; ?>
                                <span
                                    class="badge badge-inverse badge-nick"><?= Yii::$app->user->identity->getName() ?></span>
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <?php if (Yii::$app->user->identity->role == 'USER'): ?>
                                    <li class="arrow"></li>
                                    <li><a href="<?= $this->context->createUrl(['/cabinet/user/account']); ?>"><i
                                                class="fa fa-cog"></i>Настройки</a></li>
                                    <li class="divider"></li>
                                <?php endif; ?>
                                <li><a href="<?= $this->context->createUrl(['/site/logout']); ?>"><i
                                            class="fa fa-sign-out"></i>Выйти</a></li>
                            </ul>
                        </li>
                    </ul>

                    <?php  if (Yii::$app->user->identity->hasCustomLogo() /* &&  !Yii::$app->user->identity->siteLogoHidden() */ ): ?>
                        <div class="custom-logo pull-right m-r-30">
                            <img
                                src="<?= Yii::$app->homeUrl ?>uploads/custom_logos/<?= Yii::$app->user->identity->custom_logo ?>">
                        </div>
                    <?php endif; ?>

                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>