(function($) {

	var maps = [];

	var CMBGmapsInit = function( fieldEl ) {
		var search		= $('#_map_address');
		var searchInput = search.get(0);
		var mapCanvas   = $('.map', fieldEl ).get(0);
		var latitude    = $('._map_geolocation_lat', fieldEl );
		var longitude   = $('._map_geolocation_long', fieldEl );
		var elevation   = $('._map_geolocation_elevation', fieldEl );
		var elevator    = new google.maps.ElevationService();
		var map_type	= $('[name="_map_type"]');
		var map_zoom	= $('[name="_map_zoom"]');
		var streetview	= $('[name="_map_no_streetview"]');
		// Set map options
		var mapOptions = {
			center:    			new google.maps.LatLng( CMBGmaps.latitude, CMBGmaps.longitude ),
			zoom:      			parseInt( CMBGmaps._map_zoom ),
			scrollwheel: 		(CMBGmaps.scrollwheel == "true"),
			streetViewControl:	(CMBGmaps._map_no_streetview != "on")
		};

		// Create map
		var map = new google.maps.Map( mapCanvas, mapOptions );

		// Set initial map type
		setMapType( CMBGmaps._map_type );

		// Set marker options
		var markerOptions = {
			map: map,
			draggable: true,
			title: CMBGmaps.markerTitle
		};

		// Create new marker
		var marker = new google.maps.Marker( markerOptions );
		marker.setPosition( mapOptions.center );

		// Set stored Coordinates
		// if ( latitude.val() && longitude.val() ) {
		// 	latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
		// 	setPosition( latLng, parseInt( CMBGmaps._map_zoom ))
		// }

		// Click on map sets location
		window.google.maps.event.addListener( map, 'click', function ( event ) {
			setPosition( event.latLng );
			setSearchInput( event.latLng );
		} );
		// Drag marker sets location
		window.google.maps.event.addListener( marker, 'dragend', function() {
			setPosition( marker.getPosition() );
			setSearchInput( marker.getPosition() );
		});


		// Search with autocomplete
		var autocomplete = new google.maps.places.Autocomplete(searchInput);
		autocomplete.bindTo('bounds', map);

		// Set location after autocomplete
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			setPosition( place.geometry.location );
		});

		$(searchInput).keypress(function(e) {
			if (e.keyCode === 13) {
				e.preventDefault();
			}
		});

		// Change map type
		map_type.change(function() {
			var map_type_val = map_type.filter(':checked').val();
			setMapType( map_type_val );
		});

		// Change zoom level
		map_zoom.change(function() {
			var map_zoom_val = $('option:selected',this).val();
			map.setZoom( parseInt(map_zoom_val) );
		});

		// Toggle streetview
		streetview.change(function() {
			if($(this).is(":checked")) {
				map.setOptions({ streetViewControl: false });
			} else {
				map.setOptions({ streetViewControl: true });
			}
		});

		// Set marker position
		function setPosition( latLng, zoom ) {

			marker.setPosition( latLng, CMBGmaps.zoom );

			setTimeout(function() {
				map.panTo(marker.getPosition());
    		}, 500);

			if ( zoom ) {
				map.setZoom( zoom );
			}

			latitude.val( latLng.lat() );
			longitude.val( latLng.lng() );

			elevator.getElevationForLocations( { locations: [ marker.getPosition() ] }, function (results, status) {
				if (status == google.maps.ElevationStatus.OK && results[0] ) {
					elevation.val( results[0].elevation );
				}
			});

		}

		// Set search input
		function setSearchInput( latLngPos ) {
			var latlng 	= new google.maps.LatLng( latLngPos.lat(), latLngPos.lng() );
			geocoder 	= new google.maps.Geocoder();

    	    geocoder.geocode({'latLng': latlng}, function(results, status) {
    	    	if (status == google.maps.GeocoderStatus.OK) {
    	        	if (results[0]) {
    	        		search.val(results[0].formatted_address);
    	        	} else {
    	        		search.val('invalid address');
    	        	}
				} else {
    	        	//alert('Geocoder failed due to: ' + status);
    	    	}
    	    });
		}

		// Set map type
		function setMapType( mapType ) {
			if( mapType == 'ROADMAP' ) 		{ map.setMapTypeId(google.maps.MapTypeId.ROADMAP); }
			if( mapType == 'SATELLITE' )	{ map.setMapTypeId(google.maps.MapTypeId.SATELLITE); }
			if( mapType == 'HYBRID' ) 		{ map.setMapTypeId(google.maps.MapTypeId.HYBRID); }
			if( mapType == 'TERRAIN' )		{ map.setMapTypeId(google.maps.MapTypeId.TERRAIN); }
		}

		maps.push( map );

	}

	$( '.cmb-type-map' ).each(function() {
		CMBGmapsInit( $(this) );
	});


	// Resize map when meta box is opened
	if ( typeof postboxes !== 'undefined' ) {
		postboxes.pbshow = function () {
			var arrayLength = maps.length;
			for (var i = 0; i < arrayLength; i++) {
				var mapCenter = maps[i].getCenter();
				google.maps.event.trigger(maps[i], 'resize');
				maps[i].setCenter(mapCenter);
			}
		};
	}

}(jQuery));