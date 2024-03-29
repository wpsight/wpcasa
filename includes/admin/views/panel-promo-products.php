<div class="wpsight-admin-ui-promo">
	
    <div class="swiper-container wpsight-promo-slider">
		
        <div class="swiper-wrapper">
            <?php foreach ( wpsight_admin_get_recommendations() as $key => $value ) {
	
                $class = '';

                if ( ( esc_html( $value['title'] ) != "" ) || ( esc_html( $value['description'] ) != "" )  )
                    $class = 'swiper-slide swiper-slide-overlay'; ?>
			
                <a target="_blank" href="<?php echo esc_url( $value['button_link'] ); ?>" class="swiper-slide <?php echo $class; ?>">
                    <img class="promo-slider-img" src="<?php echo esc_url( $value['image_url'] ); ?>" alt="">
					<div class="slide-content">
						<span class="slide-content-title">
							<?php echo esc_html( $value['title'] ); ?>
						</span>
						<p class="slide-content-desc">
							<?php echo esc_html( $value['description'] ); ?>
						</p>
					</div>
                </a>
            <?php } ?>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
		
    </div>

</div>
