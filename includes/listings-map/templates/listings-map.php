<?php
/**
 * Listings Map Template
 *
 * Available variables: $args and $map_query
 */
?>

<div id="map-wrap-<?php echo esc_attr( $args['map_id'] ); ?>" class="map-wrap">

	<?php if( ( true === $args['toggle'] || 'listings-panel' == $args['toggle_button'] ) && false == $args['map_page'] ) : ?>
	
		<?php if( ! empty( $args['toggle_button'] ) && 'listings-panel' != $args['toggle_button'] ) : ?>
		<a href="#" class="toggle-map" data-toggle-map="<?php echo esc_attr( $args['map_id'] ); ?>"><span><?php echo strip_tags( $args['toggle_button'] ); ?></span></a>
		<?php endif; ?>
	
		<div id="map-toggle-<?php echo esc_attr( $args['map_id'] ); ?>" class="map-toggle">
			<div id="<?php echo esc_attr( $args['map_id'] ); ?>" class="map-canvas" style="width: <?php echo $args['width']; ?>; height: <?php echo $args['height']; ?>"></div>
		</div>
	
	<?php else : ?>
	
		<div id="<?php echo esc_attr( $args['map_id'] ); ?>" class="map-canvas map-init" style="width: <?php echo $args['width']; ?>; height: <?php echo $args['height']; ?>"></div>
	
	<?php endif; ?>

</div><!-- .map-wrap -->