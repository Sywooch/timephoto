<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;

use app\models\Camera;
use app\models\CameraCategory;
use app\models\Location;
use Yii;

class ObjectController extends \app\modules\cabinet\components\CabinetController
{
    public $layout = 'object';
    public $locations;
    public $categories;
    public $activeObject;

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->locations = Location::find()->where(['user_id' => Yii::$app->user->identity->userId])->all();
        $this->categories = CameraCategory::find()->where(['user_id' => Yii::$app->user->identity->userId])->all();

        return true;
    }

    public function actionIndex()
    {
        $jsonLocations = [];

        foreach ($this->locations as $location) {
            $jsonLocations[] = $location->attributes;
        }

        $jsonLocations = json_encode($jsonLocations);

        return $this->render('index', compact('jsonLocations'));
    }

    public function actionAjaxSetNewLatLon()
    {
        if (!empty($_POST['id']) && !empty($_POST['lat']) && !empty($_POST['lon'])) {
            $location = Location::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            if ($location) {
                $location->lat = $_POST['lat'];
                $location->lon = $_POST['lon'];
                $location->save();

                echo json_encode($location->attributes);
            }
        }
    }

    public function actionAjaxRemoveLocation()
    {
        if (!empty($_POST['id'])) {
            $location = Location::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            if ($location) {
                Camera::updateAll(['location_id' => 1], 'location_id = :locationId', [':locationId' => $location->id]);
                $location->delete();
            }
        }
    }

    public function actionAjaxRemoveCategory()
    {
        if (!empty($_POST['id'])) {
            $category = CameraCategory::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            if ($category) {
                Camera::updateAll(['camera_category_id' => 1], 'camera_category_id = :categoryId', [':categoryId' => $category->id]);
                $category->delete();
            }
        }
    }

    public function actionAjaxAddCategory()
    {
        if (!empty($_POST['name'])) {
            $category = new CameraCategory();
            $category->name = $_POST['name'];
            $category->user_id = Yii::$app->user->identity->userId;
            $category->save();

            echo json_encode($category->attributes);
        }
    }

    public function actionAjaxAddLocation()
    {
        if (!empty($_POST['name'])) {
            $location = new Location();
            $location->name = $_POST['name'];
            $location->user_id = Yii::$app->user->identity->userId;
            $location->save();

            echo json_encode($location->attributes);
        }
    }

    public function actionAjaxPurgeLocation()
    {
        if (!empty($_POST['id'])) {
            $location = Location::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            if ($location) {
                $location->lat = null;
                $location->lon = null;
                $location->save();
            }
        }
    }

    public function actionAjaxEditCategory()
    {
        if (!empty($_POST['id']) && !empty($_POST['name'])) {
            $category = CameraCategory::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            $category->name = $_POST['name'];
            $category->save();

            echo json_encode($category->attributes);
        }
    }

    public function actionAjaxEditLocation()
    {
        if (!empty($_POST['id']) && !empty($_POST['name'])) {
            $location = Location::findOne(['user_id' => Yii::$app->user->identity->userId, 'id' => $_POST['id']]);
            $location->name = $_POST['name'];
            $location->save();

            echo json_encode($location->attributes);
        }
    }
}