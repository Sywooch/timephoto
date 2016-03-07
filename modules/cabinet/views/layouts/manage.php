<?php
/* @var $this CameraController */

$i = 1;

?>
<?php $this->beginContent('@cabinet/views/layouts/main.php'); ?>
    <div id="sidebar" class="sidebar">
        <div data-scrollbar="true" data-height="100%">
            <ul class="nav">
                <li class="nav-header"><i class="fa fa-cogs"></i> <span>Управление архивом камер</span></li>
                <li <?= $this->context->activeCategory === null && $this->context->activeLocation === null ? 'class="active"' : '' ?>>
                    <a href="<?= $this->context->createUrl(['/cabinet/camera/manage/']) ?>">
                        <i class="fa fa-arrows-alt"></i>
                        <span>Все камеры</span>
                    </a>
                </li>
                <?php if (count($this->context->cameras) > 0): ?>
                    <?php foreach ($this->context->cameras as $cameraNumber => $camera): ?>
                        <?php if ($cameraNumber == 0): ?>
                            <li class="has-sub">
                            <a href="javascript:;">
                                <b class="caret pull-right"></b>
                                <i class="fa fa-camera"></i>
                                <span><?= $camera->getCategoryName() ?></span>
                            </a>
                            <ul class="sub-menu" style="display: block;">
                            <li <?= $this->context->activeCategory == $camera->camera_category_id && $this->context->activeLocation === null ? 'class="active"' : '' ?>>
                                <a href="<?= $this->context->createUrl(['/cabinet/camera/manage/', 'category' => $camera->camera_category_id]) ?>">
                                    Все камеры в категории
                                </a>
                            </li>
                        <?php elseif ($this->context->cameras[$cameraNumber - 1]->camera_category_id !== $camera->camera_category_id): ?>
                            </ul>
                            </li>
                            <li class="has-sub">
                            <a href="javascript:;">
                                <b class="caret pull-right"></b>
                                <i class="fa fa-camera"></i>
                                <span><?= $camera->getCategoryName() ?></span>
                            </a>
                            <ul class="sub-menu" style="display: block;">
                            <li <?= $this->context->activeCategory == $camera->camera_category_id && $this->context->activeLocation === null ? 'class="active"' : '' ?>>
                                <a href="<?= $this->context->createUrl(['/cabinet/camera/manage/', 'category' => $camera->camera_category_id]) ?>">
                                    Все камеры в категории
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($cameraNumber == 0): ?>
                            <li <?= $this->context->activeLocation == $camera->location_id ? 'class="active"' : '' ?>>
                                <a href="<?= $this->context->createUrl(['/cabinet/camera/manage/', 'location' => $camera->location_id, 'category' => $camera->camera_category_id]) ?>">
                                    <?= $camera->getLocationName() ?>
                                </a>
                            </li>
                        <?php elseif ($this->context->cameras[$cameraNumber - 1]->location_id !== $camera->location_id || $this->context->cameras[$cameraNumber - 1]->camera_category_id !== $camera->camera_category_id) : ?>
                            <li <?= $this->context->activeLocation == $camera->location_id ? 'class="active"' : '' ?>>
                                <a href="<?= $this->context->createUrl(['/cabinet/camera/manage/', 'location' => $camera->location_id, 'category' => $camera->camera_category_id]) ?>">
                                    <?= $camera->getLocationName() ?>
                                </a>
                            </li>
                        <?php endif; ?>
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