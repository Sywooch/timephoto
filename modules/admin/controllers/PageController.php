<?php

/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 27.03.15
 * Time: 4:51
 */

namespace app\modules\admin\controllers;

use app\models\Page;
use app\modules\admin\components\AdminController;

class PageController extends AdminController
{
    public $layout = 'page';

    public function actionIndex()
    {
        $pages = Page::find()->all();
        return $this->render('index', compact('pages'));
    }

    public function actionSave()
    {
        if (isset($_POST['content']) && isset($_POST['title']) && isset($_POST['url']) && isset($_POST['id'])) {
            $page = new Page();
            if ($_POST['id']) {
                $page = Page::findOne($_POST['id']);
            }

            $page->load($_POST);
            $page->save();

            $response = $page->attributes;
            if ($page->header == '1') {
                $response['hasHeader'] = 1;
            }
            if ($page->footer == '1') {
                $response['hasFooter'] = 1;
            }
            echo json_encode($response);
        }
    }

    public function actionGet($id)
    {
        $page = Page::findOne($id);

        if ($page) {
            $response = $page->attributes;
            if ($page->header == '1') {
                $response['hasHeader'] = 1;
            }
            if ($page->footer == '1') {
                $response['hasFooter'] = 1;
            }
            echo json_encode($response);
        }
    }

    public function actionRemove($id)
    {
        Page::deleteAll(['id' => $id]);
    }
}