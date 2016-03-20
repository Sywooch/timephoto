/**
 * Created by slashman on 08.03.15.
 */
var express = require('express');
var fs = require('fs');
var busboy = require('connect-busboy');
var bodyParser = require('body-parser');
var app = express();
var busboyBodyParser = require('busboy-body-parser');
var colors = require('colors');

//app.use(busboy());
app.use(busboyBodyParser());

process.umask(0);

app.post('/api/watermark/set', function (req, res) {
    var fstream;
    var homeDir = req.body.homeDir;

    console.log(('Recieved request to upload watermark to: ' + homeDir).yellow);

    if (!fs.existsSync(homeDir)) {
        fs.mkdirSync(homeDir, 0777);
        console.log(('Created dir: ' + homeDir).yellow);
    }

    var newPath = homeDir + '/.watermark';
    fs.writeFile(newPath, req.files.watermark.data, {mode: 0777}, function (err) {
        if (err)
            console.log(err.red); else
            console.log(('Wrote file: ' + newPath).green);
    });
    res.end();//("back");
});

app.post('/api/watermark/remove', function (req, res) {
    var fstream;
    var watermarkPath = req.body.homeDir + '/.watermark';

    console.log(("Request to delete file: " + watermarkPath).yellow);

    if (fs.existsSync(watermarkPath)) {
        fs.unlinkSync(watermarkPath);
        console.log(("Deleted file: " + watermarkPath).green);
    }

    res.end();
});

/*Run the server.*/
app.listen(3000, function () {
    console.log("Working on port 3000");
});