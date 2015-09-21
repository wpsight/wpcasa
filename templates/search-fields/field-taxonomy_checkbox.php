
<?php if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">
	
	<?php if( ! empty( $fields[$field]['label'] ) ) : ?>
	<label class="checkboxgroup" for="<?php echo esc_attr( $field ); ?>"><?php echo esc_attr( $fields[$field]['label'] ); ?></label>
	<?php endif; ?>
	
	<?php $checklist_args = wp_parse_args( $fields[$field]['data'], array( 'hide_empty' => 1 ) ); ?>
	
	<?php foreach( get_terms( $fields[$field]['data']['taxonomy'], $checklist_args ) as $k => $v ) : ?>				
		<?php
			if( is_array( $field_value ) ) {
				$field_option_key = array_search( $v->slug, $field_value );				
				$field_option_value = $field_option_key !== false ? $field_value[$field_option_key] : false;
			} else {
				$field_option_value = $field_value;
			}
		?>		
		<label class="checkbox"><input type="checkbox" name="<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $v->term_id ); ?>]" value="<?php echo esc_attr( $v->slug ); ?>"<?php checked( $v->slug, $field_option_value ); ?>><?php echo esc_attr( $v->name ); ?></label>
	<?php endforeach; ?>

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
