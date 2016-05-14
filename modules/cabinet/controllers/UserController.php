<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;

use app\components\Helper;
use app\components\RoboKassa;
use app\components\UploadedFile;
use app\models\AdditionalUser;
use app\models\AdditionalUserPermission;
use app\models\Camera;
use app\models\Invoice;
use app\models\Permission;
use app\models\Tariff;
use app\models\TariffDuration;
use app\models\Transaction;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;

class UserController extends \app\modules\cabinet\components\CabinetController
{
    public $layout = 'user';
    public $pageTitle = 'Пользователь';


    public function actionIndex()
    {
        $tariffs = Tariff::find()->all();
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();

        if (isset($post['User'])) {
            foreach ($post['User'] as $k => $v) {
                if(property_exists(User::className(), $k)){
                    $user->$k = $v;
                }
            }

            if ($user->changePassword()) {
                Yii::$app->session->setFlash('PASSWORD_OK', 'Пароль успешно изменен');
                return $this->redirect(['/cabinet/user/index']);
            }else{
                Yii::$app->session->setFlash('PASSWORD_ERR', 'Пароли указаны не верно');
                return $this->redirect(['/cabinet/user/index']);
            }
        }

        return $this->render('index', compact('user', 'tariffs'));
    }

    public function actionServices()
    {

        if (isset($_POST['User'])) {
            Yii::$app->user->identity->load($_POST);
            $hasCustomLogo = false;
            if (!empty($_POST['hasCustomLogo'])) {
                if ($_POST['hasCustomLogo'] === 'yes') {
                    $hasCustomLogo = true;
                }
            }

            if (isset($_FILES['User'])) {
                if ($_FILES['User']['tmp_name']['custom_logo_file'] !== '') {
                    Yii::$app->user->identity->custom_logo_file = UploadedFile::getInstance(Yii::$app->user->identity, 'custom_logo_file');
                    Yii::$app->user->identity->custom_logo_file->saveAs('uploads/custom_logos/' . Yii::$app->user->identity->userId . '.' . Yii::$app->user->identity->custom_logo_file->extension);
                    Yii::$app->user->identity->custom_logo = Yii::$app->user->identity->userId . '.' . Yii::$app->user->identity->custom_logo_file->extension;
                    $hasCustomLogo = true;
                }
            }

            if (!$hasCustomLogo && Yii::$app->user->identity->custom_logo) {
                unlink(realpath('uploads/custom_logos/' . Yii::$app->user->identity->custom_logo));
                Yii::$app->user->identity->custom_logo = null;
            }

            if (Yii::$app->user->identity->validate()) {
                Yii::$app->user->identity->save();
                Yii::$app->session->setFlash('SUCCESS', 'Изменения сохранены');

                return $this->redirect(['/cabinet/user/services']);
            }
        }

        return $this->render('services');
    }


    public function actionAjaxToggleAllCameras()
    {
        if (!empty($_POST['action']) && !empty($_POST['password'])) {
            $user = User::findOne(Yii::$app->user->identity->getId());
            if ($_POST['password'] == $user->password) {

                if ($_POST['action'] == 'disable') {
                    Camera::updateAll(['enabled' => 0], 'user_id = :myId', [':myId' => Yii::$app->user->identity->getId()]);
                    $user->cameras_enabled = 0;
                    $user->save();
                } else {
                    Camera::updateAll(['enabled' => 1], 'user_id = :myId', [':myId' => Yii::$app->user->identity->getId()]);
                    $user->cameras_enabled = 1;
                    $user->save();
                }
                echo 'OK';
            } else {
                echo 'WRONG_PASSWORD';
            }
        }
    }

    public function actionAjaxChangeTariff()
    {
        if (!empty($_POST['tariffDurationId'])) {
            $tariffDuration = TariffDuration::findOne($_POST['tariffDurationId']);

            if ($tariffDuration) {
                if (Yii::$app->user->identity->buy($tariffDuration)) {
                    Yii::$app->user->identity->setTariffDuration($tariffDuration);
                    $response = ['result' => 'OK', 'name' => $tariffDuration->tariff->name, 'expires' => Yii::$app->user->identity->getTariffExpireDate()];
                } else {
                    $response = ['result' => 'LOW_FUNDS'];
                }
            } else {
                $response = ['result' => 'ERROR'];
            }
        } else {
            $response = ['result' => 'ERROR'];
        }

        echo json_encode($response);
    }

    public function actionAjaxPaymentModal()
    {
        if (!empty($_POST['amount'])) {
            $transaction = new Transaction();
            $transaction->amount = $_POST['amount'];
            //$transaction->created = new CDbExpression('NOW()');
            $transaction->method = 'ROBOKASSA';
            $transaction->type = 'IN';
            $transaction->user_id = Yii::$app->user->identity->userId;
            if ($transaction->save()) {
                $rk = new RoboKassa();
                $rk->setAmount($transaction->amount);
                $rk->transaction = $transaction->getPrimaryKey();

                echo $rk->getParams();
            }
        }
    }

    public function actionInvoice()
    {
        if (!empty($_POST['amount'])) {
            $transaction = new Transaction();
            $transaction->amount = $_POST['amount'];
            //$transaction->created = new Expression('NOW()');
            $transaction->method = 'INVOICE';
            $transaction->type = 'IN';
            $transaction->user_id = Yii::$app->user->identity->getId();
            if ($transaction->save()) {
                $invoice = new Invoice();
                $invoice->amount = $_POST['amount'];
                $invoice->save();
                return $this->redirect(['/cabinet/user/print-invoice', 'id' => $invoice->getPrimaryKey()]);
            }
        }else{
            $this->actionIndex();
        }
    }

