jQuery(document).ready(function($) {
	$( "tr" ).each(function( index ) {
		if ( $(this).attr('data-slug') === 'wpcasa-admin-map-ui' ) {
			$(this).find('.activate').css('display', 'none');
		}

		if ( $(this).attr('data-slug') === 'wpcasa-listings-map' ) {
			$(this).find('.activate').css('display', 'none');
		}
	});

});
