<?php
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$yii = $this->assetBundles['yii\web\YiiAsset']->baseUrl;

unset(
  $this->assetBundles['yii\web\JqueryAsset'],
  $this->assetBundles['yii\bootstrap\BootstrapAsset'],
  $this->assetBundles['yii\web\YiiAsset']
);

$i = 1;

$js_global_variables = '
$.ajaxSetup({
        data: ' . \yii\helpers\Json::encode([
            \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
        ]) . '
    });' . PHP_EOL;
$this->registerJs($js_global_variables, yii\web\View::POS_HEAD, 'js_global_variables');

?>
<?php $this->beginPage() ?>
<?php /* @var $this Controller */ ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>css/bootstrap-work.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/bs-dtp/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/fa/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/sweetalert/sweet-alert.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>fw/jqueryui/jquery-ui.min.css">

        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>template/plugins/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>template/css/animate.min.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>template/css/style-responsive.min.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>template/css/theme/default.css" rel="stylesheet" id="theme"/>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl; ?>template/css/style2.css">

        <script src="https://code.jquery.com/jquery-1.11.2.js"></script>
        <?php /*<script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery.js"></script>*/ ?>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/bs-dtp/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/bs-dtp/js/locales/bootstrap-datetimepicker.ru.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/sweetalert/sweet-alert.min.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>js/helper.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>js/messages.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>fw/jqueryui/jquery-ui.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>template/plugins/slimscroll/jquery.slimscroll.js"></script>
        <script src="<?php echo Yii::$app->homeUrl; ?>template/plugins/jquery-cookie/jquery.cookie.js"></script>

        <script src="<?php echo Yii::$app->homeUrl; ?>template/js/apps.js"></script>
        <script src="<?php echo $yii; ?>/yii.js"></script>

        <?php $this->head() ?>

        <title><?php //echo yii\helpers\Html::encode($this->pageTitle); ?></title>
    </head>

    <body>

    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->

    <!-- begin #page-container -->
    <?php echo $content; ?>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>