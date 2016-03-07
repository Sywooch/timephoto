<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 31.03.15
 * Time: 14:05
 */

use app\models\Page;

$pages = Page::findAll(['footer' => '1']);

$ulNumber = count($pages) % 3 !== 0 ? intval(count($pages) / 3) + 2 : intval(count($pages) / 3) + 1;
$percent = round(1360 / $ulNumber) - 10;
?>

<div class="text-3">
    <ul style="width: <?= $percent; ?>px;">
        <li><a href="<?= $this->context->createUrl('/site/login') ?>">Вход клиентам</a></li>
        <li><a href="<?= $this->context->createUrl('/site/registration') ?>">Регистрация</a></li>
        <li><a href="<?= $this->context->createUrl('/catalog/category/5') ?>">Каталог оборудования</a></li>
    </ul>
    <?php foreach ($pages as $index => $page): ?>
    <?php if ($index == 0): ?>
    <ul style="width: <?= $percent; ?>px;">
        <?php elseif (($index > 2) && (($index) % 3 == 0)): ?>
    </ul>
    <ul style="width: <?= $percent; ?>px;">
        <?php endif; ?>
        <li><a href="<?= $this->context->createUrl('/' . $page->url) ?>"><?= $page->title ?></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="comp">
        ЗАО "Солнечные системы" 2015
    </div>
</div>