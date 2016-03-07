<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 14.03.15
 * Time: 11:52
 */

namespace app\components;

class SMS
{
    private $_login = 'solarsystems';
    private $_password = 'fbars7';
    private $_sourceAddress = 'OBLACAM';

    /**
     * @param $to string
     * @param $message string
     * @return string
     */
    public function send($to, $message)
    {
        $url = 'http://gateway.api.sc/get/?user=' . urlencode($this->_login) . '&pwd=' . urlencode($this->_password) . '&sadr=' . urlencode($this->_sourceAddress) . '&dadr=' . urlencode($to) . '&text=' . urlencode($message);

        return file_get_contents($url);
    }

    /**
     * @param $user User
     * @return string
     */
    public function sendCode($user)
    {

        $code = rand(1000, 9999);
        $message = 'Ваш код: ' . $code;

        $user->sms_code = $code;
        $user->save();
        if ($user->phone) {
            $url = 'http://gateway.api.sc/get/?user=' . urlencode($this->_login) . '&pwd=' . urlencode($this->_password) . '&sadr=' . urlencode($this->_sourceAddress) . '&dadr=' . urlencode($user->phone) . '&text=' . urlencode($message);

            return file_get_contents($url);
        } else {
            return false;
        }
    }
}