<?php
/**
 * Template part for displaying posts.
 *
 * @package Clean Blog
 */

?>

<div <?php post_class( 'post-preview' ); ?> id="post-<?php the_ID(); ?>">
		<?php the_title( sprintf( '<h2 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
			<p class="post-meta"><?php cleanblog_posted_on(); ?></p>
		<?php endif; ?>
		<?php if ( 'show' === get_theme_mod( 'cleanblog_post_excerpt' ) || '' === get_theme_mod( 'cleanblog_post_excerpt' ) ) { ?>
			<?php the_excerpt(); ?>
		<?php } ?>
</div>
<!-- /.post-preview -->

<hr>