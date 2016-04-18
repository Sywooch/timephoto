<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;

use app\components\NodeCameraAPI;
use app\components\UploadedFile;
use app\models\Camera;
use app\models\CameraCategory;
use app\models\Image;
use app\models\Location;
use app\models\Tariff;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class CameraController extends \app\modules\cabinet\components\CabinetController
{

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!isset($_COOKIE['GalleryOneColumn'])) {
            setcookie("GalleryOneColumn", 3);
        }
        if (!isset($_COOKIE['GalleryOneHeight'])) {
            setcookie("GalleryOneHeight", 8);
        }

        if (!isset($_COOKIE['GalleryColumn'])) {
            setcookie("GalleryColumn", 4);
        }
        if (!isset($_COOKIE['GalleryHeight'])) {
            setcookie("GalleryHeight", 4);
        }

        if (!isset($_COOKIE['GallerySort'])) {
            setcookie("GallerySort", 'desc');
        }
        if (!isset($_COOKIE['GalleryOneSort'])) {
            setcookie("GalleryOneSort", 'desc');
        }

        return true;
    }

    /**
     * @param null $id
     * @param string $view
     * @param null $imageId
     * @param string $type
     * @param null $date
     * @param null $currentPage
     * @param int $limit
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex($id = null, $view = 'thumbs', $imageId = null, $type = 'all', $date = null, $currentPage = null, $limit = 16)
    {

        if (!Yii::$app->user->identity->checkPermission('access_view', $id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        if (isset($_COOKIE['GalleryOneColumn']) && isset($_COOKIE['GalleryOneHeight']) && isset($_COOKIE['GalleryColumn']) && isset($_COOKIE['GalleryHeight'])) {
            if ($view == 'one') {
                $limit = $_COOKIE['GalleryOneColumn'] * $_COOKIE['GalleryOneHeight'];
            } else {
                $limit = $_COOKIE['GalleryColumn'] * $_COOKIE['GalleryHeight'];
            }
        } else {
            $limit = !empty($limit) ? $limit : 16;
        }


        if ($id === null) {

            /*$cameras = Camera::find()->where(['user_id' => Yii::$app->user->identity->userId, 'deleted' => 0])
                ->orderBy(['location_id' => SORT_DESC, 'id' => SORT_DESC])->all();*/

            return $this->render('index', ['cameras' => $this->cameras]);
        } else {
            return $this->actionPhotos($id, $view, $imageId, $type, $date, $currentPage, $limit);
        }
    }

    /**
     * Example: http://timephoto.loc:8082/cabinet/public?token=33c6ffb2843fdd44cf099fe5ca0323a8&view=one
     * @param null $token
     * @param string $view
     * @param null $imageId
     * @param string $type
     * @param null $date
     * @param null $currentPage
     * @param int $limit
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionPublic($token = null, $view = 'thumbs', $imageId = null, $type = 'all', $date = null, $currentPage = null, $limit = 16)
    {

        $id = Camera::find()->select('id')->where(new Expression('MD5(CONCAT(id, created)) = :token'), [':token' => $token])->scalar();

        if (!Yii::$app->user->identity->checkPermission('access_view', $id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        if (isset($_COOKIE['GalleryOneColumn']) && isset($_COOKIE['GalleryOneHeight']) && isset($_COOKIE['GalleryColumn']) && isset($_COOKIE['GalleryHeight'])) {
            if ($view == 'one') {
                $limit = $_COOKIE['GalleryOneColumn'] * $_COOKIE['GalleryOneHeight'];
            } else {
                $limit = $_COOKIE['GalleryColumn'] * $_COOKIE['GalleryHeight'];
            }
        } else {
            $limit = !empty($limit) ? $limit : 16;
        }


        if ($id === null) {
            return $this->render('index', ['cameras' => 1]);
        } else {
            return $this->actionPhotos($id, $view, $imageId, $type, $date, $currentPage, $limit);
        }
    }

    /**
     * @param $id
     * @param string $view
     * @param null $imageId
     * @param string $type
     * @param null $date
     * @param null $currentPage
     * @param int $limit
     * @return string
     */
    public function actionPhotos($id, $view = 'thumbs', $imageId = null, $type = 'all', $date = null, $currentPage = null, $limit = 16)
    {

        $favFilter = array();

        if (isset($_COOKIE['GalleryOneColumn']) && isset($_COOKIE['GalleryOneHeight']) && isset($_COOKIE['GalleryColumn']) && isset($_COOKIE['GalleryHeight'])) {
            if ($view == 'one') {
                $limit = $_COOKIE['GalleryOneColumn'] * $_COOKIE['GalleryOneHeight'];
            } else {
                $limit = $_COOKIE['GalleryColumn'] * $_COOKIE['GalleryHeight'];
            }
        } else {
            $limit = !empty($limit) ? $limit : 16;
        }

        $this->activeCamera = $id;
        $camera = Camera::findOne(['id' => $id, 'deleted' => 0]);

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

            if (isset($_COOKIE['GallerySort']) && isset($_COOKIE['GalleryOneSort'])) {
                if ($view == 'one') {
                    $sort = ($_COOKIE['GalleryOneSort'] == 'desc') ? SORT_DESC : SORT_ASC;
                } else {
                    $sort = ($_COOKIE['GallerySort'] == 'desc') ? SORT_DESC : SORT_ASC;
                }
            } else {
                $sort = SORT_DESC;
            }

            //Find corresponding Images
            $images = Image::find()->where($imagesCriteria)->orderBy(['created' => $sort])->limit($limit)->all();

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
                    'created' => date("d-m-y H:i:s", strtotime($image->created))
                ];
                $json[] = $element;
            }

            $json = json_encode($json);

            if ($view == 'one') {
                $this->footer = $this->renderPartial('footers/one', compact('type', 'date', 'id', 'imageId', 'filterTypes'), true);
                return $this->render('one', compact('images', 'camera', 'view', 'imageId', 'isLast',
                    'currentImage', 'previous', 'next', 'type', 'date', 'json', 'pagesCount', 'currentPage', 'limit', 'id'));
            } elseif ($view == 'thumbs') {
                $this->footer = $this->renderPartial('footers/thumbs', compact('type', 'date', 'id', 'imageId', 'filterTypes'), true);
                return $this->render('thumbs', compact('images', 'camera', 'view', 'imageId', 'isLast', 'currentImage',
                    'previous', 'next', 'type', 'date', 'json', 'pagesCount', 'currentPage', 'limit', 'id'));
            } elseif ($view == 'full') {
                return $this->render('full', compact('images', 'camera', 'view'));
            }
        }
    }

    /*public function actionTest()
    {
        $camera = Camera::findOne(2);
        $node = new NodeCameraAPI($camera);
        $node->updateWaterMark();
    }*/

    public function actionAdd()
    {
        $newCamera = new Camera();

        $locations = Location::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
        $locations = ArrayHelper::map($locations, 'id', 'name');

        $categories = CameraCategory::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
        $categories = ArrayHelper::map($categories, 'id', 'name');


        if (isset($_POST['Camera'])) {
            $newCamera->load($_POST);
            $newCamera->internal_id = $newCamera->getNextCameraNumberByUser(Yii::$app->user->identity->userId);
            $newCamera->ftp_login = Yii::$app->user->identity->accountNumber . '_' . $newCamera->internal_id;
            //$newCamera->created = new Expression('NOW()');
            $newCamera->user_id = Yii::$app->user->identity->userId;
            $newCamera->ftp_home_dir = Yii::$app->params['cameraDir'] . '/' . Yii::$app->user->identity->accountNumber . '/' . $newCamera->ftp_login;
            $newCamera->memory_limit *= 1024;

            if ($newCamera->validate()) {
                if (isset($_FILES['Camera']['tmp_name']['icon_file'])) {
                    if ($_FILES['Camera']['tmp_name']['icon_file'] !== '') {
                        $newCamera->icon_file = UploadedFile::getInstance($newCamera, 'icon_file');
                        $newCamera->icon_file->saveAs('uploads/camera_icons/' . $newCamera->ftp_login . '.' . $newCamera->icon_file->extension);
                        $newCamera->icon_name = $newCamera->ftp_login . '.' . $newCamera->icon_file->extension;

                        $node = new NodeCameraAPI($newCamera);
                        $node->updateWaterMark();
                    }
                }

                if ($newCamera->save()) {
                    Yii::$app->session->setFlash('SUCCESS', 'Камера добавлена');

                    return $this->redirect(['/cabinet/camera/']);
                }
            }
        }

        return $this->render('add', compact('newCamera', 'locations', 'categories'));
    }

    public function actionEdit($id)
    {
        if ($id == 0) {
            //Для страницы настроек берем первую попавшуюся
            $camera = Camera::find()->where(['deleted' => 0, 'user_id' => Yii::$app->user->identity->userId])->one();
        } else {
            $camera = Camera::findOne(['id' => $id, 'deleted' => 0]);
        }

        if ($camera->user_id != Yii::$app->user->identity->userId && $id != 0) {
            $this->redirect('/cabinet/camera/');
        }

        $hasIcon = false;
        if (!empty($_POST['hasIcon'])) {
            if ($_POST['hasIcon'] === 'yes') {
                $hasIcon = true;
            }
        }
        if ($camera) {

            $locations = Location::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
            $locations = ArrayHelper::map($locations, 'id', 'name');

            $categories = CameraCategory::find()->where('user_id IS NULL OR user_id = :myId', [':myId' => Yii::$app->user->identity->userId])->all();
            $categories = ArrayHelper::map($categories, 'id', 'name');

            $oldLogin = $camera->ftp_login;

            if (isset($_POST['Camera'])) {
                $camera->load($_POST);
                $camera->ftp_login = $oldLogin;

                if ($camera->validate()) {

                    if (isset($_FILES['Camera'])) {
                        if ($_FILES['Camera']['tmp_name']['icon_file'] !== '') {
                            $camera->icon_file = UploadedFile::getInstance($camera, 'icon_file');
                            $camera->icon_file->saveAs('uploads/camera_icons/' . $camera->ftp_login . '.' . $camera->icon_file->extension);
                            $camera->icon_name = $camera->ftp_login . '.' . $camera->icon_file->extension;
                            $hasIcon = true;

                            $node = new NodeCameraAPI($camera);
                            $node->updateWaterMark();
                        }
                    }

                    if (!$hasIcon && $camera->icon_name) {
                        unlink(realpath('uploads/camera_icons/' . $camera->icon_name));
                        $camera->icon_name = null;
                        $node = new NodeCameraAPI($camera);
                        $node->removeWatermark();
                    }

                    if ($camera->save()) {
                        Yii::$app->session->setFlash('SUCCESS', 'Камера сохранена');

                        return $this->redirect(['/cabinet/camera/edit', 'id' => $id]);
                    }

                }
            }

            $this->activeCamera = $id;
            $this->layout = 'edit';

            return $this->render('edit', compact('camera', 'locations', 'categories'));
        }
    }

    public function actionManage($category = null, $location = null)
    {
        if (Yii::$app->user->identity->role == 'ADDITIONAL_USER') {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        $camerasAttributes = [
            'user_id' => Yii::$app->user->identity->userId,
            'deleted' => 0
        ];

        if ($category !== null) {
            $camerasAttributes['camera_category_id'] = $category;
        }

        if ($location !== null) {
            $camerasAttributes['location_id'] = $location;
        }

        $cameras = Camera::find()->where($camerasAttributes)->orderBy(['camera_category_id' => SORT_ASC, 'location_id' => SORT_ASC])->all();

        $this->activeLocation = $location;
        $this->activeCategory = $category;
        $this->layout = 'manage';

        return $this->render('manage', compact('cameras', 'location', 'category'));
    }

    public function actionDelete($id)
    {
        Camera::updateAll(['deleted' => 1], 'user_id = :user AND id = :id', [':user' => Yii::$app->user->identity->userId, ':id' => $id]);

        Yii::$app->session->setFlash('SUCCESS', 'Камера удалена');

        return $this->redirect(['/cabinet/camera/index']);
    }

    public function actionGetJsonImages($id, $page = 1, $limit = 16, $type = 'all', $date = null, $sort = 'desc')
    {

        //отлавливаем  refer params только для галлерей
        $ref = Yii::$app->request->getReferrer();
        $params = explode('&', parse_url($ref)['query']);
        if (!empty($params)) {
            foreach ($params as $p) {
                list($key, $value) = explode('=', $p);
                $param[$key] = $value;
            }

        }

        // limit

        if (isset($_COOKIE['GalleryOneColumn']) && isset($_COOKIE['GalleryOneHeight']) && isset($_COOKIE['GalleryColumn']) && isset($_COOKIE['GalleryHeight'])) {
            if (!empty($param['view'])) {
                if ($param['view'] == 'one') {
                    $limit = $_COOKIE['GalleryOneColumn'] * $_COOKIE['GalleryOneHeight'];
                } else {
                    $limit = $_COOKIE['GalleryColumn'] * $_COOKIE['GalleryHeight'];
                }
            }
        } else {
            $limit = !empty($limit) ? $limit : 16;
        }

        if ($sort == 'desc') {
            $sort = SORT_DESC;
        } else {
            $sort = SORT_ASC;
        }

        $this->activeCamera = $id;

        $camera = Camera::findOne(['id' => $id, 'deleted' => 0]);

        if ($camera) {

            $imagesCriteria = [
                'camera_id' => $id,
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
            $images = Image::find()->where($imagesCriteria)->orderBy(['created' => $sort])->offset(($page - 1) * $limit)->limit($limit)->all();

            $json = [];

            foreach ($images as $image) {
                $element = [
                    'id' => $image->id, 'thumb' => $image->getThumbnailUrl(), 'type' => $image->getType(), 'image' => $image->getImageUrl(),
                    'big' => $image->getImageUrl(), 'created' => date('d-m-y H:i:s', strtotime($image->created))];
                $json[] = $element;
            }

            echo json_encode($json);
        }
    }


    public function actionDashboard()
    {
        $user_id = Yii::$app->user->identity->userId;

        //Свободное/занятое место
        $tariff_limit = Tariff::findOne(Yii::$app->user->identity->tariff_id)['memory_limit'] * 1024 * 1024;
        $load_space = Yii::$app->db->createCommand(
            "SELECT SUM(i.file_size)
                    FROM image i
                    JOIN camera c ON c.id=i.camera_id
                    JOIN `user` u ON u.id=c.user_id
                    WHERE u.id=:user_id ", [':user_id' => $user_id])->queryScalar();

        $return['disk_space']['load'] = round($load_space / $tariff_limit * 100, 2);
        $return['disk_space']['free'] = 100 - $return['disk_space']['load'];

        //сводка типы фоток / количество за неделю
        $return['image_per_week'] = ArrayHelper::map(Yii::$app->db->createCommand(
            "SELECT i.`type`, COUNT(i.id) as ammount
                FROM image i
                JOIN camera c ON c.id=i.camera_id
                JOIN `user` u ON u.id=c.user_id
                WHERE u.id=:user_id AND TIMESTAMPDIFF(DAY, i.created, NOW() ) <= 7
                GROUP BY `type`", [':user_id' => $user_id])->queryAll(), 'type', 'ammount');

        // Трафик с камер
        $periods = ['1' => 'day', '7' => 'week', '30' => 'month'];
        foreach ($periods as $day => $type) {
            $filesizes = Yii::$app->db->createCommand(
                "SELECT SUM(i.file_size)
                    FROM image i
                    JOIN camera c ON c.id=i.camera_id
                    JOIN `user` u ON u.id=c.user_id
                    WHERE u.id=:user_id AND TIMESTAMPDIFF(DAY, i.created, NOW() ) <= :day", [':user_id' => $user_id, ':day' => $day])->queryScalar();

            $return['traff_by_camera'][$type] = round($filesizes / 1024 / 1024, 2);
        }

        return $this->render('dashboard', ['stats' => $return]);
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
