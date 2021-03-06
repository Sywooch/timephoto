<?php
/* @var $this SiteController */
/* @var $model LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->name . ' - Регистрация';
$this->registerJsFile(Yii::$app->homeUrl . "template/js/login-v2.demo.min.js", ['position' => yii\web\View::POS_HEAD]);

?>
?>
<div class="login-cover">
    <div class="login-cover-image"><img src="<?php echo Yii::$app->homeUrl; ?>images/cover.jpg"
                                        data-id="login-cover-image" alt=""/></div>
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
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => true, 'enableAjaxValidation' => true,
            'action' => Url::to(['/site/registration']),
            'method' => 'post',
        ]); ?>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'login')->textInput(['class' => 'form-control input-lg', 'placeholder' => 'Введите e-mail']) ?>
        </div>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control input-lg', 'placeholder' => 'Введите пароль']) ?>
        </div>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'repassword')->passwordInput(['class' => 'form-control input-lg', 'placeholder' => 'Повторите пароль']) ?>
        </div>

        <div class="form-group m-b-20">
            <?= $form->field($model, 'reCaptcha')->widget(
                \himiklab\yii2\recaptcha\ReCaptcha::className(),
                [
                    'siteKey' => '6LdEux4TAAAAAMfYdn2Xu9RCnoFOqEtp1_UUtPYI',
                    'widgetOptions' => ['data-theme' => 'dark']
                ]
            )->label(false) ?>
        </div>

        <div class="login-buttons">
            <button type="submit" class="btn btn-success btn-block btn-lg">Зарегистрироваться</button>
        </div>

        <div class="m-t-20">
            Уже зарегистрированы? Нажмите <a href="<?= Url::to(['/site/login']); ?>">сюда</a> для входа.
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
