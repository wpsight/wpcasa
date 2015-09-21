
<?php if( isset( $fields[$field]['data'] ) && is_array( $fields[$field]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">

	<select class="listing-search-<?php echo sanitize_html_class( $field ); ?> select" name="<?php echo wpsight_get_query_var_by_detail( $field ); ?>">		
		<option value=""><?php echo $fields[$field]['label']; ?></option>								
		<?php foreach( $fields[$field]['data'] as $k => $v ) : ?>									
			<?php $data_default = ( isset( $fields[$field]['default'] ) && $fields[$field]['default'] == $k ) ? 'true' : 'false'; ?>								
			<?php if( ! empty( $k ) ) : ?><option value="<?php echo esc_attr( $k ); ?>"<?php selected( $k, sanitize_key( $field_value ) ); ?> data-default="<?php echo esc_attr( $data_default ); ?>"><?php echo $v; ?></option>
			<?php endif; ?>							
		<?php endforeach; ?>							
	</select><!-- .listing-search-<?php echo sanitize_html_class( $field ); ?> -->

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
