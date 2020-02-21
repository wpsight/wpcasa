<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'WPSight_Addons' ) ) :

/**
 * WPSight_Recommends Class
 */
class WPSight_Recommends {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output()
    {
        $recommends = wpsight_get_recommends();
        ?>

        <div class="wrap-title-recommend">
            <h2><?php __( "WPCasa Recommendations", "wpcasa" ) ?></h2>
        </div>

        <div class="wrap-cards-recommend">

        <?php foreach($recommends as $key => $value) { ?>

            <div class="wrap-card">
                <div class="card">
                    <a href="<?php echo $value['button_link']; ?>" class="card__wrap-img">
                        <img src="<?php echo $value['image_url']; ?>" class="card__img" alt="">
                    </a>

                    <div class="card__content">
                        <h2 class="card__wrap-link">
                            <a href="<?php echo $value['button_link']; ?>" class="card__link"><?php echo $value['title']; ?></a>
                        </h2>

                        <p class="card__description"><?php echo $value['description']; ?></p>

                        <a href="<?php echo $value['button_link']; ?>" class="button card__label"><?php echo $value['button_text']; ?></a>
                    </div>
                </div>
            </div>

        <?php } ?>

        </div>

    <?php
    }

}

endif;

return new WPSight_Recommends();
