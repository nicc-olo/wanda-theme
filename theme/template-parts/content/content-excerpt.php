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

	<header class="entry-header">
		<?php
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '%s', esc_html_x( 'In evidenza', 'post', 'wanda' ) );
		}
		the_title( '<h3 class="entry-title">', '</h3>' );
		?>
	</header><!-- .entry-header -->

	<?php wanda_post_thumbnail(); ?>

	<div class="entry-content">
		<p><?php echo esc_html( wp_trim_words( get_post()->post_excerpt ?: get_post()->post_content, 15, '…' ) ); ?></p>
	</div><!-- .entry-content -->

	<a href="<?php echo esc_url( get_permalink() ); ?>" class="primary-button mt-4">
		<?php esc_html_e( 'Leggi l\'articolo', 'wanda' ); ?>
	</a>

</article><!-- #post-<?php the_ID(); ?> -->
