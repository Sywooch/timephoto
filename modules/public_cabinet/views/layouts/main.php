<?php
/* @var $this CameraController */

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div id="page-container" class="public-cabinet fade page-sidebar-fixed page-header-fixed">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <?php /*if (!Yii::$app->user->identity->siteLogoHidden()): ?>
                            <div class="site-logo"></div>

                        <?php endif; ?>
                        <?php if (Yii::$app->user->identity->hasCustomLogo() && Yii::$app->user->identity->siteLogoHidden()): ?>
                            <div class="custom-logo">
                                <img
                                    src="<?= Yii::$app->homeUrl ?>uploads/custom_logos/<?= Yii::$app->user->identity->custom_logo ?>">
                            </div>
                        <?php endif; */?>
                        <div class="site-logo"></div>
                    </a>

                </div>

            </div>
            <!-- /.container-fluid -->
        </nav>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>