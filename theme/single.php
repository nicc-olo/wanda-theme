<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wanda
 */


get_header();
?>



<?php
if ( function_exists( 'rank_math_the_breadcrumbs' ) && ! is_front_page() ): ?>
<div class="mx-auto max-w-wide px-2 py-4">
	<?php rank_math_the_breadcrumbs(); ?>
</div>
<?php endif; ?>

	<section id="primary">
		<main id="main" class="p-2 lg:p-0">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'single' );

				if ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span aria-hidden="true">' . __( 'Prossimo articolo', 'wanda' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Prossimo articolo:', 'wanda' ) . '</span> <br/>' .
								'<span>%title</span>',
							'prev_text' => '<span aria-hidden="true">' . __( 'Articolo precedente', 'wanda' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Articolo precedente:', 'wanda' ) . '</span> <br/>' .
								'<span>%title</span>',
						)
					);
				}

				// If comments are open, or we have at least one comment, load
				// the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

				// End the loop.
			endwhile;
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
