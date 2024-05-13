<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php $count = count( wpsight_admin_get_recommendations() ); ?>

<div class="wpsight-admin-ui-promo" <?php if ($count > 1) echo 'swiper'; ?>>
	
    <div class="wpsight-promo-slider" <?php if ($count > 1) echo 'swiper-container'; ?>>
		
        <div class="swiper-wrapper">
            <?php 
			
				$class = '';
			
				foreach ( wpsight_admin_get_recommendations() as $key => $value ) {

					if ( ( esc_html( $value['title'] ) != "" ) || ( esc_html( $value['description'] ) != "" )  )
						$class = 'swiper-slide swiper-slide-overlay'; ?>

					<a target="_blank" href="<?php echo esc_url( $value['button_link'] ); ?>" class="swiper-slide <?php echo esc_html( $class ); ?>">
						<img class="promo-slider-img" src="<?php echo esc_url( $value['image_url'] ); ?>" alt="<?php echo esc_html( $value['title'] ); ?>" title="<?php echo esc_html( $value['description'] ); ?>">
						
					</a>
			
				<?php } ?>

        </div>

		<?php if( $count > 1 ) { ?>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>

			<div class="swiper-pagination"></div>
		<?php } ?>
		
    </div>

</div>
