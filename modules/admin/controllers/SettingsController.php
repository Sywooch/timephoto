<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\Json;
use app\models\Settings;
use app\modules\admin\components\AdminController;

class SettingsController extends AdminController
{

    public $layout = 'page';

    public function actionUploadLogo()
    {
        $logo = Yii::$app->request->post('logo');
        $logo = substr($logo, strpos($logo, 'base64,')+7);
        file_put_contents(Yii::getAlias('@app').'/web/images/watermark-logo.png',base64_decode($logo));
        if(file_exists(Yii::getAlias('@app').'/web/images/watermark-logo.png')){
            $setting = Settings::findOne(['setting'=> 'site_logo']);
            $setting->value = '/web/images/watermark-logo.png';
            $setting->save();
            return Json::encode(['success' => true]);
        }
    }

    public function actionDeleteLogo()
    {
        $logo = Yii::getAlias('@app').'/web/images/watermark-logo.png';

        if(file_exists($logo)){
            unlink($logo);
            $setting = Settings::findOne(['setting'=> 'site_logo']);
            $setting->value = 0;
            $setting->save();
            return Json::encode(['success' => true]);
        }

    }

    public function actionIndex(){
        $settings = Settings::find()->all();
        return $this->render('index', compact($settings));
    }

}