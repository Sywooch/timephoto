<?php
/* @var $this CameraController */

use app\models\Camera;
use app\models\Registrator;
use app\models\Location;
use yii\bootstrap\Nav;
use yii\helpers\Url;

$countCameras = Camera::find()->where(['user_id' => Yii::$app->user->identity->userId, 'deleted' => 0])->count();
$countRegistrator = Registrator::find()->where(['deleted'=>0,'user_id' => Yii::$app->user->identity->userId])->count();

$i = 1;

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
  <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                  data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
            <?php if (!Yii::$app->user->identity->siteLogoHidden()): ?>
              <div class="site-logo"></div>

            <?php endif; ?>
            <?php if ( Yii::$app->user->identity->hasCustomLogo() && Yii::$app->user->identity->siteLogoHidden() ): ?>
              <div class="custom-logo">
                <img src="<?= Yii::$app->homeUrl ?>uploads/custom_logos/<?= Yii::$app->user->identity->custom_logo ?>">
              </div>
            <?php endif; ?>
          </a>
          <a href="javascript:;" class="custom-sidebar-toggle" data-click="sidebar-minify">
            <i class="fa fa-bars"></i>
          </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

          <?php /*
                    echo Nav::widget([
                      'options' => ['class' => 'nav navbar-nav'],
                      'items' => [
                        [
                          'label' => 'Камеры<span class="label">' . Camera::find()->where(['user_id' => Yii::$app->user->identity->userId, 'deleted' => 0])->count() . '</span>',
                          'url' => ['/cabinet/camera/index'],
                          'encode' => false,
                        ],
                        [
                          'label' => 'Регистраторы<span class="label">' . Registrator::find()->where(['deleted'=>0,'user_id' => Yii::$app->user->identity->userId])->count() . '</span>',
                          'url' => Yii::$app->user->identity->active ? ['/cabinet/registrator/index'] : ['#'],
                          'linkOptions' => Yii::$app->user->identity->active ? [] : ['onclick' => 'youAreBlocked()', 'style' => 'cursor: default;'],
                          'encode' => false,
                        ],
                        [
                          'label' => 'Объекты<span class="label">' . Location::find()->where(['user_id' => Yii::$app->user->identity->userId])->count() . '</span>',
                          'url' => Yii::$app->user->identity->active ? ['/cabinet/object/index'] : ['#'],
                          'linkOptions' => Yii::$app->user->identity->active ? [] : ['onclick' => 'youAreBlocked()', 'style' => 'cursor: default;'],
                          'encode' => false,
                        ],
                      ]
                    ]);
*/
          ?>
          <ul class="nav navbar-nav main-nav">
            <li class="dropdown">
              <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
                Камеры <span
                  class="label"><?php echo $countCameras; ?></span>
                <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu animated fadeInLeft">
                <li class="arrow"></li>
                <li>
                  <a href="<?php echo URL::to(['/cabinet/camera/index']) ?>">
                    <i class="fa fa-camera"></i> Мои камеры
                  </a>
                </li>

                <?php if(Yii::$app->user->identity->role == 'USER'):?>
                <li>
                  <?php if (!Yii::$app->user->identity->canAddCamera()): ?>
                    <a onclick="noMoreCameras()"><i class="fa fa-plus-circle"></i> <span>Добавить камеру</span></a>
                  <?php elseif (!Yii::$app->user->identity->active): ?>
                    <a onclick="youAreBlocked()"><i class="fa fa-plus-circle"></i> <span>Добавить камеру</span></a>
                  <?php else: ?>
                    <a href="<?= $this->context->createUrl(['/cabinet/camera/add']); ?>"><i class="fa fa-plus-circle"></i> <span>Добавить камеру</span></a>
                  <?php endif; ?>
                </li>
                <?php endif;?>

                <?php if ($countCameras): ?>
                  <?php if(Yii::$app->user->identity->role == 'USER'):?>
                  <li>
                    <a href="<?php echo URL::to(['/cabinet/camera/edit', 'id'=>0]) ?>">
                      <i class="fa fa-cog"></i> Настройки камер
                    </a>
                  </li>
                  <?php endif;?>
                <?php endif; ?>

                <?php if (Yii::$app->user->identity->canEdit()): ?>
                <li>
                  <a href="<?php echo URL::to(['/cabinet/camera/manage']) ?>">
                    <i class="fa fa-edit"></i> Управление архивом
                  </a>
                </li>
                <?php endif; ?>
              </ul>
            </li>

            <li class="dropdown">
              <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
                Регистраторы  <span class="label"><?= $countRegistrator ?></span> <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu animated fadeInLeft">
                <li class="arrow"></li>
                <li>
                  <a href="<?php echo URL::to(['/cabinet/registrator/index'])?>">
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
                    <a href="<?= $this->context->createUrl(['/cabinet/registrator/add']); ?>"><i class="fa fa-plus-circle"></i> <span>Добавить регистратор</span></a>
                  <?php endif; ?>
                </li>
                <?php endif;?>

                <?php if ($countRegistrator): ?>
                  <?php if(Yii::$app->user->identity->role == 'USER'):?>
                    <li>
                      <a href="<?php echo URL::to(['/cabinet/registrator/edit', 'id'=>0]) ?>">
                        <i class="fa fa-cog"></i> Настройки регистраторов
                      </a>
                    </li>
                  <?php endif;?>
                <?php endif; ?>

              </ul>
            </li>

            <li>
              <a href="<?php echo URL::to(['/cabinet/object/index'])?>">
                Объекты <span class="label"><?php echo Location::find()->where(['user_id' => Yii::$app->user->identity->userId])->count() ?></span>
              </a>
            </li>


          </ul>

          <?php if(Yii::$app->user->identity->role == 'USER'):?>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown navbar-user">
              <a href="javascript:;" class="dropdown-toggle user-info" data-toggle="dropdown">
                <?= Yii::$app->user->identity->active ? '' : ' <span class="text-danger"><i class="fa fa-lock"></i> Заблокирован</span>' ?>
                клиент <span class="badge badge-inverse badge-nick"><?= Yii::$app->user->identity->getName() ?></span> <i
                  class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu animated fadeInLeft">
                <li class="arrow"></li>
                <li><a href="<?= $this->context->createUrl(['/cabinet/user/account']); ?>"><i class="fa fa-cog"></i>
                    Настройки</a></li>
                <li class="divider"></li>
                <li><a href="<?= $this->context->createUrl(['/site/logout']); ?>"><i class="fa fa-sign-out"></i>
                    Выйти</a></li>
              </ul>
            </li>
          </ul>
          <?php endif;?>

          <?php if ( Yii::$app->user->identity->hasCustomLogo() && !Yii::$app->user->identity->siteLogoHidden() ): ?>
            <div class="custom-logo pull-right m-r-30">
              <img src="<?= Yii::$app->homeUrl ?>uploads/custom_logos/<?= Yii::$app->user->identity->custom_logo ?>">
            </div>
          <?php endif; ?>

        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>
    <?php echo $content; ?>
  </div>
<?php $this->endContent(); ?>