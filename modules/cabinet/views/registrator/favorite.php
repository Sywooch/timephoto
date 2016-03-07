<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
?>
<?php Pjax::begin(); ?>
<?= Html::beginForm(['favorite'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
<?= Html::hiddenInput('id', $id) ?>
<?= Html::submitButton($f_fav ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>', []) ?>
<?= Html::endForm() ?>
<?php Pjax::end(); ?>