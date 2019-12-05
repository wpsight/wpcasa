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
        $recommends = [
            [
                'title' =>  __( "Recommend title", "wpcasa" ),
                'description' => __( "Description", "wpcasa" ),
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Image-Porkeri_001.jpg',
                'button_text' => __( "Button text", "wpcasa" ),
                'button_link' => 'button link',
            ],
            [
                'title' =>  __( "Recommend title2", "wpcasa" ),
                'description' => __( "Description2", "wpcasa" ),
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Image-Porkeri_001.jpg',
                'button_text' => __( "Button text2", "wpcasa" ),
                'button_link' => 'button link',
            ],
        ];

        foreach($recommends as $key => $value) {
            ?>

            <div class="">
                <h2><?php echo $value['image_url']; ?></h2>
            </div>

       <?php }

    }


}

endif;

return new WPSight_Recommends();
