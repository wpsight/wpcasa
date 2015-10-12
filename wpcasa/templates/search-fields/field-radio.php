
<?php if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">
	
	<?php if( ! empty( $fields[$field]['label'] ) ) : ?>
	<label class="radiogroup" for="<?php echo esc_attr( $field ); ?>"><?php echo esc_attr( $fields[$field]['label'] ); ?></label>
	<?php endif; ?>
	
	<?php foreach( $fields[$field]['data'] as $k => $v ) : ?>	
		<?php $data_default = ( isset( $fields[$field]['default'] ) && $fields[$field]['default'] == $k ) ? 'true' : 'false'; ?>				    
		<label class="radio">
			<input type="radio" name="<?php echo esc_attr( wpsight_get_query_var_by_detail( $field ) ); ?>" value="<?php echo esc_attr( $k ); ?>"<?php checked( $k, sanitize_key( $field_value ) ); ?> data-default="<?php echo esc_attr( $data_default ); ?>"/> <?php echo esc_attr( $v ); ?>
		</label>		
	<?php endforeach; ?>

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
