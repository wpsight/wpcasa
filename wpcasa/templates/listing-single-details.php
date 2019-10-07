<?php
/**
 * Template: Single Listing Details
 */
global $listing;

$id =  isset($id) ? $id : $listing->ID;
$formatted =  isset($formatted) ? $formatted : 'wpsight-listing-details';
$details =  isset($details) ? $details : false;

?>

<div class="wpsight-listing-section wpsight-listing-section-details">

    <?php do_action( 'wpsight_listing_single_details_before', $id ); ?>

    <?php wpsight_listing_details( $id, $details, $formatted ); ?>

    <?php do_action( 'wpsight_listing_single_details_after', $id ); ?>

</div><!-- .wpsight-listing-section-details -->