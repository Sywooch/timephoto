<?php
/* @var $this DefaultController */

$i = 1;

?>
<h2 class="page-header">Кабинет клиента</h2>
<ul>
    <li><a href="<?= $this->context->createUrl(['/cabinet/camera/']) ?>">Камеры</a></li>
    <li><a href="<?= $this->context->createUrl(['/cabinet/camera/add']) ?>">Добавить новую камеру</a></li>
</ul>