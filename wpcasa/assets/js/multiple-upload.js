jQuery( document ).ready( function() {

	jQuery( document ).on( 'click', '.cmb-multiple-upload', function(e) {

		e.preventDefault();

		var link = jQuery( this );
		var container = jQuery( this ).parent();
		var field = link.closest('.field');

		var frameArgs = {
			multiple: true,
            button: {
                text: cmb_multiple_upload.text_button
            },
			title: cmb_multiple_upload.text_title
		}

		library = container.attr( 'data-type' ).split(',');
		if ( library.length > 0 )
			frameArgs.library = { type: library }

		var CMB_Frame = wp.media( frameArgs );

		CMB_Frame.on( 'select', function() {

			var selection = CMB_Frame.state().get('selection');

			CMB_Frame.close();			

			// Loop through selected attachments
			selection.forEach( function( model, index ) {

				if ( 0 == index ) {
					// use existing fileholder
					fillFileholders( model, link )
				} else {
					// pretend user clicked "Add new" button to create new file holder
					field.find('.button.repeat-field').trigger('click', [ model, link ] );
				}
			    
			});

		});

		CMB_Frame.open();

	} );

	jQuery( document ).on( 'click', '.cmb-remove-file', function(e) {

		e.preventDefault();

		var container = jQuery( this ).parent().parent();

		container.find( '.cmb-file-holder' ).html( '' ).hide();
		container.find( '.cmb-file-upload-input' ).val( '' );
		container.find( '.cmb-file-upload' ).show().css( 'display', 'inline-block' );
		container.find( '.cmb-remove-file' ).hide();

	} );

	jQuery( document ).on( 'click', '.WPSight_Image_Multiple_Field .button.repeat-field, .WPSight_File_Multiple_Field .button.repeat-field', function(e, model, link) {
		e.preventDefault();
		if ( model && link ) {
			fillFileholders( model, link );
		}
	});

	var fillFileholders = function( model, link ){

		// update variables to newly created file holders
		var container = link.closest('.field').find('.field-item:not(.hidden):last').find('.cmb-file-wrap'),
			link = container.find('.cmb-multiple-upload'),
			fileHolder = container.find( '.cmb-file-holder' );

		jQuery( container ).find( '.cmb-file-upload-input' ).val( model.id );

		link.hide(); // Hide 'add media' button

		fileHolder.html( '' );
		fileHolder.show();
		fileHolder.siblings( '.cmb-remove-file' ).show();

		var fieldType = container.closest( '.field-item' ).attr( 'data-class' );

		if ( 'WPSight_Image_Multiple_Field' === fieldType ) {

			var data = {
				action: 'cmb_request_image',
				id:     model.attributes.id,
				width:  container.width(),
				height: container.height(),
				crop:   fileHolder.attr('data-crop'),
				nonce:  link.attr( 'data-nonce' )
			}

			fileHolder.addClass( 'cmb-loading' );

			jQuery.post( ajaxurl, data, function( src ) {
				// Insert image
				jQuery( '<img />', { src: src } ).prependTo( fileHolder );
				fileHolder.removeClass( 'cmb-loading' );
			}).fail( function(fail) {
				// Fallback - insert full size image.
				jQuery( '<img />', { src: model.attributes.url } ).prependTo( fileHolder );
				fileHolder.removeClass( 'cmb-loading' );
			});

		} else {

			jQuery( '<img />', { src: model.attributes.icon } ).prependTo( fileHolder );
			fileHolder.append( jQuery('<div class="cmb-file-name" />').html( '<strong>' + model.attributes.filename + '</strong>' ) );

		}
	}

	/**
	 * Recalculate the dimensions of the file upload field.
	 * It should never be larger than the available width.
	 * It should maintain the aspect ratio of the original field.
	 * It should recalculate when resized.
	 * @return {[type]} [description]
	 */
	var recalculateFileFieldSize = function() {

		jQuery( '.cmb-file-wrap' ).each( function() {

			var el        = jQuery(this),
				container = el.closest( '.postbox' ),
				width     = container.width() - 12 - 10 - 10,
				ratio     =  el.height() / el.width();

			if ( el.attr( 'data-original-width' ) )
				el.width( el.attr( 'data-original-width' ) );
			else
				el.attr( 'data-original-width', el.width() );

			if ( el.attr( 'data-original-height' ) )
				el.height( el.attr( 'data-original-height' ) );
			else
				el.attr( 'data-original-height', el.height() );

			if ( el.width() > width ) {
				el.width( width );
				el.find( '.cmb-file-wrap-placeholder' ).width( width - 8 );
				el.height( width * ratio );
				el.css( 'line-height', ( width * ratio ) + 'px' );
				el.find( '.cmb-file-wrap-placeholder' ).height( ( width * ratio ) - 8 );
			}


		} );
	}

	recalculateFileFieldSize();
	jQuery(window).resize( recalculateFileFieldSize );

} );