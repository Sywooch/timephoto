/**
 * Created by slashman on 08.03.15.
 */
var gm = require('gm').subClass({imageMagick: true});
var colors = require('colors');
var sizeOf = require('image-size');
var fs = require('fs');
var Settings = require('./settings.js');

function startWorkflow(options, callback) {
    var bigImage = gm(options.imagePath);
    var imageSize = sizeOf(options.imagePath);


    //Thumbnail
    var thumbSize = {width: 383, height: 207};

    bigImage
        .resize(thumbSize.width)
        //.resize(thumbSize.width*2, thumbSize.height*2 + ">")
        .gravity('Center')
        .crop(thumbSize.width, thumbSize.height, 0, 0)
        .quality(60)
        .write(options.thumbPath, function (error, stdout, stderr, command) {

            if (error) console.log('Error - ', error);

            var imageGm = gm(options.imagePath);

            if (fs.existsSync(options.watermarkPath) && (options.userInfo['Tariff']['watermark'] == 1)) {

                //WaterMark
                if (fs.existsSync(options.watermarkPath)) {
                    gm(options.imagePath)
                        .gravity('SouthEast x: 5, y: 5')
                        //.operator('Opacity', 'Assign', '50%')
                        .draw(['image over 10,10 0,0 "' + options.watermarkPath + '"'])
                        .noProfile()
                        .write(options.imagePath, function (e) {
                        console.log(('Added watermark to ' + options.imagePath + '...').green);
                    });
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
