<?php

use app\models\Location;
use yii\helpers\Url;

$countCameras = count(\Yii::$app->controller->cameras);

?>
<ul class="nav navbar-nav main-nav">
    <li class="dropdown">
        <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
            Камеры <span
                class="label"><?php echo  $countCameras;?></span>
            <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu animated fadeInLeft">
            <li class="arrow"></li>
            <li>
                <a href="<?php echo URL::to(['/public_cabinet/camera/index']) ?>">
                    <i class="fa fa-camera"></i> Мои камеры
                </a>
            </li>


            <?php if ($countCameras): ?>
            <?php endif; ?>

            <?php if (Yii::$app->user->identity->canEdit()): ?>
                <li>
                    <a href="<?php echo URL::to(['/public_cabinet/camera/manage']) ?>">
                        <i class="fa fa-edit"></i> Управление архивом
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </li>

    <?php /*?>
            <li class="dropdown">
              <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
                Регистраторы  <span class="label"><?= $countRegistrator ?></span> <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu animated fadeInLeft">
                <li class="arrow"></li>
                <li>
                  <a href="<?php echo URL::to(['/public_cabinet/registrator/index'])?>">
                    <i class="fa fa-camera"></i> Мои регистраторы
                  </a>
                </li>

                <?php if(Yii::$app->user->identity->role == 'USER'):?>
                <li>
                  <?php if (!Yii::$app->user->identity->canAddCamera()): ?>
                    <a onclick="noMoreCameras()"><i class="fa fa-plus-circle"></i> <span>Добавить регистратор</span></a>
                  <?php elseif (!Yii::$app->user->identity->active): ?>
                    <a onclick="youAreBlocked()"><i class="fa fa-plus-circle"></i> <span>Добавить регистратор</span></a>
                  <?php else: ?>
                    <a href="<?= $this->context->createUrl(['/public_cabinet/registrator/add']); ?>"><i class="fa fa-plus-circle"></i> <span>Добавить регистратор</span></a>
                  <?php endif; ?>
                </li>
                <?php endif;?>

                <?php if ($countRegistrator): ?>
                  <?php if(Yii::$app->user->identity->role == 'USER'):?>
                    <li>
                      <a href="<?php echo URL::to(['/public_cabinet/registrator/edit', 'id'=>0]) ?>">
                        <i class="fa fa-cog"></i> Настройки регистраторов
                      </a>
                    </li>
                  <?php endif;?>
                <?php endif; ?>

              </ul>
            </li>
            <?php*/ ?>

    <li>
        <a href="<?php echo URL::to(['/public_cabinet/object/index']) ?>">
            Объекты <span
                class="label"><?php echo Location::find()->where(['user_id' => Yii::$app->user->identity->userId])->count() ?></span>
        </a>
    </li>


</ul>