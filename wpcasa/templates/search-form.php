<form method="get"<?php echo $args['id']; ?> action="<?php echo esc_url( $args['action'] ); ?>" class="<?php echo sanitize_html_class( $args['class'] ); ?> <?php echo sanitize_html_class( $args['orientation'] ); ?>">
	
	<div class="listings-search-default">	
		<?php echo $search_default; ?>	
	</div><!-- .listings-search-default -->
	
	<?php if( ! empty( $search_advanced ) && $args['advanced'] !== false ) : ?>
	
		<div class="listings-search-advanced">		
			<?php echo $search_advanced; ?>			
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
		
</form><!-- .<?php echo sanitize_html_class( $args['class'] ); ?> -->