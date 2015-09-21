
<?php if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">
	
	<?php if( ! empty( $fields[$field]['label'] ) ) : ?>
	<label class="checkboxgroup" for="<?php echo esc_attr( $field ); ?>"><?php echo esc_attr( $fields[$field]['label'] ); ?></label>
	<?php endif; ?>
	
	<?php foreach( $fields[$field]['data'] as $k => $v ) : ?>	
		<?php
			if( is_array( $field_value ) ) {
				$field_option_key = array_search( $k, $field_value );
				$field_option_value = $field_option_key !== false ? $field_value[$field_option_key] : false;
			} else {
				$field_option_value = $field_value;
			}
		?>			
		<label class="checkbox"><input type="checkbox" name="<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $k ); ?>"<?php checked( $k, $field_option_value ); ?>><?php echo esc_attr( $v ); ?></label>		
	<?php endforeach; ?>

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
