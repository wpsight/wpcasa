jQuery(document).ready(function($) {
	jQuery( '.wpsight-remove-uploaded-file' ).click(function() {
		jQuery(this).closest( '.wpsight-uploaded-file' ).remove();
		return false;
	});
});