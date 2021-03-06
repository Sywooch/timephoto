<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 06.08.15
 * Time: 1:02
 */

namespace app\modules\public_cabinet\components;

use app\components\Controller;
use app\models\Camera;
use Yii;
use yii\db\Expression;

/**
 * Site controller
 */
class CabinetController extends Controller
{

    public $layout = 'camera';
    public $activeCamera = null;
    public $activeLocation = null;
    public $activeCategory = null;
    public $cameras = [];
    public $jsonCameras = '';
    public $jsonRegistrators = '';
    public $registrators = [];

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        if (\Yii::$app->request->isAjax) {
            return true;
        }

        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->request->get('token')) {

                $id = Camera::find()->select('id')->where(new Expression('MD5(CONCAT(id, created)) = :token'), [':token' => Yii::$app->request->get('token')])->scalar();
                list($cameras, $camerasArray) = Camera::getCameras($id);
                $this->cameras = $cameras;
                $this->jsonCameras = json_encode($camerasArray);

            } else {

                list($cameras, $camerasArray) = Camera::getCameras();
                $this->cameras = $cameras;
                $this->jsonCameras = json_encode($camerasArray);

                //list($registrators, $registratorArray) = Registrator::getRegistrators();
                //$this->registrators = $registrators;
                //$this->jsonRegistrators = json_encode($registratorArray);

            }
        }

    }


}