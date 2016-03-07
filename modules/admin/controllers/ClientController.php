<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 09.03.15
 * Time: 4:08
 */

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\components\AdminController;

class ClientController extends AdminController
{
    public $layout = 'clients';

    public function actionIndex()
    {
        $users = User::findAll(['role' => 'USER']);

        return $this->render('index', compact('users'));
    }

    public function actionEditField()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $response = ['pk' => $_POST['pk']];
            $user = User::findOne($_POST['pk']);
            if ($user) {
                $user->$_POST['name'] = $_POST['value'];
                $user->save();
                if ($_POST['name'] == 'tariff_id') {
                    $response['value'] = $user->getTariffName();
                } else {
                    $response['value'] = $user->$_POST['name'];
                }


                echo json_encode($response);
            }
        }
    }

    public function actionAddFunds()
    {
        if (!empty($_POST['sum']) && !empty($_POST['info']) && !empty($_POST['id'])) {
            $user = User::findOne($_POST['id']);

            if ($user) {
                if ($transaction = $user->completeInvoice($_POST['sum'], $_POST['info'], $_POST['id'])) {
                    $transaction = $transaction->attributes;

                    $transaction['action'] = 'Пополнение по счету (' . $transaction['description'] . ')';
                    $transaction['date'] = date('H:i:s d.m.Y', strtotime($transaction['created']));
                    if ($transaction['type'] == 'IN') {
                        $transaction['in'] = 1;
                    }
                    echo json_encode(['result' => 'OK', 'transactions' => [$transaction]]);
                }
            }
        }
    }
}