<section id="listing-<?php the_ID(); ?>" <?php wpsight_listing_class(); ?>>

	<header class="entry-header">

        <?php if( get_the_post_thumbnail() ) { ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail(); ?>
            </a>
        <?php } // endif get_the_post_thumbnail() ?>

		<h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>

	</header><!-- .entry-header -->

	<?php do_action( 'wpsight_listing_archive_content_before' ); ?>
		
	<?php wpsight_get_template_part( 'listing', 'archive' ); ?>
		
	<?php do_action( 'wpsight_listing_archive_content_after' ); ?>

</section><!-- #listing-<?php the_ID(); ?> -->