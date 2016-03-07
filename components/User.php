<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 12.10.15
 * Time: 4:31
 */

namespace app\components;

use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\web\IdentityInterface;
use Yii;

class User extends \yii\web\User
{

    protected function renewAuthStatus()
    {
        $session = Yii::$app->getSession();
        $id = $session->getHasSessionId() || $session->getIsActive() ? $session->get($this->idParam) : null;

        if ($id === null) {
            $identity = null;
        } else {
            /* @var $class IdentityInterface */
            $class = $session->get('IdentityClass');
            if($class) {
                $identity = $class::findIdentity($id);
            } else {
                $identity = null;
            }
        }

        if ($identity !== null && ($this->authTimeout !== null || $this->absoluteAuthTimeout !== null)) {
            $expire = $this->authTimeout !== null ? $session->get($this->authTimeoutParam) : null;
            $expireAbsolute = $this->absoluteAuthTimeout !== null ? $session->get($this->absoluteAuthTimeoutParam) : null;
            if ($expire !== null && $expire < time() || $expireAbsolute !== null && $expireAbsolute < time()) {
                $this->logout(false);
            } elseif ($this->authTimeout !== null) {
                $session->set($this->authTimeoutParam, time() + $this->authTimeout);
            }
        }

        if ($this->enableAutoLogin) {
            if ($this->getIsGuest()) {
                $this->loginByCookie();
            } elseif ($this->autoRenewCookie) {
                $this->renewIdentityCookie();
            }
        }

    }

    protected function loginByCookie()
    {
        $value = Yii::$app->getRequest()->getCookies()->getValue($this->identityCookie['name']);
        if ($value === null) {
            return;
        }

        $data = json_decode($value, true);
        if (count($data) !== 3 || !isset($data[0], $data[1], $data[2])) {
            return;
        }

        list ($id, $authKey, $duration) = $data;
        /* @var $class IdentityInterface */
        $session = Yii::$app->getSession();
        $class = $session->get('IdentityClass');
        if($class) {
            $identity = $class::findIdentity($id);
        } else {
            $identity = null;
        }
        if ($identity === null) {
            return;
        } elseif (!$identity instanceof IdentityInterface) {
            throw new InvalidValueException("$class::findIdentity() must return an object implementing IdentityInterface.");
        }

        if ($identity->validateAuthKey($authKey)) {
            if ($this->beforeLogin($identity, true, $duration)) {
                $this->switchIdentity($identity, $this->autoRenewCookie ? $duration : 0);
                $ip = Yii::$app->getRequest()->getUserIP();
                Yii::info("User '$id' logged in from $ip via cookie.", __METHOD__);
                $this->afterLogin($identity, true, $duration);
            }
        } else {
            Yii::warning("Invalid auth key attempted for user '$id': $authKey", __METHOD__);
        }
    }

    public function switchIdentity($identity, $duration = 0)
    {
        parent::switchIdentity($identity, $duration);

        $session = Yii::$app->getSession();

        $session->set('IdentityClass', get_class($identity));
    }

}