    public function actionPrintInvoice($id)
    {
        $invoice = Invoice::findOne($id);

        if ($invoice) {
            return $this->renderPartial('//system/invoice', [
                'id' => $invoice->id,
                'amount' => $invoice->amount,
                'amountString' => Helper::num2str($invoice->amount),
                'date' => Helper::rusDate(),
            ]);
        }
    }

    public function actionManager()
    {
        $additionalUsers = AdditionalUser::findAll(['user_id' => Yii::$app->user->id]);

        return $this->render('/users/manager', ['user_id' => Yii::$app->user->id, 'additionalUsers' => $additionalUsers]);
    }

    public function actionAjaxAddAdditionalUser()
    {
        if (!empty($_POST['additionalUser'])) {
            $additionalUser = AdditionalUser::findOne(['user_id' => Yii::$app->user->id, 'email' => $_POST['additionalUser']]);

            $index = AdditionalUser::find()->where(['user_id' => Yii::$app->user->id])->count();

            if (!$additionalUser) {
                $additionalUser = new AdditionalUser();
                $additionalUser->user_id = Yii::$app->user->id;
                $additionalUser->active = true;
                $additionalUser->login = Yii::$app->user->identity->accountNumber . '_U' . ($index + 1);
                $additionalUser->name = 'Без имени';
                $additionalUser->email = $_POST['additionalUser'];
                $additionalUser->password = Helper::randomPassword();
            }

            if ($additionalUser->save()) {
                $response = [
                    'result' => 'OK',
                    'additional_users' => [
                        [
                            'id' => $additionalUser->id,
                            'login' => $additionalUser->login,
                            'name' => $additionalUser->name,
                            'email' => $additionalUser->email,
                            'active' => (bool)$additionalUser->active,
                        ]
                    ]
                ];
            } else {
                $response = ['result' => 'ERROR'];
            }
        } else {
            $response = ['result' => 'ERROR'];
        }

        echo json_encode($response);
    }

    public function actionAjaxEditAdditionalUser()
    {

        $pk = Yii::$app->request->post('pk');

        $response = ['result' => 'ERROR'];

        if (Yii::$app->request->isPost && $pk) {

            $additionalUser = AdditionalUser::findOne($pk);

            $name = Yii::$app->request->post('name');

            switch ($name) {
                case 'active':
                    $value = (int)Yii::$app->request->post('value');
                    break;
                default:
                    $value = Yii::$app->request->post('value');
                    break;
            }

            $additionalUser->load(['AdditionalUser' => [$name => $value]]);

            if ($additionalUser->save()) {
                $response = ['result' => 'OK'];
            }

        }

        echo json_encode($response);
    }

    public function actionAjaxRemoveAdditionalUser()
    {

        $pk = Yii::$app->request->post('pk');

        $response = ['result' => 'ERROR'];

        if (Yii::$app->request->isPost && $pk) {

            if (AdditionalUser::deleteAll(['id' => $pk])) {
                $response = ['result' => 'OK'];
            }

        }

        echo json_encode($response);
    }

    public function actionAjaxGetAdditionalUserInfo()
    {
        $response = ['result' => 'ERROR'];

        $pk = Yii::$app->request->post('pk');

        if (Yii::$app->request->isPost && $pk) {

            /** @var AdditionalUser $additionalUser */
            $additionalUser = AdditionalUser::findOne($pk);

            $permissions_collections = ArrayHelper::map(Permission::find()->asArray()->all(), 'id', 'title');

            $client = $additionalUser->user;

            foreach ($client->cameras as $camera) {
                if (!$camera->deleted) {
                    $camera->name = $camera->getName();
                    $element = $camera->attributes;
                    $permission = AdditionalUserPermission::find()
                        ->select(['permission_id', 'machine_name'])
                        ->where([
                            'additional_user_id' => $additionalUser->id,
                            'camera_id' => $camera->id,
                        ])
                        ->innerJoin('permission p', 'p.id = additional_user_permission.permission_id')
                        ->asArray()->one();

                    if (isset($permissions_collections[$permission['permission_id']])) {
                        $element['permissions'] = $permissions_collections[$permission['permission_id']];
                        $element['permissions_id'] = $permission['permission_id'];

                        switch ($permission['machine_name']) {
                            case 'access_view':
                                $element['permissions_class'] = 'success';
                                break;
                            case 'access_copy':
                                $element['permissions_class'] = 'info';
                                break;
                            case 'access_delet':
                                $element['permissions_class'] = 'danger';
                                break;
                        }
                    }
                    $cameras[] = $element;
                }
            }

            $response = ['result' => 'OK'];
            $response['clientInfo']['settings'] = [
                'login' => $additionalUser->login,
                'created' => $additionalUser->create_time,
                'password' => '******',
                'limit' => $additionalUser->traffic_limit_after_block,
                'traffic' => 0,
                'id' => $additionalUser->id,
            ];

            $response['clientInfo']['cameras'] = $cameras;

        }

        echo json_encode($response);
    }

    public function actionAjaxSetAdditionalUserPermession()
    {

        $response = ['result' => 'ERROR'];

        $additional_user_id = Yii::$app->request->post('additional_user_id');
        $camera_id = Yii::$app->request->post('camera_id');
        $permission_id = Yii::$app->request->post('permission_id');

        /** @var AdditionalUser $additionalUser */
        if ($additionalUser = AdditionalUser::findOne($additional_user_id)) {
            $permission_id = $additionalUser->addPermission($camera_id, $permission_id);
            $response = ['result' => 'OK', 'userPermission' => $permission_id];
        }

        echo json_encode($response);

    }

}