/**
 * Created by slashman on 06.03.15.
 */

$(document).on('click', '.next-page', function() {
    nextPage();
});
$(document).on('click', '.previous-page', function() {
    previousPage();
});

$(document).on('click', '.go-to-page', function() {
    moveToPage(parseInt($(this).attr('page-number') ));
});
$(document).on('click', '.toggle-slideshow', function() {
    if($(this).hasClass('active'))
    {
        stopSlideShow();
    }
    else
    {
        startSlideShow();
    }
});



function previousPage() {
    if(currentPage !== 1)
        moveToPage(currentPage - 1);
}
function nextPage() {
    if(currentPage !== pagesCount)
        moveToPage(currentPage + 1);
}
function moveToPage(page) {
    var imageThumbnailsContainer = $('.camera-thumbnails .image-thumbnails:first');
    var html = '';
    for(var i = (page - 1) * limit; i < Math.min(page * limit, jsonImages.length); i++)
    {
        html = html +
        '<div class="col-md-6 thumbnail-container " image-id="' + jsonImages[i].id + '">' +
            '<div class="panel panel-default">' +
                '<div class="panel-body" image-index="' + i + '">' +
                    '<div class="row image-container">' +
                        '<img src="' + jsonImages[i].thumb + '" class="img-responsive cam-thumb" full-img="' + jsonImages[i].big + '">' +
                    '</div>' +
                '</div>' +
                '<div class="panel-footer">' +
                    '<div class="row danger">' +
                        '<div class="col-md-10">' + jsonImages[i].created + '</div>' +
                            '<div class="col-md-2">' +
                                '<input type="checkbox" class="pull-right thumb-check" image-id="' + jsonImages[i].id + '">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
            '</div>' +
        '</div>';
    }
    $(imageThumbnailsContainer).html(html);

    currentPage = page;

    updatePagination();
}

function updatePagination() {
    var paginationContainer = $('.pagination');

    var html = '';
    var previousDisabled = '';
    var nextDisabled = '';

    if(currentPage == 1)
        previousDisabled = ' disabled';
    if(currentPage == pagesCount)
        nextDisabled = ' disabled';

    html += '<li class="previous-page' + previousDisabled + '">' +
    '<a href="#" aria-label="Previous">' +
    '<span aria-hidden="true">&laquo;</span>' +
    '</a>' +
    '</li>';

    for (var i = Math.max(1, currentPage - 3); i <= Math.min(pagesCount, currentPage + 3); i++)
    {
        var activeClass = '';
        if(i == currentPage)
            activeClass = ' active';

        html += '<li page-number="' + i + '" class="go-to-page' + activeClass + '"><a href="#">' + i + '</a></li>';
    }
    html += '<li class="next-page' + nextDisabled + '">' +
    '<a href="#" aria-label="Next">' +
    '<span aria-hidden="true">&raquo;</span>' +
    '</a>' +
    '</li>';

    $(paginationContainer).html(html);
}