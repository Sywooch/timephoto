/**
 * Created by slashman on 08.03.15.
 */

var fs = require('fs-extra');

/**
 *
 */
function guessCorum(fileName) {
    if (fileName.indexOf('m') > 0) {
        return 'MOVE';
    }
    if (fileName.indexOf('i') > 0) {
        return 'ALERT';
    }

    return false;
}

/**
 *
 */
function guessHicVision(fileName) {
    if (fileName.indexOf('motion') > 0) {
        return 'MOVE';
    }
    if (fileName.indexOf('timing') > 0) {
        return 'ALERT';
    }

    return false;
}

/**
 *
 */
function extractDate(file, mask) {
    var formatData = {'y': '-', 'm': '-', 'd': ' ', 'h': ':', 'i': ':', 's': ''};
    var data = '';
    for (var index in formatData) {
        var p = stripos(mask, index);
        var c = 1;
        while (mask[p + c] === index) {
            c++;
        }
        data += file.substr(p, c) + formatData[index];
    }

    return data;
}

/**
 *
 */
function stripos(f_haystack, f_needle, f_offset) {
    // Find position of first occurrence of a case-insensitive string
    //
    // +     original by: Martijn Wieringa

    var haystack = f_haystack.toLowerCase();
    var needle = f_needle.toLowerCase();
    var index = 0;

    if (f_offset == undefined) {
        f_offset = 0;
    }

    if ((index = haystack.indexOf(needle, f_offset)) > -1) {
        return index;
    }

    return false;
}

/**
 *
 */
function getSize(path) {
    var fileStats = fs.statSync(path);
    return fileSizeInKilobytes = fileStats['size'] / 1024.0;
}


/**
 * Определение типа картинки
 */
function guessImageType(fileName) {
    var guessHicVisionResult = guessHicVision(fileName);
    var guessCorumResult = guessCorum(fileName);

    if (guessHicVisionResult)
        return guessHicVisionResult;

    if (guessCorumResult)
        return guessCorumResult;

    return 'SCHEDULE';
}

exports.getSize = getSize;
exports.guessImageType = guessImageType;
exports.extractDate = extractDate;