(function ($) {
  var marker;
  var search_input = '_map_address';

  /**
   * Default marker
   */
  var createMarker = function (latLng = null) {
    marker = new L.Marker(latLng, { draggable: true });

    return marker
  };

  /**
   * Add marker to map
   * @param latLng
   * @param marker
   * @param map
   */
  var addMarker = function (latLng, map, marker) {
    marker.setLatLng(latLng).addTo(map);
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
   * Handle adding latLng to inputs
   * @param context
   * @param latLng
   */
  var handleLatLngChange = function (context, latLng) {
    getLatField(context).val(latLng.lat);
    getLngField(context).val(latLng.lng);
  };

  /**
   * Initialize Leaflet to map-container
   * @param context
   * @param index
   */
  var initMap = function (context, index) {
    var marker = createMarker();
    var mapId = 'cmb2-leaflet-map_' + index;
    var geocodeService = L.esri.Geocoding.geocodeService();
    var latLng = new L.latLng(CMB2LM.initial_coordinates.lat, CMB2LM.initial_coordinates.lng);

    var map = L.map(mapId, {
      center: [
        CMB2LM.initial_coordinates.lat,
        CMB2LM.initial_coordinates.lng
      ],
      zoom: CMB2LM._map_zoom
    });


    addMarker(latLng, map, marker);


    marker.on('moveend', function (data) {
      handleLatLngChange(context, data.target._latlng);

      geocodeService.reverse().latlng(data.target._latlng).run(function (error, result) {
        $('#' + search_input).val(result.address.LongLabel);

      });
    });

    L.tileLayer(CMB2LM.tilelayer, {
      attribution: null
    }).addTo(map);

    // create an empty layer group to store the results and add it to the map
    var search = L.esri.BootstrapGeocoder.search({
      inputTag: search_input,
      placeholder: 'Location',
      allowMultipleResults: false
    }).addTo(map);

    search.on('results', function(data){
      $('#' + search_input).val(data.text);

      for (var i = data.results.length - 1; i >= 0; i--) {
        marker.setLatLng(data.results[i].latlng);

        handleLatLngChange(context, data.latlng);
      }
    });
  };

  /**
   * Initialize on load
   */
  $('.cmb-type-leaflet-map').each(function (i) {
    $(this).find('.cmb2-leaflet__container').attr('id', 'cmb2-leaflet-map_' + i);
    initMap($(this), i);
  });

})(jQuery);
