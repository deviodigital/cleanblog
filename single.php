<?php
/**
 * The template for displaying all single posts.
 *
 * @package Clean Blog
 */

get_header(); ?>

		<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

			<?php do_action('cleanblog_single_top'); ?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>
			
			<div class="postfooter">
				<?php cleanblog_entry_footer(); ?>
			</div>
			<!-- /.postfooter -->

		<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>

		<?php endwhile; // End of the loop. ?>
		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

			<?php do_action('cleanblog_single_bottom'); ?>

		</div>
		<!-- /.col-lg-8.col-lg-offset-2.col-md-10.col-md-offset-1 -->

<?php get_footer(); ?>
