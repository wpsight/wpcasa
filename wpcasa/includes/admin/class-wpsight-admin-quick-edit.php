<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Quick_Edit class
 */
class WPSight_Admin_Quick_Edit {
	
	/**
	 *	Constructor
	 */
	public function __construct() {
		
//		add_action( 'quick_edit_custom_box',	array( $this, 'quickedit_listing' ), 10, 2 );
//		add_action( 'save_post',				array( $this, 'save_listing_meta' ), 10, 2 );

	}
	
	/**
	 *	quickedit_listing()
	 *	
	 *	@uses	wp_nonce_field()
	 *	@uses	plugin_basename()
	 *	@uses	wpsight_offers()
	 *	
	 *	@since 1.1.0
	 */
	public function quickedit_listing( $column_name, $post_type ) {
		
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
	
	/**
	 *	save_listing_meta()
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	current_user_can()
	 *	@uses	wp_verify_nonce()
	 *	@uses	plugin_basename()
	 *	@uses	update_post_meta()
	 *	
	 *	@since 1.1.0
	 */
	public function save_listing_meta( $post_id ) {
		
		/* in production code, $slug should be set only once in the plugin,
		   preferably as a class property, rather than in each function that needs it.
		 */
		
		$slug = wpsight_post_type();
		if ( $slug !== $_POST['post_type'] ) {
			return;
		}
		if ( !current_user_can( 'edit_listing', $post_id ) ) {
			return;
		}
		$_POST += array("{$slug}_edit_nonce" => '');
		if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"],
							   plugin_basename( __FILE__ ) ) )
		{
			return;
		}
	
		if ( isset( $_REQUEST['offer'] ) ) {
			update_post_meta( $post_id, '_price_offer', $_REQUEST['offer'] );
		}
		# checkboxes are submitted if checked, absent if not
//		if ( isset( $_REQUEST['inprint'] ) ) {
//			update_post_meta($post_id, 'inprint', TRUE);
//		} else {
//			update_post_meta($post_id, 'inprint', FALSE);
//		}
	}	
	
}