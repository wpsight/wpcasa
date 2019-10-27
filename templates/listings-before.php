<?php
/**
 * Template: Before Listings Archive
 */
global $wpsight_query; ?>

<?php do_action( 'wpsight_listings_before', $wpsight_query ); ?>

<?php if( isset( $show_panel ) && $show_panel ) wpsight_panel( $wpsight_query ); ?>

<div class="wpsight-listings">
