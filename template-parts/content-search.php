<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clean Blog
 */

?>


<div <?php post_class( 'post-preview' ); ?> id="post-<?php the_ID(); ?>">
		<?php the_title( sprintf( '<h2 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
			<p class="post-meta"><?php cleanblog_posted_on(); ?></p>
		<?php endif; ?>
		<?php the_excerpt(); ?>
</div>
<!-- /.post-preview -->

<hr>