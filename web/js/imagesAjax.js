/**
 * Created by slashman on 26.02.15.
 */

var currentImagesArray = [];
var currentPaginationArray = [];

function getImagesFromCamera(id, page, limit)
{
    $.get(
        yii.app.createUrl('/cabinet/ajax/get-images-from-camera', {id: id, page: page, limit: limit})
    ).done(function(response){
            currentImagesArray = JSON.parse(response);
    });
}