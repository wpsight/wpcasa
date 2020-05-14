 var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var map = new L.Map(wpsightMap.map.id, {
        zoom: 10,
        layers: [tiles],
        scrollWheelZoom: false
    });

    var markers = new L.MarkerClusterGroup();
    var markersList = [];

    var customOptions = {
         'maxWidth': '500',
         'minWidth': '300',
         'className' : 'custom-leaflet-popup'
     }

	for ( var i = wpsightMap.map.markers.length - 1; i >= 0; i-- ) {
        var markerOptions = wpsightMap.map.markers[i]
        var path = new L.Polyline(new L.LatLng(parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)) );;

		marker = new L.Marker([parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)] );
        marker.bindPopup(markerOptions.infobox.content, customOptions).openPopup();
        markersList.push(marker);
        markers.addLayer(marker);
        map.addLayer(path);
	}
    map.addLayer(markers);

    map.fitBounds(wpsightMap.map.markers, {padding: [100,100]});