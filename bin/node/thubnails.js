/**
 * Created by slashman on 08.03.15.
 */
var gm = require('gm').subClass({imageMagick: true});
;
var colors = require('colors');
var sizeOf = require('image-size');
var fs = require('fs');

var Settings = require('./settings.js');

/*function addWaterMark(imagePath, watermarkPath) {
 gm(imagePath).size(function (imageError, imageSize) {
 if (!imageError) {

 gm(watermarkPath).size(function (watermarkError, watermarkSize) {
 if (!watermarkError) {
 gm(imagePath).draw(['image Over 10,10 0,0 ' + watermarkPath]).write(imagePath, function (e) {
 console.log(('Added watermark to ' + imagePath + '...').green);
 });
 }
 });
 }
 });
 }*/

/*function addSiteLogo(imagePath) {
 var watermarkPath = 'logo.png';

 gm(imagePath).size(function (imageError, imageSize) {
 if (!imageError) {
 var imageWidth = imageSize.width;

 gm(watermarkPath).size(function (watermarkError, watermarkSize) {
 if (!watermarkError) {
 var watermarkWidth = watermarkSize.width;

 gm(imagePath).draw(['image Over ' + (imageWidth - watermarkWidth - 10) + ', 10 0,0 ' + watermarkPath]).write(imagePath, function (e) {
 console.log(('Added sitelogo to ' + imagePath + '...').green);
 });
 }
 });
 }
 });
 }*/

function startWorkflow(options, callback) {
    var bigImage = gm(options.imagePath);
    var imageSize = sizeOf(options.imagePath);


    //Thumbnail
    var thumbSize = {width: 383, height: 207};

    bigImage
    //.resize(383)
    //.resize(thumbSize.width*2, thumbSize.height*2 + ">")
    //.gravity('Center')
    .crop(thumbSize.width, thumbSize.height, 0, 0)
    //.quality(60)
    .write(options.thumbPath, function (error, stdout, stderr, command) {

        if (error) console.log('Error - ', error);

        var imageGm = gm(options.imagePath);

        //.thumb(370, 200, rootDir + currentCamera.dataValues['ftp_login'] + '/.thumbs/' + files[fileNumber], 80, function(){});
        //.extent(thumbSize.width, thumbSize.height)
        //.quality(60)
        //.write(rootDir + currentCamera.dataValues['ftp_login'] + '/.thumbs/' + files[fileNumber], function (error) {
        //    if (error) console.log('Error - ', error);
        //});
        //.gravity('Center')
        //.thumb(370, 200, rootDir + currentCamera.dataValues['ftp_login'] + '/.thumbs/' + files[fileNumber], 80, function(){});

        if (((options.userInfo['hide_site_logo'] == 0) && (options.userInfo['Tariff']['site_logo'] == 1)) ||
            (fs.existsSync(options.watermarkPath) && (options.userInfo['Tariff']['watermark'] == 1))) {

            //SiteLogo
            /*if ((options.userInfo['hide_site_logo'] == 0) && (options.userInfo['Tariff']['site_logo'] == 1)) {
             var siteLogoSize = sizeOf(Settings.site.logo);
             imageGm.draw(['image Over ' + (imageSize.width - siteLogoSize.width - 10) + ',10 0,0 ' + Settings.site.logo]);
             console.log(('Added sitelogo to ' + options.imagePath + '...').green);
             }*/

            //WaterMark
            if (fs.existsSync(options.watermarkPath) && (options.userInfo['Tariff']['watermark'] == 1)) {
                imageGm.draw(['image Over 10,10 0,0 ' + options.watermarkPath]);
                console.log(('Added watermark to ' + options.imagePath + '...').green);
            }

            //SaveChanges
            imageGm.write(options.thumbPath, function (e) {
                if (e) {
                    console.log(e);
                } else {
                    callback();
                }
            });

        }

    });
}

exports.startWorkflow = startWorkflow;