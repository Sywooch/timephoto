<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 15.03.15
 * Time: 17:20
 * array
 * (
 * 'OutSum' => '123'
 * 'InvId' => '793286269'
 * 'SignatureValue' => '82C7B01871ED97985591B9F3C9D1917E'
 * 'Shp_transaction' => '2'
 * )
 */

namespace app\controllers;

use app\models\User;
use app\models\Transaction;
use app\components\RoboKassa;

use yii\web\Controller;

class RobokassaController extends Controller
{
    public function init(){
        $this->enableCsrfValidation = false;
    }

    public function actionResult()
    {
        $robokassa = new RoboKassa();

        if ($robokassa->valid($_POST)) // @todo Additional Verification via XML
        {
            $transaction = Transaction::findOne($robokassa->transaction);
            $transaction->status = 'COMPLETED';
            $transaction->method = 'ROBOKASSA';

            $transaction->save();
            $user = User::findOne($transaction->user_id);
            if ($user) {
                $user->refillBalance($transaction->amount);
            }
        }
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }

    public function actionFail()
    {
        return $this->render('fail');
    }
}