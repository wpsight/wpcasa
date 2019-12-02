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
        echo 'recommends';
    }


}

endif;

return new WPSight_Recommends();
