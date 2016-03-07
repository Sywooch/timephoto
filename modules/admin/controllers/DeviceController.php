<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 19.03.15
 * Time: 2:29
 */

namespace app\modules\admin\controllers;

use app\components\UploadedFile;
use app\models\Device;
use app\models\DeviceCase;
use app\models\DeviceCategory;
use app\models\DeviceFeature;
use app\models\DevicePhoto;
use app\modules\admin\components\AdminController;

class DeviceController extends AdminController
{
    public $layout = 'device';

    public function actionIndex()
    {
        $deviceCategories = DeviceCategory::find()->all();

        return $this->render('index', compact('deviceCategories'));
    }

    public function actionAjaxGetCategory($id, $device_index = 0)
    {
        $device_index = intval($device_index);
        $deviceCategory = DeviceCategory::findOne($id);
        $response = [
            'devices' => [],
            'device' => [
                'features' => [],
                'photos' => [],
                'cases' => [],
                'description' => '',
            ],
        ];

        $devices = [];

        foreach ($deviceCategory->devices as $device) {
            $response['devices'][] = $device->attributes;
        }

        if (count($deviceCategory->devices) > $device_index) {
            foreach ($deviceCategory->devices[$device_index]->deviceFeatures as $feature) {
                $response['device']['features'][] = $feature->attributes;
            }
            foreach ($deviceCategory->devices[$device_index]->devicePhotos as $devicePhoto) {
                $response['device']['photos'][] = $devicePhoto->attributes;
            }
            foreach ($deviceCategory->devices[$device_index]->deviceCases as $deviceCase) {
                $response['device']['cases'][] = $deviceCase->attributes;
            }

            $response['device']['description'] = $deviceCategory->devices[$device_index]->description;
        }

        echo json_encode($response);
    }

    public function actionAjaxAddDeviceCategory()
    {
        if (!empty($_POST['name'])) {
            $deviceCategory = new DeviceCategory();

            $deviceCategory->name = $_POST['name'];

            if ($deviceCategory->validate()) {
                $deviceCategory->save();
                echo json_encode(['deviceCategories' => [$deviceCategory->attributes]]);
            }
        }
    }

    public function actionAjaxAddDevice()
    {
        if (!empty($_POST['name']) && !empty($_POST['categoryId'])) {
            $device = new Device();

            $device->name = $_POST['name'];
            $device->device_category_id = $_POST['categoryId'];
            $device->price = 0;

            if ($device->validate()) {
                $device->save();
                echo json_encode(['devices' => [$device->attributes]]);
            }
        }
    }

    public function actionAjaxAddFeature()
    {
        if (!empty($_POST['name']) && !empty($_POST['deviceId'])) {
            $feature = new DeviceFeature();

            $feature->name = $_POST['name'];
            $feature->device_id = $_POST['deviceId'];

            if ($feature->validate()) {
                $feature->save();
                echo json_encode(['device' => ['features' => [$feature->attributes]]]);
            }
        }
    }

    public function actionAjaxAddPhoto()
    {
        if (!empty($_FILES['DevicePhoto']['tmp_name']['photo']) && !empty($_POST['deviceId'])) {
            $photo = new DevicePhoto();
            $photo->photo = UploadedFile::getInstance($photo, 'photo');
            $photo->photo->saveAs('uploads/device_photos/' . date('Ymdhis') . '.' . $photo->photo->extension);
            $photo->original_file_name = $photo->photo->name;
            $photo->file_name = date('Ymdhis') . '.' . $photo->photo->extension;
            $photo->device_id = $_POST['deviceId'];

            if ($photo->validate()) {
                $photo->save();
                echo json_encode(['device' => ['photos' => [$photo->attributes]]]);
            }
        }
    }

    public function actionAjaxAddCase()
    {
        if (!empty($_FILES['DeviceCase']['tmp_name']['case']) && !empty($_POST['deviceId'])) {
            $case = new DeviceCase();
            $case->case = UploadedFile::getInstance($case, 'case');
            $case->case->saveAs('uploads/device_cases/' . date('Ymdhis') . '.' . $case->case->extension);
            $case->original_file_name = $case->case->name;
            $case->file_name = date('Ymdhis') . '.' . $case->case->extension;
            $case->device_id = $_POST['deviceId'];

            if ($case->validate()) {
                $case->save();
                echo json_encode(['device' => ['cases' => [$case->attributes]]]);
            }
        }
    }

    public function actionAjaxSaveDescription()
    {
        if (!empty($_POST['description']) && !empty($_POST['deviceId'])) {
            $device = Device::findOne($_POST['deviceId']);
            if ($device) {
                $device->description = $_POST['description'];

                if ($device->validate()) {
                    $device->save();
                }
            }
        }
    }

    public function actionDeleteDeviceCategory($id)
    {
        $model = DeviceCategory::findOne($id);
        if ($model->delete()) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function actionDeleteDevice($id)
    {
        $model = Device::findOne($id);
        if ($model->delete()) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function actionDeletePhoto($id)
    {
        $model = DevicePhoto::findOne($id);
        if ($model->delete()) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function actionDeleteFeature($id)
    {
        $model = DeviceFeature::findOne($id);
        if ($model->delete()) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function actionDeleteCase($id)
    {
        $model = DeviceCase::findOne($id);
        if ($model->delete()) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function actionEditCategory()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $model = DeviceCategory::findOne($_POST['pk']);
            if ($model) {
                $model->$_POST['name'] = $_POST['value'];
                $model->save();

                echo $model->$_POST['name'];
            }
        }
    }

    public function actionEditDevice()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $model = Device::findOne($_POST['pk']);
            if ($model) {
                $model->$_POST['name'] = $_POST['value'];
                $model->save();

                echo $model->$_POST['name'];
            }
        }
    }

    public function actionEditFeature()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $model = DeviceFeature::findOne($_POST['pk']);
            if ($model) {
                $model->$_POST['name'] = $_POST['value'];
                $model->save();

                echo $model->$_POST['name'];
            }
        }
    }
}