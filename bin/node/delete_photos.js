/**
 * Created by slashman on 21.02.15.
 */
var Sequelize = require('sequelize');
var fs = require('fs');
var util = require("util");
var colors = require('colors');

var Settings = require('./settings.js');
var Functions = require('./functions.js');

var rootDir = Settings.fs.rootDir;

var sequelize = new Sequelize(Settings.mysql.db_name, Settings.mysql.db_login, Settings.mysql.db_password, {
    'host': 'localhost', 'logging': false
});

var Camera = sequelize.define('Camera', {
    id: {
        type: Sequelize.INTEGER, primaryKey: true
    },
    ftp_login: Sequelize.STRING(45),
    ftp_password: Sequelize.STRING(45),
    storage_time: Sequelize.INTEGER,
    delete: Sequelize.INTEGER
}, {
    timestamps: false, tableName: 'camera'
});

var Image = sequelize.define('Image', {
    id: {type: Sequelize.INTEGER, primaryKey: true},
    file_name: Sequelize.TEXT,
    created: Sequelize.DATE,
    deleted: Sequelize.INTEGER,
    sifted: Sequelize.INTEGER,
    camera_id: {
        type: Sequelize.INTEGER, references: Camera, referencesKey: "id"
    }
}, {
    timestamps: false, tableName: 'image'
});

Camera.hasMany(Image, {foreignKey: 'camera_id', timestamps: false});
Image.belongsTo(Camera, {foreignKey: 'camera_id', timestamps: false});

function DeletePhotos() {
}

DeletePhotos.prototype.removeImages = function (images) {
    if (images.length > 0) {
        Functions.processArray(images, function (image) {
            var thumbFile = rootDir + image.dataValues['Camera']['ftp_login'] + '/.thumbs/' + image.dataValues['file_name'];
            var file = rootDir + image.dataValues['Camera']['ftp_login'] + '/' + image.dataValues['file_name'];

            if (fs.existsSync(thumbFile)) {
                fs.unlinkSync(thumbFile);
                console.log('Delete thumb: ' + image.dataValues['id']);
            }
            if (fs.existsSync(file)) {
                fs.unlinkSync(file);
                console.log('Delete image: ' + image.dataValues['id']);
            }
            image.destroy();
            //console.log(image.dataValues['id']);
        });
    }
};

DeletePhotos.prototype.run = function () {

    Camera.findAll().then(function (cameras) {
        Functions.processArray(cameras, function (camera) {

            var storageTime = camera.dataValues['storage_time'];

            if (!storageTime){
                return;
            }

            camera.getImages({
                where: Sequelize.and({
                    sifted: {ne: 1}
                }, Sequelize.or(Sequelize.literal('NOW() > DATE_ADD(Image.created, INTERVAL ' + storageTime + ' DAY)'), {deleted: 1})),
                include: [Camera]
            }).then(function (images) {
                var deleteMode;

                if (deleteMode = parseInt(camera.dataValues['delete'])) {
                    deleteMode = (deleteMode === null || deleteMode === '' || typeof(deleteMode) === 'undefined') ? 1 : deleteMode;
                    for (var i = 0; i < images.length; i++) {
                        if (((i + 1) % deleteMode != 0 || deleteMode != 1) && (parseInt(images[i].dataValues['deleted']) !== 1)) {
                            images[i].dataValues['sifted'] = 1;
                            images[i].save();
                            images.splice(i, 1);
                        }
                    }

                    console.log(images);
                    deletePhotos.removeImages(images);
                }
            });
        });
    });
};


var deletePhotos = new DeletePhotos();
setInterval(function () {
    deletePhotos.run()
}, 10000);