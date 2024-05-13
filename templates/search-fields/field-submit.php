<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[$field]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">
	<input type="submit" value="<?php echo esc_attr( $fields[$field]['label'] ); ?>">
</div><!-- .listings-search-field-<?php echo esc_html( sanitize_html_class( $field ) ); ?> -->
