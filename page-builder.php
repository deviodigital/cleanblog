<?php
/**
 * Template Name: Builder
 *
 * This is the template for times you want to use a page builder.
 *
 * @package Clean Blog
 */

get_header(); ?>

            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

			<?php do_action('cleanblog_page_top'); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

			<?php endwhile; // End of the loop. ?>
			
			<?php do_action('cleanblog_page_bottom'); ?>

			</div>
			<!-- /.col-lg-8.col-lg-offset-2.col-md-10.col-md-offset-1 -->

<?php get_footer(); ?>
