
<?php if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">

	<?php
		$dropdown_defaults = array(
			'echo'				=> 1,
			'name'				=> $field,
			'class'           	=> 'listing-search-' . $field . ' select',
			'selected'			=> $field_value,
			'value_field'       => 'slug',
			'hide_if_empty'   	=> false,
			'cache'				=> true					
		);
		
		// Merge with form field $fields[$field]['data']
		$dropdown_args = wp_parse_args( $fields[$field]['data'], $dropdown_defaults );
	?>
	
	<?php wp_dropdown_categories( $dropdown_args ); ?>

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
