/**
 * Created by slashman on 08.03.15.
 */

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

exports.guessImageType = guessImageType;