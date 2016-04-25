var Sequelize = require('sequelize');
//var fs = require('fs');
var fs = require('fs-extra');
var util = require("util");
var colors = require('colors');
var mkdirp = require('mkdirp').sync;
var chownr = require('chownr').sync;
var mime = require('mime');
//var exif = require('exiv2');
var gm = require('gm').subClass({imageMagick: true});
var _0775 = parseInt('0775', 8);
var _0664 = parseInt('0664', 8);

var Settings = require('./settings.js');
var Functions = require('./functions.js');
var Guess = require('./image_properties.js');
var Thubnails = require('./thubnails.js');
var rootDir = Settings.fs.rootDir;
var user = Settings.fs.user;
var group = Settings.fs.group;

//var allowedImageTypes = ['PNG', 'JPEG']; //@todo
process.umask(0);

var sequelize = new Sequelize(Settings.mysql.db_name, Settings.mysql.db_login, Settings.mysql.db_password, {
    'host': 'localhost',
    'logging': false,
    'timezone': '+03:00' // Выставляем Москву + 3 часа
});

var Tariff = sequelize.define('Tariff', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    site_logo: Sequelize.INTEGER, watermark: Sequelize.INTEGER
}, {
    timestamps: false, tableName: 'tariff'
});

var User = sequelize.define('User', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    hide_site_logo: Sequelize.INTEGER,
    tariff_id: {type: Sequelize.INTEGER, references: Tariff, referencesKey: "id"}
}, {
    timestamps: false, tableName: 'user'
});

var Camera = sequelize.define('Camera', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    ftp_login: Sequelize.STRING(45),
    ftp_password: Sequelize.STRING(45),
    ftp_home_dir: Sequelize.STRING(255),
    format: Sequelize.STRING(50),
    deleted: Sequelize.INTEGER,
    enabled: Sequelize.INTEGER,
    user_id: {type: Sequelize.INTEGER, references: User, referencesKey: "id"},
    icon_name: Sequelize.STRING(50),
}, {
    timestamps: false, tableName: 'camera'
});

var Image = sequelize.define('Image', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    file_name: Sequelize.TEXT,
    file_size: Sequelize.FLOAT(),
    created: Sequelize.DATE,
    type: Sequelize.STRING(45),
    //exif: Sequelize.TEXT,
    camera_id: {type: Sequelize.INTEGER, references: Camera, referencesKey: "id"}
}, {
    timestamps: false, tableName: 'image'
});

Camera.hasMany(Image, {foreignKey: 'camera_id', timestamps: false});
Camera.belongsTo(User, {foreignKey: 'user_id', timestamps: false});
User.belongsTo(Tariff, {foreignKey: 'tariff_id', timestamps: false});

// Создание объекта
function PhotoScan() {
}

PhotoScan.prototype.run = function () {

    Camera.findAll({
        where: {
            deleted: 0, enabled: 1
        }, include: [{
            model: User,
            include: Tariff
        }]
    }).then(function (cameras) {

        Functions.processArray(cameras, function (currentCamera) {

            var userdir = rootDir + (currentCamera.dataValues['user_id'] + 10011000) + '/';
            var userInfo = currentCamera.dataValues['User'];
            var camera_dir = userdir + currentCamera.dataValues['ftp_login'];
            var ftp_homedir = currentCamera.dataValues['ftp_home_dir'];
            var format = currentCamera.dataValues['format'];
            var cameraId = currentCamera.dataValues['id'];
            var watermarkPath = Settings.fs.watermarkPath + currentCamera.dataValues['icon_name'];

            if (fs.existsSync(ftp_homedir)) {

                if (!fs.existsSync(camera_dir)) {
                    mkdirp(camera_dir, _0775);
                    chownr(camera_dir, user, group);
                }

                Functions.directoryWalk(ftp_homedir, ['.jpeg', '.jpg'], function (walkError, files) {

                    if (files.length > 0) {

                        Image.findAll({
                            where: {
                                camera_id: cameraId, file_name: {
                                    in: files
                                }
                            }
                        }).then(function (existingImages) {

                            for (var i = 0; i < existingImages.length; i++) {
                                var index = files.indexOf(existingImages[i].dataValues['file_name']);

                                if (index >= 0) {
                                    files.splice(index, 1);
                                }
                            }

                            if (files.length > 0) {
                                Functions.processArray(files, function (currentFile) {

                                    if (!fs.existsSync(ftp_homedir + "/" + currentFile)) {
                                        console.log('File ' + ftp_homedir + "/" + currentFile + ' not found');
                                        return;
                                    }

                                    var fileMimeType = mime.lookup(ftp_homedir + "/" + currentFile);

                                    if (fileMimeType.indexOf('image') !== -1) {

                                        var fileName = currentFile.replace('ended.', '');

                                        if (!fs.existsSync(camera_dir + "/" + fileName)) {

                                            var bigImage = gm(ftp_homedir + "/" + currentFile);

                                            if (userInfo['Tariff']['watermark'] == 1) {

                                                //WaterMark
                                                if (fs.existsSync(watermarkPath)) {
                                                    bigImage
                                                        .gravity('SouthEast x: 5, y: 5')
                                                        //.operator('Opacity', 'Assign', '50%')
                                                        .draw(['image over 10,10 0,0 "' + watermarkPath + '"']);
                                                }

                                            }

                                            //SaveChanges
                                            bigImage.write(camera_dir + "/" + fileName, function (e) {
                                                if (e) {
                                                    console.log(e);
                                                } else {

                                                    fs.unlinkSync(ftp_homedir + "/" + currentFile);

                                                    console.log('Copying... ' + camera_dir + "/" + fileName);

                                                    if (!fs.existsSync(camera_dir + '/.thumbs')) {
                                                        mkdirp(camera_dir + '/.thumbs', _0775);
                                                        chownr(camera_dir + '/.thumbs', user, group);
                                                    }

                                                    var fileThumb = camera_dir + '/.thumbs/' + fileName;

                                                    if (fs.existsSync(fileThumb)) {
                                                        fs.unlinkSync(fileThumb);
                                                    }

                                                    Image.create({
                                                        file_name: fileName,
                                                        created: Guess.extractDate(fileName, format),
                                                        file_size: Guess.getSize(camera_dir + '/' + fileName),
                                                        camera_id: cameraId,
                                                        type: Guess.guessImageType(camera_dir + "/" + fileName),
                                                        //exif: JSON.stringify(tags),
                                                    });

                                                    Thubnails.startWorkflow({
                                                        imagePath: camera_dir + "/" + fileName,
                                                        thumbPath: fileThumb,
                                                        width: 383,
                                                        height: 207
                                                    }, function () {

                                                        console.log('Thubnails image create ' + fileThumb);

                                                    });

                                                }
                                            })

                                        } else {

                                            console.log('File ' + camera_dir + "/" + fileName + ' exists');

                                            if (fs.existsSync(ftp_homedir + "/" + currentFile)) {
                                                fs.unlinkSync(ftp_homedir + "/" + currentFile);
                                            }

                                        }

                                    }

                                });
                            }

                        });

                    } else {

                        /*Image.destroy({
                         where: {
                         camera_id: cameraId
                         }
                         });*/

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