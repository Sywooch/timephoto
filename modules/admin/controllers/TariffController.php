<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 09.03.15
 * Time: 4:06
 */

namespace app\modules\admin\controllers;

use app\models\Tariff;
use app\models\TariffDuration;
use app\models\Transaction;
use app\modules\admin\components\AdminController;

class TariffController extends AdminController
{
    public $layout = 'tariff';

    public function actionIndex()
    {
        $tariffs = Tariff::find()->all();
        $tariffsTransposed = Tariff::getAllTransposed();
        $transactions = Transaction::findAll(['type' => 'IN', 'status' => 'COMPLETED']);

        return $this->render('index', compact('tariffsTransposed', 'tariffs', 'transactions'));
    }

    public function actionJsonGetAll()
    {
        $json = [];
        $tariffs = Tariff::find()->all();
        foreach ($tariffs as $tariff) {
            $json[] = ['value' => $tariff->id, 'text' => $tariff->name];
        }

        echo json_encode($json);
    }

    public function actionEditField()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $tariff = Tariff::findOne($_POST['pk']);
            if ($tariff) {
                $tariff->$_POST['name'] = $_POST['value'];
                $tariff->save();

                if (in_array($_POST['name'], Tariff::getBooleanAttributes())) {
                    $value = $_POST['value'] == '1' ? 'Да' : 'Нет';
                } elseif (in_array($_POST['name'], Tariff::getUnlimitedAttributes())) {
                    $value = $_POST['value'] == '-1' ? 'Безлимитно' : $_POST['value'];
                } else {
                    $value = $_POST['value'];
                }

                echo $value;
            }
        }
    }

    public function actionEditPrice()
    {
        if (!empty($_POST['pk']) && !empty($_POST['name']) && (!empty($_POST['value']) || $_POST['value'] == 0)) {
            $tariffDuration = TariffDuration::findOne($_POST['pk']);
            if ($tariffDuration) {
                $tariffDuration->$_POST['name'] = $_POST['value'];
                $tariffDuration->save();
                echo $tariffDuration->price;
            }
        }
    }
}