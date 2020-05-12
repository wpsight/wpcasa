// jQuery(document).ready(function($) {

console.log(wpsightMap.map);



	// var map = L.map(wpsightMap.map.id, {
	// 	center: [
	// 		50,
	// 		50
	// 	],
	// 	scrollWheelZoom: false,
	// 	zoom: 5
	// });


    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }),
        latlng = new L.LatLng(50.5, 30.51);

    var map = new L.Map(wpsightMap.map.id, {center: latlng, zoom: 15, layers: [tiles]});

    var markers = new L.MarkerClusterGroup();
    var markersList = [];





	for ( var i = wpsightMap.map.markers.length - 1; i >= 0; i-- ) {

        var markerOptions = wpsightMap.map.markers[i];


		marker = new L.Marker([parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)]);

        markersList.push(marker);
        markers.addLayer(marker);

        var path = new L.Polyline(new L.LatLng(parseFloat(markerOptions.lat), parseFloat(markerOptions.lng)) );
        map.addLayer(path);

	}

    map.addLayer(markers);




    // function populateRandomVector() {
    //     for (var i = 0, latlngs = [], len = 20; i < len; i++) {
    //         latlngs.push(getRandomLatLng(map));
    //     }
    //     var path = new L.Polyline(latlngs);
    //     map.addLayer(path);
    // }


    // populate();
    // map.addLayer(markers);





// });
