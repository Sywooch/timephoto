<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 14.08.15
 * Time: 4:45
 */

namespace app\components;

use yii\base\ErrorException;
use yii\web\UploadedFile as BaseUploadedFile;

class UploadedFile extends BaseUploadedFile
{

    public $chmod = 775;

    public function saveAs($file, $deleteTempFile = true)
    {
        $path = dirname($file);

        if (!file_exists($path)) {
            mkdir($path, octdec($this->chmod), true);
            if (!file_exists($path)) {
                return new ErrorException('Folder doest created.');
            }
        }

        return parent::saveAs($file, $deleteTempFile);
    }

}