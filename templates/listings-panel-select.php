<?php
	$_GET['orderby'] = isset( $_GET['orderby'] ) ? $_GET['orderby'] : false;
	$_GET['order']   = isset( $_GET['order'] ) ? $_GET['order'] : false;
?>

<div class="listings-sort">

	<select name="listings-sort">

		<option value=""><?php echo $args['labels']['orderby']; ?></option>

		<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'date' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'date', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['date']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'price' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'price', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['price']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'asc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'asc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['asc']; ?>)</option>

		<option<?php if ( $_GET['orderby'] == 'title' && $_GET['order'] == 'desc' ) echo ' selected="selected"'; ?> value="<?php echo add_query_arg( array( 'orderby' => 'title', 'order' => 'desc' ) ); ?>"><?php echo $args['labels']['title']; ?> (<?php echo $args['labels']['desc']; ?>)</option>

	</select>

</div><!-- .listings-sort -->