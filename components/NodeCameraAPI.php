<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 08.03.15
 * Time: 22:04
 */

namespace app\components;

use Yii;

/**
 * @property Camera $_camera
 **/
class NodeCameraAPI
{
    public $port = 3000;
    private $_camera;
    private $_apiUrl;

    public function __construct($camera)
    {
        $this->_apiUrl = 'http://' . Yii::$app->params['host'] . ':' . $this->port . '/';
        $this->_camera = $camera;
    }

    public function updateWaterMark()
    {
        if (!$this->ping()) {
            return false;
        }

        $file_name_with_full_path = realpath('uploads/camera_icons/' . $this->_camera->icon_name);
        $post = array('homeDir' => $this->_camera->ftp_home_dir, 'watermark' => new \CURLFile($file_name_with_full_path));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_apiUrl . 'api/watermark/set');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function ping()
    {

        $waitTimeoutInSeconds = 5;

        if ($fp = fsockopen(Yii::$app->params['host'], $this->port, $errCode, $errStr, $waitTimeoutInSeconds)) {
            return true;
        } else {
            return false;
        }
    }

    public function removeWatermark()
    {
        if (!$this->ping()) {
            return false;
        }

        $post = array('homeDir' => $this->_camera->ftp_home_dir);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_apiUrl . 'api/watermark/remove');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}