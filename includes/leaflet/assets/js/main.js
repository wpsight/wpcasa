(function ($) {

  /**
   * Maps on DOM
   * @type {Array}
   */
  var maps = [];

  /**
   * Default marker
   */
  var createMarker = function () {
    return new L.Marker(null, { draggable: true });
  };

  /**
   * Get value from lat-input
   * @param context
   * @returns {{from, to}|T|*|{line, ch}|{}}
   */
  var getLatField = function (context) {
    return context.find('.leaflet-map__lat');
  };

  /**
   * Get value from lng-input
   * @param context
   * @returns {{from, to}|T|*|{line, ch}|{}}
   */
  var getLngField = function (context) {
    return context.find('.leaflet-map__lng');
  };

  /**
   * Generate marker html-string
   * @param markerCenter
   * @returns {string}
   */
  var setMarkerHtml = function (markerCenter) {
    return "<span class='leaflet-map__popup'>" + markerCenter.lat + ", " + markerCenter.lng + "</span>";
  };

  /**
   * Add marker to map
   * @param markerCenter
   * @param map
   */
  var addMarker = function (markerCenter, map, marker) {
    marker
      .setLatLng(markerCenter)
      .bindPopup(setMarkerHtml(markerCenter))
      .addTo(map);

    marker.openPopup();
    marker.on('moveend', function (e) {
      marker.openPopup();
    });

    // map.setView(markerCenter, CMB2LM.default_zoom);
  };

  /**
   * Handle adding latLng to inputs
   * @param e
   * @param context
   */
  var handleLatLngChange = function (e, context) {
    var latLng = e.layer ? e.layer.getLatLng() : new L.latLng(e.lat, e.lng);

    getLatField(context).val(latLng.lat);
    getLngField(context).val(latLng.lng);
  };

  /**
   * Initialize Leaflet to map-container
   * @param context
   * @param index
   */
  var initMap = function (context, index) {

    var mapId = 'cmb2-leaflet-map_' + index;
    var marker = createMarker();

    var latFieldVal = getLatField(context).val();
    var lngFieldVal = getLngField(context).val();
    // alert(CMB2LM.initial_coordinates.lat);
    // alert(CMB2LM.initial_coordinates.lng);

    var map = L.map(mapId, {
      center: [
        CMB2LM.initial_coordinates.lat,
        CMB2LM.initial_coordinates.lng
      ],
      zoom: CMB2LM._map_zoom
    });


    if (latFieldVal && lngFieldVal) {
      var markerCenter = new L.latLng(CMB2LM.initial_coordinates.lat, CMB2LM.initial_coordinates.lng);
      addMarker(markerCenter, map, marker);
    }


    var tiles = L.esri.basemapLayer("Streets").addTo(map);

    // create the geocoding control and add it to the map
    var searchControl = L.esri.Geocoding.geosearch().addTo(map);

    // create an empty layer group to store the results and add it to the map
    var results = L.layerGroup().addTo(map);


    var search = BootstrapGeocoder.search({
      inputTag: '_map_address',
      placeholder: 'ex. LAX'
    }).addTo(map);

    search.on('results', function(data){
      document.getElementById(mapId).style.display = 'block';
      // document.getElementById('intro').style.display = 'none';

      // http://leafletjs.com/reference.html#map-invalidatesize
      // map.invalidateSize();
      results.clearLayers();
      for (var i = data.results.length - 1; i >= 0; i--) {
        results.addLayer(L.marker(data.results[i].latlng));
      }
    });



    map.on('layeradd', function (e) {
      return handleLatLngChange(e, context)
    });



   // If the map with given ID is already instantiated => return early
   //  if (L.DomUtil.get(mapId)._leaflet_id) {
    //   return;
    // }
    //
    // var marker = createMarker();
    // var geoCoder = new L.Control.Geocoder({
    //   geocoder: null,
    //   showResultIcons: false,
    //   collapsed: true,
    //   expand: 'click',
    //   position: CMB2LM.searchbox_position,
    //   placeholder: CMB2LM.search,
    //   errorMessage: CMB2LM.not_found
    // });
    // var latFieldVal = getLatField(context).val();
    // var lngFieldVal = getLngField(context).val();
    // var map = L.map(mapId, {
    //   center: [
    //     CMB2LM.initial_coordinates.lat,
    //     CMB2LM.initial_coordinates.lng
    //   ],
    //   zoom: CMB2LM.initial_zoom
    // });
    //
    // L.tileLayer(CMB2LM.tilelayer, {
    //   attribution: null
    // }).addTo(map);
    //
    // geoCoder.addTo(map);
    //
    // // Check if fields are populated
    // if (latFieldVal && lngFieldVal) {
    //   var markerCenter = new L.latLng(latFieldVal, lngFieldVal);
    //   addMarker(markerCenter, map, marker);
    // }
    //
    // // Handle marker position add / change
    // map.on('layeradd', function (e) {
    //   return handleLatLngChange(e, context)
    // });
    //
    // // Remove default markers on geocoder results
    // map.on('geocoderMarkerAdd', function () {
    //   return map.removeLayer(marker);
    // });
    //
    // maps.push(map);

  };

  /**
   * Initialize on load
   */
  $('.cmb-type-leaflet-map').each(function (i) {
    $(this).find('.cmb2-leaflet__container').attr('id', 'cmb2-leaflet-map_' + i);
    initMap($(this), i);
  });

  /**
   * Handle new row add
   */
  // $('.cmb-repeatable-group').on('cmb2_add_row', function (event, newRow) {
  //   var wrap = $(newRow).closest('.cmb-repeatable-group');
  //
  //   wrap.find('.cmb-row.cmb-repeatable-grouping').each(function (i) {
  //     $(this).closest('.cmb2-leaflet__container').attr('id', 'cmb2-leaflet-map_' + i);
  //     initMap($(this), i);
  //   });
  // });

  /**
   * Trigger invalidateSize() when meta box is opened
   */
  // $(document).on('postbox-toggled', function (e) {
  //   for (var i = 0; i < maps.length; i++) {
  //     var map = maps[i];
  //     if (map && map._leaflet_id) {
  //       var mapCenter = map.getCenter();
  //       var mapZoom = map.getZoom();
  //       map.invalidateSize();
  //       map.setView(mapCenter, mapZoom);
  //     }
  //   }
  // });
})(jQuery);
