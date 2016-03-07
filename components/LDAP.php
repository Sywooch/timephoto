<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 13.02.15
 * Time: 2:14
 */

namespace app\components;

use Yii;

class LDAP
{
    private $_group = 'ftpusers';
    private $_server = 'mastaki.pro';
    private $_admin_login = 'cn=admin,dc=mastaki,dc=pro';
    private $_admin_password = '884088';
    private $_home_dir = '/home/users/';
    private $_baseDN = 'dc=mastaki,dc=pro';

    private $_ldap;

    private $_login;

    public function __construct()
    {
        $this->_home_dir = Yii::$app->params['cameraDir'];
        $this->_login = Yii::$app->user->identity->getName();
        $this->_ldap = ldap_connect($this->_server);
        ldap_set_option($this->_ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_bind($this->_ldap, $this->_admin_login, $this->_admin_password);
    }

    public function addUser($userName, $userPass)
    {
        $info ["uid"] = $userName;
        $info ["cn"] = $userName;
        $info ["sn"] = $userName;
        $info ["objectClass"][0] = "inetOrgPerson";
        $info ["objectClass"][1] = "posixAccount";
        $info ["objectClass"][2] = "top";
        $info ['userPassword'] = '{MD5}' . base64_encode(pack('H*', md5($userPass)));
        $info ["loginShell"] = "/bin/bash";
        $info ["uidNumber"] = "1004";
        $info ["gidNumber"] = "1004";
        $info ["homeDirectory"] = $this->_home_dir . '/' . $userName;

        return ldap_add($this->_ldap, "cn=" . $userName . ",cn=" . $this->_group . "," . $this->_baseDN, $info);
    }
}