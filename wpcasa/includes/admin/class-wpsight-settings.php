<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	WPSight_Admin_Settings class
 */
class WPSight_Admin_Settings {

	/**
	 *	Constructor
	 */
	public function __construct() {
		
		$this->settings_group = WPSIGHT_DOMAIN;
		$this->settings_name  = WPSIGHT_DOMAIN;
		
		add_action( 'admin_init',			array( $this, 'register_settings' ) );
		add_filter( 'admin_body_class',		array( $this, 'admin_body_class' ) );
		
	}
	
	/**
	 *	admin_body_class()
	 *	
	 *	@since 1.1.0
	 */
	public function admin_body_class( $classes ) {
		$classes .= ' wpsight-settings-page ';
		return $classes;
	}
	
	/**
	 *	init_settings()
	 *	
	 *	@access	protected
	 *	@uses	get_editable_roles()
	 *	@uses	apply_filters()
	 *	@uses	wpsight_options()
	 *	
	 *	@since 1.0.0
	 */
	protected function init_settings() {

		// Prepare roles option
		$roles         = get_editable_roles();
		$account_roles = array();

		foreach ( $roles as $key => $role ) {
			if ( $key == 'administrator' )
				continue;
			$account_roles[ $key ] = $role['name'];
		}

		$this->settings = apply_filters( 'wpsight_settings', wpsight_options() );

	}

	/**
	 *	register_settings()
	 *	
	 *	@access	public
	 *	@uses	$this->init_settings()
	 *	@uses	get_option()
	 *	@uses	add_option()
	 *	@uses	wpsight_options_defaults()
	 *	@uses	register_setting()
	 *	
	 *	@since 1.0.0
	 */
	public function register_settings() {

		$this->init_settings();
	    
	    // If no settings available, set defaults
	    
	    if( get_option( $this->settings_name ) === false )
	    	add_option( $this->settings_name, wpsight_options_defaults() );

		register_setting( $this->settings_group, $this->settings_name );

	}

