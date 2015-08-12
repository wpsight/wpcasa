jQuery(document).ready(function($) {
	
	/** LISTINGS COMPARE */
	
	function wpsight_listings_compare() {
	
		var btn = $('.listings-compare');
		var fade = $('.wpsight-listing-section-summary, .wpsight-listing-section-description');
		var comp = $('.wpsight-listing-section-compare');
		
		if ($.cookie(WPSight_Compare.compare_cookie) && $.cookie(WPSight_Compare.compare_cookie) == 'open') {
			fade.hide();
		    comp.show();
		    btn.addClass('open');
		}
		
		btn.live('click', function(e) {
			e.preventDefault();
			if ( comp.is(':visible') ) {
		    	comp.fadeOut(100, function() {
			    	btn.removeClass('open');
			    	fade.fadeIn(100);
		    	});
		    	$.cookie(WPSight_Compare.compare_cookie, 'closed',{ expires: 60, path: WPSight_Compare.compare_cookie_path });
		    } else {
		    	fade.fadeOut(100, function() {
			    	btn.addClass('open');
			    	comp.fadeIn(100);
		    	});
		    	$.cookie(WPSight_Compare.compare_cookie, 'open',{ expires: 60, path: WPSight_Compare.compare_cookie_path });
		    }
		});
	
	}
	
	wpsight_listings_compare();
	
});