/**
 * Created by mirocow on 22.08.15.
 */


exports.mysql = {

    //db_name: 'cameras',
    db_name: 'timephoto_loc',

    //db_login: 'cuser1',
    db_login: 'root',

    // db_password: 'vpPBx7clS6'
    db_password: 'password'

};

exports.fs = {
    user: 501,
    group: 20,
    rootDir: '/usr/local/var/www/timephoto.loc/yii2/web/public/',
    watermarkPath: '/usr/local/var/www/timephoto.loc/yii2/web/uploads/camera_icons/'    
};

exports.site = {
    logo: '/usr/local/var/www/timephoto.loc/yii2/bin/node/logo.png'
};