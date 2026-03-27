<?php
/**
 * 
 * Fixed homepage layout
 *
 * @package wanda
 */

get_header();


// ACF — Options page "Informazioni sito"
$homepage  = get_field( 'homepage', 'options' ); // group: descrizione_del_sito, foto_slider_principale, cta_finale_*

?>

	<section id="primary" class="mt-8>
		<main id="main">

			<?php
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
