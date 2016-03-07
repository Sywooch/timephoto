<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 31.03.15
 * Time: 14:04
 */

use app\models\Page;

$pages = Page::findAll(['header' => '1']);
?>

<div class="row">
    <div class="col-md-12 header">
        <div class="logo"><a href="/"><i class="fa fa-cloud"></i> TimePhoto</a></div>
        <div class="links">
            <?php foreach ($pages as $page): ?>
                <a href="<?= $this->context->createUrl('/' . $page->url) ?>" style="margin:20px;"><?= $page->title ?></a>
            <?php endforeach; ?>
            <a href="<?= $this->context->createUrl('/catalog/category/5') ?>" style="margin:20px;"> Каталог оборудования </a>
        </div>
        <div class="login pull-right">
            <div class="signin">
                <a href="<?= $this->context->createUrl('/site/login') ?>"><i class="fa fa-sign-in"></i></a>
            </div>
            <div class="links">
                <p><a href="<?= $this->context->createUrl('/site/login') ?>">Вход</a></p>

                <p><a href="<?= $this->context->createUrl('/site/registration') ?>">Регистрация</a></p>
            </div>
        </div>
    </div>
</div>