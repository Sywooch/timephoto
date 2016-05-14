<?php
/* @var $this CameraController */

$i = 1;

?>
<?php $this->beginContent('@cabinet/views/layouts/main.php'); ?>
    <div id="sidebar" class="sidebar">
        <div data-scrollbar="true" data-height="100%">
            <ul class="nav">
                <li class="nav-header"><i class="fa fa-cogs"></i> Настройка камер</li>

                <?php if (count($this->context->cameras) > 0): ?>

                    <?php foreach ($this->context->cameras as $cameraNumber => $camera): ?>
                        <?php if ($cameraNumber == 0): ?>
                            <li class="has-sub">
                            <a href="javascript:;">
                                <b class="caret pull-right"></b>
                                <i class="fa fa-camera"></i>
                                <span><?= $camera->getCategoryAndLocation() ?></span>
                            </a>
                            <ul class="sub-menu" style="display: block;">
                        <?php elseif ($this->context->cameras[$cameraNumber - 1]['location']['name'] !== $camera['location']['name']): ?>
                            </ul>
                            </li>
                            <li class="has-sub">
                            <a href="javascript:;">
                                <b class="caret pull-right"></b>
                                <i class="fa fa-camera"></i>
                                <span><?= $camera->getCategoryAndLocation() ?></span>
                            </a>
                            <ul class="sub-menu" style="display: block;">
                        <?php endif; ?>
                        <li <?= $this->context->activeCamera == $camera->id ? 'class="active"' : '' ?>>
                            <a href="<?= $this->context->createUrl(['/cabinet/camera/edit/', 'id' => $camera->id]) ?>">
                                <?= $camera->getName(); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    </ul>
                    </li>
                <?php else: ?>
                    <li class="text-center text-warning">
                        У вас пока нет камер
                    </li>
                <?php endif; ?>

            </ul>
            <!-- end sidebar nav -->
        </div>
    </div>
    <!-- end sidebar scrollbar -->
    <div id="content" class="content">
        <?php echo $content; ?>
        <?php if (!empty($this->context->footer)): ?>
            <footer class="camera-footer">
                <?= $this->context->footer ?>
            </footer>
        <?php endif; ?>
    </div>
<?php $this->endContent(); ?>