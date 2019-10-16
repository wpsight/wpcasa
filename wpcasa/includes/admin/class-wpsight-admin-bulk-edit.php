<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Bulk_Edit class
 */
class WPSight_Admin_Bulk_Edit {
	
	/**
	 *	Constructor
	 */
	public function __construct() {
		
		add_action( 'bulk_edit_custom_box', array( $this, 'bulkedit_listing' ), 10, 2 );

	}
	
	/**
	 *	bulkedit_listing()
	 *	
	 *	@uses	wp_nonce_field()
	 *	@uses	plugin_basename()
	 *	@uses	wpsight_offers()
	 *	
	 *	@since 1.1.0
	 */
	public function bulkedit_listing( $column_name, $post_type ) {
		
		var_dump( $column_name );
		
		static $printNonce = TRUE;
		if ( $printNonce ) {
			$printNonce = FALSE;
			wp_nonce_field( plugin_basename( __FILE__ ), 'listing_edit_nonce' );
		}
	
		?>
        
        <?php /*?><div class="inline-edit-group wp-clearfix">
            <label class="inline-edit-status alignleft">
                <span class="title">Status</span>
                <select name="_status">
                    <option value="publish">Published</option>
                    <option value="pending">Pending Review</option>
                    <option value="draft">Draft</option>
                </select>
            </label>
        </div><?php */?>
        
		<fieldset class="inline-edit-col-right inline-edit-listing">
		  <div class="inline-edit-group wp-clearfix column-<?php echo $column_name; ?>">
			<label class="inline-edit-offer alignleft">
			<?php 
			 switch ( $column_name ) {
			 case 'listing_offer':
				 ?>
                 	<span class="title"><?php _e( 'Offer', 'wpcasa' ); ?></span>
                    <select name="offer">
                    	<?php foreach( wpsight_offers() as $key => $value ) { ?>
                    	<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
				<?php
				 break;
			 }
			?>
			</label>
		  </div>
		</fieldset>
		<?php
		
	}
	
}