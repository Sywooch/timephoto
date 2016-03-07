var Sequelize = require('sequelize');
var fs = require('fs');
var util = require("util");
var colors = require('colors');
var gm = require('gm');
var mkdirp = require('mkdirp');

var Settings = require('./settings.js');
var Functions = require('./functions.js');
var Guess = require('./image_types.js');
var Watermark = require('./thubnails.js');

var rootDir = Settings.fs.rootDir;

//var allowedImageTypes = ['PNG', 'JPEG']; //@todo
process.umask(0); //@todo

var sequelize = new Sequelize(Settings.mysql.db_name, Settings.mysql.db_login, Settings.mysql.db_password, {
    'host': 'localhost', 'logging': false,
});

var Tariff = sequelize.define('Tariff', {
    id: {type: Sequelize.INTEGER, primaryKey: true}, site_logo: Sequelize.INTEGER, watermark: Sequelize.INTEGER
}, {
    timestamps: false, tableName: 'tariff'
});

var User = sequelize.define('User', {
    id: {type: Sequelize.INTEGER, primaryKey: true}, hide_site_logo: Sequelize.INTEGER, tariff_id: {
        type: Sequelize.INTEGER, references: Tariff, referencesKey: "id"
    }
}, {
    timestamps: false, tableName: 'user'
});

var Camera = sequelize.define('Camera', {
    //id: {type: Sequelize.INTEGER, primaryKey: true, autoIncrementField: true },
    id: {
        type: Sequelize.INTEGER.UNSIGNED, allowNull: false, primaryKey: true, autoIncrement: true
    },
    name: Sequelize.STRING(45),
    ftp_login: Sequelize.STRING(45),
    ftp_password: Sequelize.STRING(45),
    ftp_home_dir: Sequelize.STRING(255),
    internal_id: Sequelize.INTEGER,
    registrator_id: Sequelize.INTEGER,
    deleted: Sequelize.INTEGER,
    enabled: Sequelize.INTEGER,
    created: Sequelize.STRING(45),
    camera_registrator_id: Sequelize.STRING(45),
    user_id: {
        type: Sequelize.INTEGER, references: User, referencesKey: "id"
    }
}, {
    timestamps: false, tableName: 'camera'
});

var Registrator = sequelize.define('Registrator', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    prefix: Sequelize.STRING(255),
    ftp_login: Sequelize.STRING(45),
    ftp_password: Sequelize.STRING(45),
    deleted: Sequelize.INTEGER,
    enabled: Sequelize.INTEGER,
    user_id: {
        type: Sequelize.INTEGER, references: User, referencesKey: "id"
    }
}, {
    timestamps: false, tableName: 'registrator'
});

var Image = sequelize.define('Image', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    file_name: Sequelize.TEXT,
    file_size: Sequelize.FLOAT(),
    created: Sequelize.DATE,
    type: Sequelize.STRING(45),
    camera_id: {
        type: Sequelize.INTEGER, references: Camera, referencesKey: "id"
    }
}, {
    timestamps: false, tableName: 'image'
});

Camera.hasMany(Image, {foreignKey: 'camera_id', timestamps: false});
Registrator.belongsTo(User, {foreignKey: 'user_id', timestamps: false});
//Registrator.hasMany(Camera, {foreignKey: 'registrator_id', timestamps: false});
Camera.belongsTo(User, {foreignKey: 'user_id', timestamps: false});
User.belongsTo(Tariff, {foreignKey: 'tariff_id', timestamps: false});

function PhotoScan() {
}

PhotoScan.prototype.run =
    function () {
        Registrator.findAll({
            where: {
                deleted: 0, enabled: 1
            }, // include: [{model: Camera}]
        }).then(function (registrators) {
            Functions.processArray(registrators, function (currentRegistrator) {

                var dir = rootDir + currentRegistrator.dataValues['ftp_login'] + '/';
                var userdir = rootDir + (currentRegistrator.dataValues['user_id'] + 10011000) + '/';
                var registrator_dir = userdir + currentRegistrator.dataValues['ftp_login'];

                if (!fs.existsSync(registrator_dir)) {
                    mkdirp(registrator_dir);
                }

                if (fs.existsSync(registrator_dir)) {
                    Functions.directoryWalk(registrator_dir, function (walkError, files) {
                        var cameraId = currentRegistrator.dataValues['id'];
                        var registrator_id = currentRegistrator.dataValues['id'];

                        if (files.length > 0) {
                            Functions.processArray(files, function (currentFile) {
                                var dir = rootDir + currentRegistrator.dataValues['ftp_login'] + '/';
                                var prefix = currentRegistrator.dataValues['prefix'];

                                if (prefix) {
                                    var filename = currentFile;

                                    var regular = '^' + prefix;
                                    var rePattern = new RegExp(regular, 'i');
                                    var arrMatches = filename.match(rePattern);
                                    var camera_prefix_id = 0;
                                    if (arrMatches.length == 2) {
                                        camera_prefix_id = arrMatches[1];
                                    }
                                    //var camera_prefix_id = currentFile.substr(currentRegistrator.dataValues['prefix'].toString().length, 3);

                                    // если префикс камеры содержится в наименовании файла
                                    if (parseInt(camera_prefix_id, 10) > 0) {

                                        var internal_id = parseInt(prefix, 10);
                                        var login = (currentRegistrator.dataValues['user_id'] + 10011000) + '_' + internal_id;
                                        var new_camera_dir = rootDir + (currentRegistrator.dataValues['user_id'] + 10011000) + '/' + login;

                                        if (!fs.existsSync(new_camera_dir)) {
                                            mkdirp(new_camera_dir);
                                        }

                                        Camera.findOrCreate({
                                            where: {
                                                camera_registrator_id: camera_prefix_id,
                                                registrator_id: registrator_id,
                                            }, defaults: {
                                                name: camera_prefix_id,
                                                ftp_login: login,
                                                internal_id: internal_id,
                                                registrator_id: registrator_id,
                                                user_id: currentRegistrator.dataValues['user_id'],
                                                ftp_home_dir: new_camera_dir,
                                                created: new Date().toLocaleString(),
                                                camera_registrator_id: camera_prefix_id,
                                                deleted: 0
                                            }
                                        }).spread(function (camera, created) {

                                            // тут настоящий internal_id из базы
                                            var internal_id = camera.get('internal_id');

                                            if (!fs.existsSync(new_camera_dir)) {
                                                fs.mkdirSync(new_camera_dir);
                                            }

                                            fs.renameSync(registrator_dir + '/' + filename, new_camera_dir + '/' + filename);
                                        });

                                    }
                                }
                            });

                        }
                        else {
                            Image.destroy({
                                where: {
                                    camera_id: cameraId
                                }
                            });
                        }
                    });

                }
            });
        });
    };

var photoScanner = new PhotoScan();
setInterval(function () {
    photoScanner.run()
}, 10000);
