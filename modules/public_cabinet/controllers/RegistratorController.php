<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\public_cabinet\controllers;

use app\models\Camera;
use app\models\Image;
use app\models\Location;
use app\models\Registrator;
use Yii;
use yii\helpers\ArrayHelper;

/* @property Registrator[] $Registrators */
class RegistratorController extends \app\modules\public_cabinet\components\CabinetController
{
    public $layout = 'registrator';
    public $activeCamera = null;
    public $activeLocation = null;
    public $activeCategory = null;
    public $cameras = [];
    public $registrators = [];
    public $jsonCameras;
    public $jsonRegistrators;

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $cameras = Camera::find()->where('user_id = :user_id AND deleted = :deleted AND NOT registrator_id = ""', [
            ':user_id' => Yii::$app->user->identity->userId,
            ':deleted' => 0
        ])->orderBy(['camera_category_id' => SORT_ASC, 'location_id' => SORT_ASC])->all();

        $this->cameras = $cameras;

        $camerasArray = [];
        foreach ($cameras as $camera) {
            $cameraElement = $camera->attributes;
            $cameraElement['thumb'] = $camera->getLastImage()->getThumbnailUrl();
            $cameraElement['category_name'] = $camera->getCategoryName();
            $cameraElement['location_name'] = $camera->getLocationName();
            $cameraElement['last_image_date'] = date('d-m-y H:i', strtotime($camera->getLastImage()->created));
            $cameraElement['totalSize'] = $camera->getTotalSize();
            $cameraElement['occupiedPercent'] = $camera->getOccupiedPercent();
            $cameraElement['quantity'] = $camera->getCapturesQuantity();

            $cameraElement['canEdit'] = Yii::$app->user->identity->canEdit();

            $cameraElement['href'] = $this->createUrl(['/public_cabinet/camera', 'id' => $camera->id]);
            $cameraElement['manage_href'] = $this->createUrl('/public_cabinet/camera/manage');
            $cameraElement['edit_href'] = $this->createUrl(['/public_cabinet/camera/edit', 'id' => $camera->id]);
            $camerasArray[] = $cameraElement;
        }

        $this->jsonCameras = json_encode($camerasArray);

        list($this->registrators, $this->jsonRegistrators) = Registrator::getRegistrators();

