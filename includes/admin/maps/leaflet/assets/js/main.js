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
     * Get value from lng-input
     * @param context
     * @returns {{from, to}|T|*|{line, ch}|{}}
     */
    var getZoom = function (map_zoom_field) {
        return parseInt( $('option:selected', map_zoom_field).val() );
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

  var map_type	= $('[name="_map_type"]');

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
    var map_zoom_field	= $('[name="_map_zoom"]');

    var map = L.map(mapId, {
      center: [
        CMB2LM.initial_coordinates.lat,
        CMB2LM.initial_coordinates.lng
      ],
      scrollWheelZoom: false,
      zoom: CMB2LM._map_zoom
    });

    addMarker(latLng, map, marker);

    marker.on('moveend', function (data) {
      handleLatLngChange(context, data.target._latlng);

      geocodeService.reverse().latlng(data.target._latlng).run(function (error, result) {
        $('#' + search_input).val(result.address.LongLabel);

      });
    });

    setMapType(CMB2LM.tilelayer);

    // create an empty layer group to store the results and add it to the map
    var search = L.esri.BootstrapGeocoder.search({
      inputTag: search_input,
      placeholder: 'Location',
      allowMultipleResults: false
    }).addTo(map);


    search.on('results', function(data){
      $('#' + search_input).val(data.text);
      setTimeout(function(){
        map.setView( data.latlng, getZoom(map_zoom_field) );
      }, 500);
      marker.setLatLng(data.latlng);
      handleLatLngChange(context, data.latlng);
    });
      // Change zoom level
      map_zoom_field.change(function() {
        map.setZoom(getZoom(map_zoom_field));
      });


    function setMapType( mapType ) {
      customLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: null
      });

      if( mapType === 'TERRAIN' ) {
        map.removeLayer(customLayer);
        customLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
          maxZoom: 20,
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
        });
      }

      map.addLayer(customLayer);
    }

    // Change map type
    map_type.change(function() {
      var map_type_val = $(this).filter(':checked').val();
      setMapType( map_type_val );
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
