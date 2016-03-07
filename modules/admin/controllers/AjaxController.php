<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 09.03.15
 * Time: 3:11
 */

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\components\AdminController;

class AjaxController extends AdminController
{
    public function actionGetClient($id)
    {
        $client = User::findOne($id);

        $clientInfo = $client->attributes;
        $clientInfo['accountNumber'] = $client->getAccountNumber();
        $clientInfo['tariff'] = $client->tariff->name;
        $clientInfo['balance'] = $client->getBalance() . 'p.';
        $clientInfo['expires'] = $client->getTariffExpireDate() ? $client->getTariffExpireDate() : ' - ';

        $cameras = [];
        $transactions = [];
        $users = [];

        foreach ($client->cameras as $camera) {
            if (!$camera->deleted) {
                $element = $camera->attributes;
                $element['totalSize'] = $camera->getTotalSize();
                $element['occupiedPercent'] = $camera->getOccupiedPercent();
                $cameras[] = $element;
            }
        }

        foreach ($client->additionalUsers as $additionalUser) {
            $users[] = $additionalUser->attributes;
        }

        foreach ($client->transactions as $transaction) {
            if ($transaction->status === 'COMPLETED') {
                if ($transaction->type == 'IN') {
                    if ($transaction->method == 'ROBOKASSA') {
                        $action = 'Пополнение через ' . $transaction->method;
                    } elseif ($transaction->method == 'INVOICE') {
                        $action = 'Пополнение по счету (' . $transaction->description . ')';
                    } else {
                        $action = 'Пополнение';
                    }
                } else {
                    if ($transaction->tariffDuration) {
                        $action = 'Оплачен тариф <strong>"' . $transaction->tariffDuration->tariff->name . '"</strong> пакет <strong>"' . $transaction->tariffDuration->duration->name . '"</strong>';
                    } else {
                        $action = 'Оплата услуг';
                    }
                }

                $element = [
                    'id' => $transaction->id,
                    'date' => date('H:i:s d.m.Y', strtotime($transaction->created)),
                    'amount' => $transaction->amount,
                    'action' => $action,
                ];
                if ($transaction->type == 'IN') {
                    $element['in'] = 1;
                }

                $transactions[] = $element;
            }
        }

        echo json_encode(['client' => $clientInfo, 'cameras' => $cameras, 'transactions' => $transactions, 'users' => $users]);
    }
}