<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<form method="get"<?php echo esc_attr( $args['id'] ); ?> action="<?php echo esc_url( $args['action'] ); ?>" class="<?php echo esc_attr( sanitize_html_class( $args['class'] ) ); ?> <?php echo esc_attr( sanitize_html_class( $args['orientation'] ) ); ?>">
	
	<div class="listings-search-default">	
		<?php echo wp_kses( $search_default , wpsight_allowed_html_tags() ) ; ?>
	</div><!-- .listings-search-default -->
	
	<?php if( ! empty( $search_advanced ) && $args['advanced'] !== false ) : ?>
	
		<div class="listings-search-advanced">		
			<?php echo wp_kses( $search_advanced , wpsight_allowed_html_tags() ) ; ?>
		</div><!-- .listings-search-advanced -->
			
		<?php // Display advanced search toggle button ?>
		<?php echo wp_kses_post( $args['advanced'] ); ?>
			
	<?php endif; ?>
	
	<?php if( $args['reset'] !== false ) : ?>	
		<?php // Display reset search button ?>
		<?php echo wp_kses_post( $args['reset'] ); ?>			
	<?php endif; ?>
	
	<?php if( isset( $_GET['page_id'] ) ) : ?>
		<?php // Add current page_id to GET parameters if permalinks are not pretty ?>
		<input name="page_id" type="hidden" value="<?php echo absint( $_GET['page_id'] ); ?>" />
	<?php endif; ?>
		
</form><!-- .<?php echo esc_html( sanitize_html_class( $args['class'] ) ); ?> -->