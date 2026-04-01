<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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

				get_template_part( 'template-parts/content/content', 'page' );

				// If comments are open, or we have at least one comment, load
				// the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
