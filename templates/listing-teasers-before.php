<?php
/**
 * Template: Before Listing Teasers
 */
global $wpsight_query; ?>

<?php do_action( 'wpsight_listing_teasers_before', $wpsight_query ); ?>

<?php if( $show_panel ) wpsight_panel( $wpsight_query ); ?>

<div class="wpsight-listing-teasers">
