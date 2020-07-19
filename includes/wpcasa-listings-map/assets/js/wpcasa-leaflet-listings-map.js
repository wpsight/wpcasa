(function($) {

var mapOptions = wpsightMap.map;

if( 'true' != mapOptions.map_page ) {
    if($.cookie(mapOptions.cookie) != 'closed') {
        $('#map-toggle-' + mapOptions.id).show();
        $('#map-toggle-' + mapOptions.id + ' .toggle-map').addClass('open');
        initialize();
    }

    $('.toggle-map').click(function(e) {
        e.preventDefault();
        if ($('#map-toggle-' + mapOptions.id).is(':visible')) {

            $.cookie(mapOptions.cookie, 'closed',{ expires: 60, path: mapOptions.cookie_path });

            $('#' + mapOptions.id).animate(
                {
                    opacity: '0'
                },
                100,
                function(){
                    $('#map-toggle-' + mapOptions.id + ' .toggle-map').removeClass('open');
                    $('#map-toggle-' + mapOptions.id).slideUp(150);
                }
            );
        }
        else {
            $('#map-toggle-' + mapOptions.id).slideDown(150, function(){

                $.cookie(mapOptions.cookie, 'open',{ expires: 60, path: mapOptions.cookie_path });

                $('#' + mapOptions.id).animate(
                    {
                        opacity: '1'
                    },
                    100
                );
                initialize(mapOptions.id);
                $('#map-toggle-' + mapOptions.id + ' .toggle-map').addClass('open');
            });
        }
    });

}

    var map_init = $('.map-init').attr('id');

    if( undefined !== map_init && map_init.length ) {
        initialize(map_init);
    }

}(jQuery));

function initialize(  ) {

    var container = L.DomUtil.get(wpsightMap.map.id);
    if(container != null){
        container._leaflet_id = null;
    }

    // map.invalidateSize();
    var map = new L.Map(wpsightMap.map.id, {
        zoom: 10,
        scrollWheelZoom: ( wpsightMap.map.scrollwheel === 'true' )
    });


     var mapType = wpsightMap.map.mapTypeId;
     if( mapType === 'TERRAIN' ) {
         customLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
             maxZoom: 20,
             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
         });
     } else {
         customLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
             attribution: null
         });
     }
     map.addLayer(customLayer);

    var markers = new L.MarkerClusterGroup();
    var markersList = [];


    var customOptions = {
         'maxWidth': '500',
         'minWidth': '300',
         'className' : 'custom-leaflet-popup',
         'closeButton' : ( wpsightMap.map.infobox_close === 'true' )
     };


    for ( var i = wpsightMap.map.markers.length - 1; i >= 0; i-- ) {
        // closeButton
        var markerOptions = wpsightMap.map.markers[i];
        var path = new L.Polyline(new L.LatLng(parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)) );;

        marker = new L.Marker([parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)] );
        marker.bindPopup(markerOptions.infobox.content, customOptions);

        if ( wpsightMap.map.infobox_event === 'mouseover' ) {
            marker.on('mouseover',function(ev) {
                ev.target.openPopup();
            });
        } else {
            marker.on('click',function(ev) {
                ev.target.openPopup();
            });
        }

        markersList.push(marker);
        markers.addLayer(marker);
        map.addLayer(path);
    }
    map.addLayer(markers);

    map.fitBounds(wpsightMap.map.markers, {padding: [100,100]});

}


