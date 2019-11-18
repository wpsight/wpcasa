jQuery(document).ready(function($) {

	// Tooltips

	$( ".tips, .help_tip" ).tipTip({
		'attribute' : 'data-tip',
		'fadeIn' : 50,
		'fadeOut' : 50,
		'delay' : 200,
		'defaultPosition' : 'top'
	});

	// Fade out the save message
	$('.fade').delay(2500).fadeOut(250);

	// Switches option sections

	$('.settings_panel').hide();
	var active_tab = '';

	// Get active tab from local storage

	if (typeof(localStorage) != 'undefined' ) {
		active_tab = localStorage.getItem("active_tab");
	}

	// Get active tab from URL hash

	var url  = window.location.href;
	var hash = url.substring(url.indexOf("#")+1);

	if( hash.substring( 0, 9 ) == 'settings-' ) {
		active_tab = '#' + hash;
	}

	// If no active tab, fade in first

	if (active_tab != '' && $(active_tab).length ) {
		$(active_tab).fadeIn(200);
	} else {
		$('.settings_panel:first').fadeIn(200);
	}

	if (active_tab != '' && $(active_tab + '-tab').length ) {
		$(active_tab + '-tab').addClass('nav-tab-active');
	}
	else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}

	// Switch tab on click

	$('.nav-tab-wrapper a').click(function(evt) {
		$('.nav-tab-wrapper a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active').blur();
		var clicked_group = $(this).attr('href');
		if (typeof(localStorage) != 'undefined' ) {
			localStorage.setItem("active_tab", $(this).attr('href'));
		}
		$('.settings_panel').hide();
		$(clicked_group).fadeIn(200);
		evt.preventDefault();

	});

	$('.wpsight-addons .type-download a, .wpsight-themes .type-download a').attr('target','_blank');
	$('.download-wrapper .type-download .download-meta-price-details a').addClass('button');


	// Switch navbar on click

	$('.wpsight-admin-main-btn-toggle').click( function (e) {
		e.preventDefault();

		$(this).toggleClass("wpsight-admin-main-btn-toggle-active");

		$(".wpsight-settings-wrap").toggleClass("wpsight-settings-wrap-hide-navbar");
	});


	// Working with listing fields

    //var themeName = theme_name;

	var workWithListingFields = function () {
		var totoggle_currency = '.setting-wpcasa_currency_other-tr, .setting-wpcasa_currency_other_ent-tr';

		jQuery('#setting-wpcasa_currency').change(function() {
			if( jQuery(this).val() == 'other' ) {
				jQuery(totoggle_currency).fadeIn(150);
			} else {
				jQuery(totoggle_currency).fadeOut(150);
			}
		});

		var totoggle_details = $(".setting-wpcasa_heading_details-tr ~[class^=setting-wpcasa_details_]");

		jQuery('#setting-wpcasa_listing_features').click(function() {
			totoggle_details.fadeToggle(150);
		});

		if (jQuery('#setting-wpcasa_listing_features:checked').val() !== undefined) {
			totoggle_details.show();
		}


		var totoggle_periods = $(".setting-wpcasa_heading_rental_periods-tr ~[class^=setting-wpcasa_rental_period_]");

		jQuery('#setting-wpcasa_rental_periods').click(function() {
			totoggle_periods.fadeToggle(150);
		});

		if (jQuery('#setting-wpcasa_rental_periods:checked').val() !== undefined) {
			totoggle_periods.show();
		}


		jQuery('.addon-inactive').hide();

		jQuery('#addons-all').click(function(e) {
			e.preventDefault();
			jQuery('.addon-active').show();
			jQuery('.addon-inactive').show();
		});

		jQuery('#addons-active').click(function(e) {
			e.preventDefault();
			jQuery('.addon-active').show();
			jQuery('.addon-inactive').hide();
		});

		jQuery('#addons-inactive').click(function(e) {
			e.preventDefault();
			jQuery('.addon-active').hide();
			jQuery('.addon-inactive').show();
		});
	};

	workWithListingFields();

});