        return true;
    }

    public function actionIndex($id = null, $view = 'thumbs', $imageId = null, $type = 'all', $date = null, $currentPage = null, $limit = 12)
    {

        if ($id === null) {

            /*$cameras = Camera::find()->where('user_id = :user_id AND deleted = :deleted AND NOT registrator_id = ""', [
              ':user_id' => Yii::$app->user->identity->userId,
              ':deleted' => 0
            ])->all();*/

            $registrators = Registrator::find()
                ->where('user_id = :userId AND deleted = :deleted', [':userId' => Yii::$app->user->identity->userId, ':deleted' => 0])
                ->orderBy(['location_id' => SORT_ASC, 'id' => SORT_ASC])
                ->all();

            return $this->render('registrators', compact('registrators'));

        } else {

            $cameras = Camera::find()->where('user_id = :user_id AND deleted = :deleted AND registrator_id = :registrator_id', [
                ':user_id' => Yii::$app->user->identity->userId,
                ':deleted' => 0,
                ':registrator_id' => $id,
            ])->all();

            return $this->render('index', compact('cameras', 'id'));

        }

        /*if ($id === null) {
            $cameras = Registrator::findAll(['user_id' => Yii::$app->user->identity->userId, 'registrator_id' => $id, 'deleted' => 0]);

            return $this->render('index', compact('cameras'));
        } else {
            return $this->actionPhotos($id, $view, $imageId, $type, $date, $currentPage, $limit);
        }*/

    }

    /*public function actionPhotos($id, $view = 'thumbs', $imageId = null, $type = 'all', $date = null, $currentPage = null, $limit = 12)
    {
        $favFilter = array();

        $this->activeCamera = $id;
        $camera = Registrator::findOne(['id' => $id, 'deleted' => 0]);

        if ($camera) {
            $imagesCriteria = [
              'deleted' => 0,
              'camera_id' => $id,
            ];

            $typeFilter = null;
            $dateFilter = null;
            $favFilter = null;

            $filterTypes = $camera->getFilterTypes();

            if ($type !== 'all') {
                foreach (explode(',', $type) as $typeValue) {
                    switch ($typeValue) {
                        case 'alert':
                            $typeFilter[] = 'ALERT';
                            break;
                        case 'move':
                            $typeFilter[] = 'MOVE';
                            break;
                        case 'schedule':
                            $typeFilter[] = 'SCHEDULE';
                            break;
                        case 'favorite':
                            $favFilter = [1];
                            break;
                        default:
                            $typeFilter = null;
                    }
                }
            }

            if ($date !== null) {
                $dateFilter = $date;
            }

            if ($favFilter) {
                $imagesCriteria['f_fav'] = $favFilter;
            }

            if ($typeFilter) {
                $imagesCriteria['type'] = $typeFilter;
            }

            if ($dateFilter) {
                $imagesCriteria['DATE(created)'] = explode(',', $date);
            }

            //Count Images
            $totalImages = Image::find()->where($imagesCriteria)->count();

            //$imagesCriteria->offset = $offset;

            //Find corresponding Images
            $images = Image::find()->where($imagesCriteria)->orderBy(['created' => SORT_DESC])->limit($limit)->all();

            $previous = null;
            $next = null;
            $currentImage = 0;
            $isLast = true;

            if ($imageId === null) {
                $imageId = 0;
            }

            if (count($images) > 0) {
                if ($imageId !== $images[0]->id) {
                    $isLast = false;
                }

                if ($imageId === "0" || intval($imageId) > $images[0]->id) {
                    $imageId = $images[0]->id;
                }
            }

            if ($imageId !== null) {
                foreach ($images as $index => $image) {
                    if ($image->id == $imageId) {
                        $currentImage = $index;
                        //Next/prev
                        if ($currentImage > 0) {
                            $previous = $images[$index - 1]->id;
                        }
                        if ($currentImage != count($images) - 1) {
                            $next = $images[$index + 1]->id;
                        }
                        break;
                    }
                }
            }

            //Pages
            $pagesCount = $totalImages % $limit !== 0 ? intval($totalImages / $limit) + 1 : intval($totalImages / $limit);
            if ($currentPage === null) {
                $currentPage = intval(count($currentImage) / $limit) + 1;
            }

            $json = [];
            foreach ($images as $image) {
                $element = [
                  'id' => $image->id,
                  'thumb' => $image->getThumbnailUrl(),
                  'image' => $image->getImageUrl(),
                  'big' => $image->getImageUrl(),
                  'created' => $image->created
                ];
                $json[] = $element;
            }
            $json = json_encode($json);

            if ($view == 'one') {
                $this->footer = $this->renderPartial('footers/one', compact('type', 'date', 'id', 'imageId', 'filterTypes'), true);

                return $this->render('one', compact('images', 'camera', 'view', 'imageId', 'isLast', 'currentImage', 'previous', 'next', 'type', 'date', 'json', 'pagesCount', 'currentPage', 'limit', 'id'));
            } elseif ($view == 'thumbs') {
                $this->footer = $this->renderPartial('footers/thumbs', compact('type', 'date', 'id', 'imageId', 'filterTypes'), true);

                return $this->render('thumbs', compact('images', 'camera', 'view', 'imageId', 'isLast', 'currentImage', 'previous', 'next', 'type', 'date', 'json', 'pagesCount', 'currentPage', 'limit', 'id'));
            } elseif ($view == 'full') {
                return $this->render('full', compact('images', 'camera', 'view'));
            }
        }
    }*/

    /*public function actionTest()
    {
        $camera = Camera::findOne(2);
        $node = new NodeCameraAPI($camera);
        $node->updateWaterMark();
    }*/

    public function actionAdd()
    {
        $newRegistrator = new Registrator();

        $locations = Location::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
        $locations = ArrayHelper::map($locations, 'id', 'name');

        if (isset($_POST['Registrator'])) {
            $newRegistrator->load($_POST);

            $newRegistrator->ftp_login = $newRegistrator->getNewFtpLogin();
            $newRegistrator->create_date = time();
            $newRegistrator->user_id = Yii::$app->user->identity->userId;
            $newRegistrator->ftp_home_dir = Yii::$app->params['cameraDir'] . '/' . Yii::$app->user->identity->accountNumber . '/' . $newRegistrator->ftp_login;
            $newRegistrator->memory_limit *= 1024;

            if ($newRegistrator->save()) {
                Yii::$app->session->setFlash('SUCCESS', 'Регистратор добавлен');
                $this->redirect(['/public_cabinet/registrator/']);
            }
        }

        return $this->render('add', compact('newRegistrator', 'locations'));
    }

    public function actionEdit($id)
    {
        $registrator = Registrator::findOne(['id' => $id, 'deleted' => 0]);

        if ($registrator->user_id != Yii::$app->user->identity->userId) {
            $this->redirect('/public_cabinet/registrator/');
        }

        if ($registrator) {

            $locations = Location::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
            $locations = ArrayHelper::map($locations, 'id', 'name');

            $oldLogin = $registrator->ftp_login;

            if (isset($_POST['Registrator'])) {
                $registrator->load($_POST);
                $registrator->ftp_login = $oldLogin;

                if ($registrator->validate()) {
                    if ($registrator->save()) {
                        Yii::$app->session->setFlash('SUCCESS', 'Регистратор сохранен');
                        $this->redirect(['/public_cabinet/registrator/edit', 'id' => $id]);
                    }
                }
            }

            //$this->activeRegistrator = $id;
            //$this->layout = 'edit';

            return $this->render('edit', compact('registrator', 'locations'));
        }
    }

    public function actionManage($category = null, $location = null)
    {
        $RegistratorsAttributes = ['user_id' => Yii::$app->user->identity->userId, 'deleted' => 0];

        if ($category !== null) {
            $RegistratorsAttributes['Registrator_category_id'] = $category;
        }
        if ($location !== null) {
            $RegistratorsAttributes['location_id'] = $location;
        }

        $Registrators = Registrator::find()->where($RegistratorsAttributes)->orderBy(['Registrator_category_id' => SORT_ASC, 'location_id' => SORT_ASC])->all();

        $this->activeLocation = $location;
        $this->activeCategory = $category;
        $this->layout = 'manage';

        return $this->render('manage', compact('Registrators', 'location', 'category'));
    }

    public function actionDelete($id)
    {
        Registrator::updateAll(['deleted' => 1], 'user_id = :user AND id = :id', [':user' => Yii::$app->user->identity->userId, ':id' => $id]);

        Yii::$app->session->setFlash('SUCCESS', 'Регистратор удалена');

        $this->redirect(['/public_cabinet/registrator/index']);
    }

    public function actionGetJsonImages($id, $page = 1, $limit = 12, $type = 'all', $date = null)
    {
        $this->activeRegistrator = $id;
        $Registrator = Registrator::findOne(['id' => $id, 'deleted' => 0]);

        if ($Registrator) {

            $imagesCriteria = [
                'Registrator_id' => $id,
                'deleted' => 0,
            ];

            $typeFilter = null;
            $dateFilter = null;
            $favFilter = null;

            if ($type !== 'all') {
                foreach (explode(',', $type) as $typeValue) {
                    switch ($typeValue) {
                        case 'alert':
                            $typeFilter[] = 'ALERT';
                            break;
                        case 'move':
                            $typeFilter[] = 'MOVE';
                            break;
                        case 'schedule':
                            $typeFilter[] = 'SCHEDULE';
                            break;
                        case 'favorite':
                            $favFilter = [1];
                            break;
                        default:
                            $typeFilter = null;
                    }
                }
            }

            if ($date !== null) {
                $dateFilter = $date;
            }

            if ($favFilter) {
                $imagesCriteria['f_fav'] = $favFilter;
            }

            if ($typeFilter) {
                $imagesCriteria['type'] = $typeFilter;
            }

            if ($dateFilter) {
                $imagesCriteria['DATE(created)'] = explode(',', $date);
            }
            //Find corresponding Images
            $images = Image::find()->where($imagesCriteria)->orderBy(['created' => SORT_DESC])->offset(($page - 1) * $limit)->limit($limit)->all();

            $json = [];
            foreach ($images as $image) {
                $element = ['id' => $image->id, 'thumb' => $image->getThumbnailUrl(), 'image' => $image->getImageUrl(), 'big' => $image->getImageUrl(), 'created' => $image->created];
                $json[] = $element;
            }
            echo json_encode($json);
        }
    }

    public function actionFavorite()
    {
        $id = (int)$_POST['id'];

        if (!$id) {
            return false;
        }

        $image = Image::findOne($id);

        if ($image->f_fav) {
            Image::updateAll(['f_fav' => 0], ['id' => $id]);

            return $this->renderPartial('favorite', ['id' => $id, 'f_fav' => 0]);
        } else {
            Image::updateAll(['f_fav' => 1], ['id' => $id]);

            return $this->renderPartial('favorite', ['id' => $id, 'f_fav' => 1]);
        }

        return false;
    }
}
