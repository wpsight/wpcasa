<div class="listings-sort">

	<?php if ( $args['orderby'] ) { ?>

	<div class="listings-sort-orderby">

		<span class="listings-sort-orderby-label"><?php echo $args['labels']['orderby']; ?></span>

		<?php if ( get_query_var( 'order' ) ) $vars_order = array( 'order' => get_query_var( 'order' ) ); ?>

		<span class="listings-sort-orderby-date">
			<a href="<?php echo add_query_arg( array_merge( array( 'orderby' => 'date' ), (array) $vars_order ) ); ?>"><?php echo $args['labels']['date']; ?></a>
		</span>

		<span class="listings-sort-orderby-separator"><?php echo $args['labels']['orderby_sep']; ?></span>

		<span class="listings-sort-orderby-price">
			<a href="<?php echo add_query_arg( array_merge( array( 'orderby' => 'price' ), (array) $vars_order ) ); ?>"><?php echo $args['labels']['price']; ?></a>
		</span>

	</div><!-- .listings-sort-orderby -->

	<?php } ?>

	<?php if ( $args['order'] ) { ?>

	<div class="listings-sort-order">

		<span class="listings-sort-order-label"><?php echo $args['labels']['order']; ?></span>

		<?php $vars_orderby = get_query_var( 'orderby' ) == 'price' ? array( 'orderby' => 'price' ) : false; ?>

		<span class="listings-sort-order-desc">
			<a href="<?php echo add_query_arg( array_merge( (array) $vars_orderby, array( 'order' => 'DESC' ) ) ); ?>"><?php echo $args['labels']['desc']; ?></a>
		</span>

		<span class="listings-sort-order-separator"><?php echo $args['labels']['order_sep']; ?></span>

		<span class="listings-sort-order-asc">
			<a href="<?php echo add_query_arg( array_merge( (array) $vars_orderby, array( 'order' => 'ASC' ) ) ); ?>"><?php echo $args['labels']['asc']; ?></a>
		</span>

	</div><!-- .listings-sort-order -->

	<?php } ?>

</div><!-- .listings-sort -->