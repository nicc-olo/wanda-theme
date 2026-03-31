<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

get_header();
?>

	<section id="primary" class="px-2 py-8 md:py-12">
		<main id="main" class="mx-auto max-w-wide">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8 border-b border-foreground/15 pb-6 md:mb-10">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php the_archive_description( '<div class="prose mx-auto max-w-content text-primary-900/85">', '</div>' ); ?>
			</header><!-- .page-header -->

			<div class="posts-grid">
				<?php
				// Start the Loop.
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', 'title' );

					// End the loop.
				endwhile;
				?>
			</div>

			<div class="mt-8 border-t border-foreground/15 pt-6">
				<?php
				// Previous/next page navigation.
				wanda_the_posts_navigation();
				?>
			</div>

		else :
			?>
			<div class="mx-auto max-w-content rounded-sm bg-neutral-100 p-6">
				<?php
				// If no content, include the "No posts found" template.
				get_template_part( 'template-parts/content/content', 'none' );
				?>
			</div>
			<?php
		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
