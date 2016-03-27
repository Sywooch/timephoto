<?php
/* @var $this SiteController */
/* @var $model LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name . ' - Вход';

$this->registerJsFile(Yii::$app->homeUrl . "template/js/login-v2.demo.min.js", ['position' => yii\web\View::POS_HEAD]);

?>
<div class="login-cover">
    <div class="login-cover-image"><img src="<?php echo Yii::$app->homeUrl; ?>images/cover.jpg" data-id="login-cover-image" alt=""/></div>
    <div class="login-cover-bg"></div>
</div>
<!-- begin login -->
<div class="login login-v2" data-pageload-addclass="animated flipInX">
    <!-- begin brand -->
    <div class="login-header">
        <div class="brand">
            <span class="logo"></span> Oblacam
            <small></small>
        </div>
        <div class="icon">
            <i class="fa fa-sign-in"></i>
        </div>
    </div>
    <!-- end brand -->
    <div class="login-content">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'username') ?>
        </div>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>

        <div class="m-t-20">
            Еще не зарегистрировались? Кликните <a href="<?= Url::to('/site/registration'); ?>">сюда</a> для регистрации. Для восстановления пароля кликните <a href="<?= Url::to('/site/request-password'); ?>">сюда</a>.
        </div>

        <div class="form-group login-buttons">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-success btn-block btn-lg', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<!-- end login -->
<!-- end page container -->

<script>
    $(document).ready(function () {

        App.init();
        LoginV2.init();

    });
</script>