	/**
	 *	output()
	 *	
	 *	@access	public
	 *	@uses	$this->init_settings()
	 *	@uses	settings_fields()
	 *	@uses	flush_rewrite_rules()
	 *	@uses	wpsight_options_defaults()
	 *	@uses	update_option()
	 *	@uses	wpsight_get_option()
	 *	@uses	do_action()
	 *	@uses	submit_button()
	 *	
	 *	@since 1.0.0
	 */
	public function output() {
		
		$this->init_settings(); ?>

		<div class="wrap wpsight-settings-wrap">
		
			<h2><?php echo WPSIGHT_NAME . ' ' . __( 'Settings', 'wpcasa' ); ?></h2>
			
			<?php
				if ( isset( $_POST['reset'] ) ) {
					flush_rewrite_rules();
					update_option( $this->settings_name, wpsight_options_defaults() );
					echo '<div class="updated fade wpsight-updated"><p>' . __( 'Settings reset.', 'wpcasa' ) . '</p></div>';
				} elseif ( isset( $_GET['settings-updated'] ) ) {
					flush_rewrite_rules();
					echo '<div class="updated fade wpsight-updated"><p>' . __( 'Settings saved.', 'wpcasa' ) . '</p></div>';
				}
			?>
		
			<form method="post" action="options.php">

				<?php settings_fields( $this->settings_group ); ?>

			    <div class="nav-tab-wrapper">
			    	<?php
			    		foreach ( $this->settings as $key => $section ) {
			    			echo '<a href="#settings-' . sanitize_title( $key ) . '" id="settings-' . sanitize_title( $key ) . '-tab" class="nav-tab">' . esc_html( $section[0] ) . '</a>';
			    		}
			    	?>
			    </div>

				<?php

					foreach ( $this->settings as $key => $section ) {

						echo '<div id="settings-' . sanitize_title( $key ) . '" class="settings_panel">';

						echo '<table class="form-table">';

						foreach ( $section[1] as $option ) {
							
							$option_id			= isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
							$option_css			= sanitize_html_class( $this->settings_name . '_' . $option['id'] );
							
							$option_name		= isset( $option['name'] )				? $option['name']									: '';
							$option_type		= isset( $option['type'] )				? $option['type']									: '';
							$option_desc		= isset( $option['desc'] )				? $option['desc']									: '';
							$option_cb_label	= isset( $option['cb_label'] )			? $option['cb_label']								: '';
							$option_options		= isset( $option['options'] )			? $option['options']								: '';

							$placeholder		= isset( $option['placeholder'] )		? 'placeholder="' . $option['placeholder'] . '"'	: '';
//							$class				= isset( $option['class'] )				? 'class="' . $option['class'] . '"'				: '';
							$class				= isset( $option['class'] )				? ' ' . $option['class']							: '';
							
							$min				= isset( $option['min'] )				? 'min="' . $option['min'] . '"'					: null;
							$max				= isset( $option['max'] )				? 'max="' . $option['max'] . '"'					: null;
							$step				= isset( $option['step'] )				? 'step="' . $option['step'] . '"'					: null;
							
							$value				= wpsight_get_option( $option['id'] );
							
							if( ! isset( $value ) && isset( $option['default'] ) )
								$value = $option['default'];
							
							$attributes = array();

							if ( isset( $option['attributes'] ) && is_array( $option['attributes'] ) )
								foreach ( $option['attributes'] as $attribute_name => $attribute_value )
									$attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';

							echo '<tr valign="top" class="setting-' . $option_css . '-tr' . $class . '">';
							
								if( $option_type == 'heading' ) {
									echo '<th scope="row" colspan="2">';
									
										echo '<h3 style="border-bottom: 1px solid #DDD; margin: 0 0 1rem 0; padding: 0 0 1rem 0;">' . $option_name . '</h3>';
										
										if ( $option_desc )
											echo ' <p class="description">' . $option_desc . '</p>';
	
									echo '</th>';
								} else {
									echo '<th scope="row">';
									
										echo '<label for="setting-' . $option_css . '">' . $option_name . '</label>';
										
										if ( $option_desc )
											echo ' <p class="description">' . $option_desc . '</p>';
	
									echo '</th>';
								
									echo '<td>';
		
									echo '<div class="wpsight-settings-field-wrap wpsight-settings-field-' . $option_type . '-wrap">';
									
									switch ( $option_type ) {
		
										case "heading" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<h5><?php echo $value; ?></h5>                                        
											</div>  
																			  
											<?php
		
										break;
										case "checkbox" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<div class="switch">
													<input id="setting-<?php echo $option_css; ?>" name="<?php echo $option_id; ?>" type="<?php echo $option_type; ?>" value="1" <?php echo implode( ' ', $attributes ); ?> <?php checked( '1', $value ); ?> />
													<label for="setting-<?php echo $option_css; ?>" class="label-<?php echo $option_type; ?>"><?php //echo $option_cb_label; ?></label>
												</div>                                    
											</div>  
																			  
											<?php
		
										break;
										case "multicheck" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-checkbox">
											
												<?php
												
												foreach ( $option_options as $key => $name ) {
		//											
		//											var_dump( $value );
		//											var_dump( $key );
		//
													$v = null;
													
													$v = isset( $value[$key] ) ? $value[$key] : '';
													
												?>
												
												<!--<div class="switch">-->
													<input id="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" name="<?php echo $option_id . '[' . $key . ']' ; ?>" type="checkbox" value="1" <?php //echo implode( ' ', $attributes ); ?> <?php checked( '1', $v ); ?> />
													<label for="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" class="label-checkbox"><?php echo $name; ?></label>
												<!--</div>-->                                   
												
												<?php } ?>
											
											
											</div>  
																			  
											<?php
		
										break;
										case "textarea" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<textarea id="setting-<?php echo $option_css; ?>" cols="100" rows="8" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?>><?php echo esc_textarea( $value ); ?></textarea>
											</div>
											
											<?php
		
										break;
										case "select" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<select id="setting-<?php echo $option_css; ?>" class="regular-text" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?>><?php
													foreach( $option_options as $key => $name )
														echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>'; ?>
												</select>
											</div>
											
											<?php
		
										break;
										case "pages" :
										
											$pages = array();
											
											foreach ( get_pages() as $key => $page )			
												$pages[$page->ID] = $page->post_title;
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<select id="setting-<?php echo $option_css; ?>" class="regular-text" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?>>
													<option value=""><?php _ex( 'Select page', 'plugin settings', 'wpcasa' ); ?>&hellip;</option><?php
													foreach( $pages as $key => $name )
														echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>'; ?>
												</select>
											</div>
											
											<?php
		
										break;
										case "password" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<input id="setting-<?php echo $option_css; ?>" class="regular-text" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
											</div>
											
											<?php
		
										break;
										
										case "measurement" :
										
											$measurement = $value;
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-text">
												<input id="setting-<?php echo $option_css; ?>_label" class="regular-text" type="text" name="<?php echo $option_id . '[label]'; ?>" value="<?php echo esc_attr( $measurement['label'] ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
											</div>
		
											<div class="wpsight-settings-field wpsight-settings-field-radio">
											
												<?php
												
												foreach ( wpsight_measurements() as $key => $unit ) {
													$id = $option_css .'-'. $key;
			
												?>
												
												<input id="setting-<?php echo $id; ?>" name="<?php echo esc_attr( $option_id ); ?>[unit]" type="radio" value="<?php echo esc_attr( $key ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php checked( $measurement['unit'], $key ); ?> />
												<label for="setting-<?php echo $id; ?>" class="label-radio"><?php if( empty( $unit ) ) { echo 'None'; } else { echo $unit; } ?></label>
												
												<?php } ?>
											
											</div>
		
											<?php
											
										break;
										
										case "radio" :
										
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
											
												<?php
											
												$name = $option_name .'['. $option_id .']';
												
												foreach ( $option['options'] as $key => $option ) {
													$id = $option_css .'-'. $key;
			
													?>
												
													<input id="setting-<?php echo $id; ?>" name="<?php echo esc_attr( $option_id ); ?>" type="<?php echo $option_type; ?>" value="<?php echo esc_attr( $key ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php checked( $value, $key ); ?> />
													<label for="setting-<?php echo $id; ?>" class="label-<?php echo $option_type; ?>"><?php echo $option; ?></label>
													
												<?php } ?>
											
											</div>
											
											<?php
		
										break;
										
										case "range" :
										
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<input id="setting-<?php echo $option_css; ?>" class="range-slider__range" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> <?php echo $min; ?> <?php echo $max; ?> <?php echo $step; ?> />
												<span class="range-slider__value">0</span>
											</div>                                    
											
											<?php
		
										break;
										
										case "number" :
										
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<input id="setting-<?php echo $option_css; ?>" class="regular-text" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
											</div>
											
											<?php
		
										break;
										
										case "" :
										case "input" :
										case "text" :
		
											?>
											
											<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
												<input id="setting-<?php echo $option_css; ?>" class="regular-text" type="text" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
											</div>
											
											<?php
		
										break;
										default :
											do_action( 'wpsight_settings_field_' . $option_type, $option, $attributes, $value, $placeholder );
										break;
		
									}
		
									echo '</div>';
									
									echo '</td>';
									
								}
								
							echo '</tr>';
							
						}

						echo '</table></div>';

					}
					
					submit_button( __( 'Save Changes', 'wpcasa' ), 'primary', 'wpsight-settings-save' );

				?>

		    </form>
		    
		    <div class="wpsight-settings-reset">
		    	<form method="post" action="">
		    		<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'wpcasa' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Are you sure?', 'wpcasa' ) ); ?>' );" />
		    	</form>
		    </div>
		    
