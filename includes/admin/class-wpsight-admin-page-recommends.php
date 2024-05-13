<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'WPSight_Recommendations' ) ) :

/**
 * WPSight_Recommendations Class
 */
class WPSight_Recommendations {

	/**
	 * Handles output of the recommendations page in admin.
	 */
	public function output() { ?>

        <div class="wrap-title-recommend">
			<h2><?php
                /* translators: %s: is the name */
                printf( esc_html__( '%s Recommendations', 'wpcasa' ), esc_html( WPSIGHT_NAME ) ); ?></h2>
        </div>

        <div class="wrap-cards-recommend">

        <?php foreach( wpsight_admin_get_recommendations() as $key => $value ) { ?>

            <div class="wrap-card">
                <div class="card">
                    <a href="<?php echo esc_url( $value['button_link'] ); ?>" class="card__wrap-img">
                        <img src="<?php echo esc_url( $value['image_url'] ); ?>" class="card__img" alt="">
                    </a>

                    <div class="card__content">
                        <h2 class="card__wrap-link">
                            <a href="<?php echo esc_url( $value['button_link'] ); ?>" class="card__link"><?php echo esc_html( $value['title'] ); ?></a>
                        </h2>

                        <p class="card__description"><?php echo esc_html( $value['description'] ); ?></p>

                        <a href="<?php echo esc_url( $value['button_link'] ); ?>" class="button card__label"><?php echo esc_html( $value['button_text'] ); ?></a>
                    </div>
                </div>
            </div>

        <?php } ?>

        </div>

    <?php }

}

endif;

return new WPSight_Recommendations();
