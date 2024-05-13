<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
	$_GET['orderby'] = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : false;
	$_GET['order']   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : false;
?>

<div class="listings-sort">

	<select name="listings-sort">

		<option value=""><?php echo esc_html( $args['labels']['orderby'] ); ?></option>

		<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'date', 'order' => 'asc' ) ) ); ?>"><?php echo esc_html( $args['labels']['date'] ); ?> (<?php echo esc_html( $args['labels']['asc'] ); ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'date', 'order' => 'desc' ) ) ); ?>"><?php echo esc_html( $args['labels']['date'] ); ?> (<?php echo esc_html( $args['labels']['desc'] ); ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'price', 'order' => 'asc' ) ) ); ?>"><?php echo esc_html( $args['labels']['price'] ); ?> (<?php echo esc_html( $args['labels']['asc'] ); ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'price', 'order' => 'desc' ) ) ); ?>"><?php echo esc_html( $args['labels']['price'] ); ?> (<?php echo esc_html( $args['labels']['desc'] ); ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'title', 'order' => 'asc' ) ) ); ?>"><?php echo esc_html( $args['labels']['title'] ); ?> (<?php echo esc_html( $args['labels']['asc'] ); ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo esc_url( add_query_arg( array( 'orderby' => 'title', 'order' => 'desc' ) ) ); ?>"><?php echo esc_html( $args['labels']['title'] ); ?> (<?php echo esc_html( $args['labels']['desc'] ); ?>)</option>

	</select>

</div><!-- .listings-sort -->