		</div>

		<script type="text/javascript">

			var totoggle_currency = '.setting-<?php echo $this->settings_name; ?>_currency_other-tr, .setting-<?php echo $this->settings_name; ?>_currency_other_ent-tr';	
	
			jQuery('#setting-<?php echo $this->settings_name; ?>_currency').change(function() {
				
			  	if( jQuery(this).val() == 'other' ) {
					jQuery(totoggle_currency).fadeIn(150);
				} else {
					jQuery(totoggle_currency).fadeOut(150);
				}
			});
			
			<?php
				/** Loop through standard details and hide them */
				$totoggle_details = array();
				foreach( wpsight_details() as $feature => $value ) {
					$totoggle_details[] = '.' . sanitize_html_class( '.setting-' . $this->settings_name . '_' . $feature . '-tr' );
				}
				$totoggle_details = implode( ', ' , $totoggle_details );		
			?>
			
			var totoggle_details = '<?php echo $totoggle_details; ?>';
			
			jQuery('#setting-<?php echo $this->settings_name; ?>_listing_features').click(function() {
  				jQuery(totoggle_details).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo $this->settings_name; ?>_listing_features:checked').val() !== undefined) {
				jQuery(totoggle_details).show();
			}
			
			<?php
				/** Loop through standard details and hide them */
				$totoggle_periods = array();
				foreach( wpsight_rental_periods() as $period_id => $value ) {
					$totoggle_periods[] = '.' . sanitize_html_class( '.setting-' . $this->settings_name . '_' . $period_id . '-tr' );
				}
				$totoggle_periods = implode( ', ' , $totoggle_periods );		
			?>
			
			var totoggle_periods = '<?php echo $totoggle_periods; ?>';
			
			jQuery('#setting-<?php echo $this->settings_name; ?>_rental_periods').click(function() {
  				jQuery(totoggle_periods).fadeToggle(150);
			});
			
			if (jQuery('#setting-<?php echo $this->settings_name; ?>_rental_periods:checked').val() !== undefined) {
				jQuery(totoggle_periods).show();
			}
			
		</script>
		<?php
			
		do_action( 'wpsight_settings_scripts', $this->settings_name );
		
	}
}
