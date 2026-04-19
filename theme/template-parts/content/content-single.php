<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('max-w-content mx-auto my-12'); ?>>

	<header class="entry-header mt-8">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php /*if ( ! is_page() ) : ?>
			<div class="entry-meta">
				<?php wanda_entry_meta(); ?>
			</div><!-- .entry-meta -->
		<?php endif; */ ?>
	</header><!-- .entry-header -->

	<?php wanda_post_thumbnail(); ?>

	<div <?php wanda_content_class( 'entry-content my-4' ); ?>>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Continua a leggere<span class="sr-only"> "%s"</span>', 'wanda' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div>' . __( 'Pagine:', 'wanda' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php wanda_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
