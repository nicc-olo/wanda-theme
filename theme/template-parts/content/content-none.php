<?php
/**
 * Template part for displaying a message when posts are not found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

?>

<section>

	<header class="page-header">
		<?php if ( is_search() ) : ?>

			<h1 class="page-title">
				<?php
				printf(
					/* translators: 1: search result title. 2: search term. */
					'<h1 class="page-title">%1$s <span>%2$s</span></h1>',
					esc_html__( 'Risultati della ricerca per:', 'wanda' ),
					get_search_query()
				);
				?>
			</h1>

		<?php else : ?>

			<h1 class="page-title"><?php esc_html_e( 'Nessun risultato', 'wanda' ); ?></h1>

		<?php endif; ?>
	</header><!-- .page-header -->

	<div <?php wanda_content_class( 'page-content' ); ?>>
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
			?>

			<p>
				<?php esc_html_e( 'Il tuo sito è impostato per mostrare gli articoli più recenti nella pagina iniziale, ma non hai ancora pubblicato nulla.', 'wanda' ); ?>
			</p>

			<p>
				<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
					<?php
					/* translators: 1: link to WP admin new post page. */
					esc_html_e( 'Aggiungi o pubblica articoli', 'wanda' );
					?>
				</a>
			</p>

			<?php
		elseif ( is_search() ) :
			?>

			<p>
				<?php esc_html_e( 'La tua ricerca non ha prodotto risultati. Prova a effettuare una ricerca diversa.', 'wanda' ); ?>
			</p>

			<?php
			get_search_form();
		else :
			?>

			<p>
				<?php esc_html_e( 'Nessun contenuto corrisponde alla tua richiesta.', 'wanda' ); ?>
			</p>

			<?php
			get_search_form();
		endif;
		?>
	</div><!-- .page-content -->

</section>
