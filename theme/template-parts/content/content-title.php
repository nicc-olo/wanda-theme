<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php wanda_post_thumbnail(); ?>

	<header class="entry-header">
		<?php
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '%s', esc_html_x( 'In evidenza', 'post', 'wanda' ) );
		}
		the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		?>
	</header><!-- .entry-header -->

</article><!-- #post-${ID} -->
