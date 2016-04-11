<div id="sidebar" class="sidebar">
    <div data-scrollbar="true" data-height="100%">
        <ul class="nav nav-list cameras-nav" id="cameras-nav">
            <li class="nav-header camera-list-header">
                <!--                    <span class="with-btns"><i class="fa fa-camera"></i> Список камер</span>-->
                <div class="col-md-4 p-l-0 p-r-0 cameras-search">
                    <i class="fa fa-search cameras-search-icon"></i>
                    <input type="text" class="form-control" id="cameras-search-input">
                </div>
                <div class="col-md-8 p-l-0 p-r-0 p-t-3" id="cameras-buttons">
                    <button class="btn btn-sm pull-right" id="regroup" data-toggle="tooltip" data-placement="bottom"
                            title="Сортировать по объектам / тематике"><i class="fa fa-sort"></i></button>
                    <button class="btn btn-inverse btn-sm pull-right" id="cameras-thumbs" data-toggle="tooltip"
                            data-placement="bottom" title="Отобразать снимками"><i class="fa fa-th"></i></button>
                    <button class="btn btn-inverse btn-sm pull-right active" id="cameras-list" data-toggle="tooltip"
                            data-placement="bottom" title="Показать списком"><i class="fa fa-th-list"></i></button>
                </div>
            </li>

            <?php if (count($this->context->cameras) > 0): ?>
                <?php foreach ($this->context->cameras as $cameraNumber => $camera): ?>

                    <?php if ($cameraNumber == 0): ?>
                        <li class="has-sub camera-list-element">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-map-marker"></i>
                            <span class="group-name"><?= $camera->getLocationName() ?></span>
                        </a>
                        <ul class="sub-menu" style="display: block;">
                    <?php elseif ($this->context->cameras[$cameraNumber - 1]['location']['name'] !== $camera['location']['name']): ?>
                        </ul>
                        </li>
                        <li class="has-sub camera-list-element">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-map-marker"></i>
                            <span class="group-name"><?= $camera->getLocationName() ?></span>
                        </a>
                        <ul class="sub-menu" style="display: block;">
                    <?php endif; ?>

                    <li <?= $this->context->activeCamera == $camera->id ? 'class="active"' : '' ?>>
                        <a href="<?= $this->context->createUrl(['/cabinet/camera/', 'id' => $camera->id]) ?>">
                            <span class="camera-name"><?= $camera->getName() ?></span>
                            <img src="<?= $camera->getLastImage()->getThumbnailUrl() ?>" class="img-responsive">
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

            <?= $this->render('/registrator/_registrator_menu'); ?>

        </ul>
        <!-- end sidebar nav -->
    </div>
</div>
