<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 26.02.15
 * Time: 22:38
 */
namespace app\modules\cabinet\controllers;


use app\models\Image;
use Yii;
use ZipArchive;

class DownloadController extends \app\modules\cabinet\components\CabinetController
{
    private $dir = '/files/zip/';

    public function actionDownloadZip($id = null, $count = null, $from = null, $to = null)
    {
        $imagesSearch = new Image;
        $imagesSearch = $imagesSearch::find();

        $images = Yii::$app->request->get('images');

        if (!empty($images)) {
            $files = explode(',', $images);
            if (count($files) > 0) {

                $imagesSearch->joinWith('camera');
                $imagesSearch->where = (['camera.user_id' => Yii::$app->user->identity->userId]);
                $imagesSearch->andFilterWhere(['image.deleted' => '0']);
                $imagesSearch->andFilterWhere(['camera.id' => $from]);
                $imagesSearch->andFilterWhere(['image.id' => $files]);
            } else {
                die();
            }
        } elseif ($id !== null && $from !== null && $to !== null && $count !== null) {

            $count = (int)Yii::$app->request->get('count');

            if ($count <= 10 && $count >= 1) {
                $imagesSearch->with = ['camera'];
                $imagesSearch->andFilterWhere(['t.deleted' => '0']);
                $imagesSearch->andFilterWhere(['t.camera_id' => $id]);
                $imagesSearch->andFilterWhere(['camera.user_id' => Yii::$app->user->identity->userId]);
                if (!empty($from) && !empty($to)) {
                    $imagesSearch->andFilterWhere('t.created' > ':from');
                    $imagesSearch->andFilterWhere('t.created' < ':to');
                }
                $imagesSearch->where([
                    'user_id' => Yii::$app->user->identity->userId,
                    '' => $from,
                    '' => $to,
                    '' => $id,
                ]);
                if (!empty($from) && !empty($to)) //@todo Костыль
                {
                    $imagesSearch->params = [
                        'user_id' => Yii::$app->user->identity->userId,
                        '' => $from,
                        '' => $to,
                        '' => $id,
                    ];
                } else {
                    $imagesSearch->where = ([
                        'user_id' => Yii::$app->user->identity->userId,
                        '' => $id,
                    ]);
                }

            } else {
                die();
            }
        } else {
            die();
        }

        $images = $imagesSearch->all();

        if ($count !== null && $count !== 1) {
            for ($i = 0; $i < count($images); $i++) {
                if (($i + 1) % $count !== 0 || $count === 1) {
                    $images[$i] = null;
                }
            }
        }

        if (count($images) > 0) {

            $zipName = Yii::$app->user->identity->getName() . '_' . date('Y_m_d_H_i_s') . '.zip';

            $this->dir = Yii::getAlias('@webroot') . $this->dir;

            if (!file_exists($this->dir)) {
                return false;
            }

            $zip = new ZipArchive();
            $zip->open($this->dir . $zipName, ZipArchive::CREATE);
            foreach ($images as $image) {
                if ($image !== null) {
                    $imageSrc = file_get_contents($image->absoluteFileName);
                    $zip->addFromString($image->file_name, $imageSrc);
                }
            }
            $zip->close();

            $zipFile = $this->dir . $zipName;

            if (!file_exists($zipFile)) {
                die('Can`t zipped file ' . $zipFile);
            }

            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $zipName);
            header('Content-Length: ' . filesize($zipFile));

            readfile($this->dir . $zipName);
        }


    }
}