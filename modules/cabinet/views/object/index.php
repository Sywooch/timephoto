<?php
/* @var $this ObjectController */
/* @var $jsonLocations String */

$this->registerJsFile("http://maps.google.com/maps/api/js?sensor=false&language=ru");
$this->registerJsFile(Yii::$app->homeUrl . "fw/gmaps.js", ['position' => yii\web\View::POS_HEAD]);

$i = 1;

?>
<div id="gmap" class="gmap"></div>

<script>
    var map; //Google map
    var overlay;
    var markers = []; //Map markers
    var jsonLocations = JSON.parse('<?=$jsonLocations?>');
    var returnMapMarkerTo = null;

    $(document).ready(function () {
        initialize();
        App.init();
        makeDraggable($('.map-marker'));
        $('#gmap').droppable();
    });
    function placeNewMarker(location, id) {
        $.post(yii.app.createUrl('cabinet/object/ajax-set-new-lat-lon'), {
            lat: location.lat(), lon: location.lng(), id: id
        }).done(function (response) {
            addMarker(JSON.parse(response));
        });
    }

    function initialize() {
        var myLatlng = new google.maps.LatLng(55.770475, 37.612559);
        var myOptions = {
            zoom: 10, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("gmap"), myOptions);
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {
        };
        overlay.setMap(map);

        jsonLocations.forEach(function (location, index, array) {
            if (location.lat !== null && location.lon !== null)
                addMarker(location);
        });
    }

    function addMarker(location) {
        var infowindow = new google.maps.InfoWindow({
            content: generateBalloon(location)
        });

        var marker = new google.maps.Marker({
            id: location.id,
            draggable: true,
            position: new google.maps.LatLng(location.lat, location.lon),
            map: map,
            title: location.name
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {
            var newLat = event.latLng.lat();
            var newLon = event.latLng.lng();
            $.post(yii.app.createUrl('cabinet/object/ajax-set-new-lat-lon'), {
                lat: newLat, lon: newLon, id: marker.id
            }).done(function (response) {
            });
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        var markerContainer = {
            marker: marker, infoWindow: infowindow
        };
        markers.push(markerContainer);
    }

    function generateBalloon(location) {
        return '<div class="marker-balloon" location-id="' + location.id + '">' + '<div class="col-md-12">' + '<div class="page-header" class="balloon-name">' + location.name + '</div>' + '</div>' + '<div class="col-md-12">' + '<button class="btn btn-sm btn-danger remove-marker" location-id="' + location.id + '" title="Убрать место с карты"><i class="fa fa-trash"></i></button></div>' + '</div>' + '</div>';
    }

    function makeDraggable(object) {
        $(object).draggable({
            helper: "clone", start: function (event, ui) {
                var startX = event.pageX;
                var startY = event.pageY;
                returnMapMarkerTo = $(ui.helper).parent();
                $(ui.helper).detach().appendTo('body').css({
                    'padding-top': startY + 'px',
                    'padding-left': startX + 'px'
                });
                $('.map-marker[location-id=' + $(ui.helper).attr('location-id') + ']:first').hide();
            }, /*revert : function(event, ui) {
             $(this.context).css({'padding-left': 0, 'padding-top': 0});
             $(this).data("uiDraggable").originalPosition = {
             top : 5,
             left : 0,
             'padding-left': 0,
             'padding-top': 0,
             'height': 'auto'
             };
             /$(this.context).detach().appendTo(returnMapMarkerTo).css({height: 'auto'}).removeClass('ui-draggable-dragging');
             makeDraggable(this.context);
             return !event;
             },*/
            stop: function (e, ui) {
                if (e.pageX > 220 && e.pageY > 40) {
                    var point = new google.maps.Point(e.pageX - 220, e.pageY);
                    var ll = overlay.getProjection().fromContainerPixelToLatLng(point);
                    placeNewMarker(ll, parseInt($(ui.helper).attr('location-id')));
                    $(ui.helper).detach().remove();
                    $('.map-marker[location-id=' + $(ui.helper).attr('location-id') + ']:first').remove();
                } else {
                    $(ui.helper).remove();
                    $('.map-marker[location-id=' + $(ui.helper).attr('location-id') + ']:first').show();
                }
            }
        });
    }

    $(document).on('click', '.remove-marker', function (e) {
        var locationId = $(this).attr('location-id');

        $('.locations-list-element[location-id=' + locationId + '] a span').after('<i class="fa fa-map-marker map-marker pull-right text-success" location-id="' + locationId + '" title="Перетащите маркер на карту"></i>');

        makeDraggable($('.locations-list-element[location-id=' + locationId + '] .map-marker'));
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].marker.id == locationId) {
                removeGeoLocation(i);
                break;
            }
        }

        $.post(yii.app.createUrl('cabinet/object/ajax-purge-location'), {
            id: locationId
        }).done(function (response) {
        });
    });

    function removeGeoLocation(index) {
        markers[index].marker.setMap(null);
        markers.splice(index, 1);
    }
</script>