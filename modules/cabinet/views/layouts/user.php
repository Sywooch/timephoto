<?php
/* @var $this UserController */

$i = 1;

?>
<?php $this->beginContent('@cabinet/views/layouts/main.php'); ?>
    <div id="sidebar" class="sidebar">
        <div data-scrollbar="true" data-height="100%">
            <ul class="nav">
                <li class="nav-header"><i class="fa fa-cog"></i> Настройки клиента</li>
                <li>
                    <a href="<?= $this->context->createUrl(['/cabinet/user/index']) ?>" class="">
                        <span>Счет</span>
                    </a>
                    <a href="<?= $this->context->createUrl(['/cabinet/user/services']) ?>" class="">
                        <span>Услуги</span>
                    </a>
                    <a href="<?= $this->context->createUrl(['/cabinet/user/manager']) ?>" class="">
                        <span>Пользователи</span>
                    </a>
            </ul>
            <!-- end sidebar nav -->
        </div>
    </div>
    <div id="content" class="content">
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>