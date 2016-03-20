var fs = require('fs');

function processArray(items, process) {
    var todo = items.concat();

    setTimeout(function () {

        process(todo.shift());

        if (todo.length > 0) {
            //setTimeout(arguments.callee, 2);
            setTimeout(processArray(todo, process), 20);
        }

    }, 20);
}

function getHost(url) {
    var a = document.createElement('a');
    a.href = urls[x];
    return a.hostname;
}

function getPath(url) {
    var host = 'http://' + getHost(url);
    return url.replace(host, '');
}

function currencyExists(currencies, currency) {
    var id = 0;
    currencies.some(function (dbCurrency) {
        if (dbCurrency.dataValues['code'].toLowerCase() == currency.toLowerCase()) {
            id = dbCurrency.dataValues['id'];
            return id;
        }
    });
    return id;
}

function getFileName(rootDir, fileName) {
    return str_replace(rootDir, '', fileName);
}

function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
}

function directoryWalk(dir, done, rootDir) {
    var results = [];
    if (typeof rootDir === 'undefined')
        rootDir = dir;

    var list = fs.readdirSync(dir);

    var i = 0;
    (function next() {
        var file = list[i++];
        if (!file) {
            return done(null, results);
        }

        fs.stat(dir + '/' + file, function (err, stat) {

            if (file.indexOf('.ftpquota') < 0 && file.indexOf('.thumbs') < 0) {

                if (stat && stat.isDirectory()) {

                    directoryWalk(file, function (err, res) {

                        results = results.concat(res);
                        next();

                    }, rootDir);

                } else {

                    var fileName = getFileName(rootDir, file);

                    if (fileName.indexOf('ended.') === 0) {
                        results.push(getFileName(rootDir, file));
                    }

                    next();
                }

            } else {
                next();
            }

        });
    })();
}

exports.processArray = processArray;
exports.getHost = getHost;
exports.getPath = getPath;
exports.currencyExists = currencyExists;
exports.directoryWalk = directoryWalk;