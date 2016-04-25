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
    var width = options.width;
    var height = options.height;

    bigImage
        .resize(width)
        .resize(width * 2, height * 2, ">")
        .gravity('Center')
        .crop(width, height, 0, 0)
        .quality(60)
        .noProfile()
        .write(options.thumbPath, function (error, stdout, stderr, command) {

        if (error) console.log('Error - ', error);

    });
}

exports.startWorkflow = startWorkflow;
