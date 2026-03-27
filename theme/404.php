<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package wanda
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<div>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Pagina non trovata', 'wanda' ); ?></h1>
				</header><!-- .page-header -->

				<div <?php wanda_content_class( 'page-content' ); ?>>
					<p><?php esc_html_e( 'Impossibile trovare questa pagina. Potrebbe essere stata rimossa, rinominata o non essere mai esistita.', 'wanda' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
