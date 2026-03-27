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
$bando     = get_field( 'bando', 'options' ); 

?>
	<?php if ( ! wanda_is_past_enrollment_date() ): ?>
	<section class="bg-primary-900 text-white py-12">
		<div class="mx-auto max-w-content p-2">
			<?php if ( has_custom_logo() ) {
				$logo = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $logo , 'full' );
				echo '<img src='. $image[0] .' alt="" role="presentation" class="custom-logo max-w-48 mx-auto block">';
			} ?>
			<h2 class="text-white text-center mb-4 text-4xl"> <?= do_shortcode( __( 'Sono in corso le selezioni per l&apos; Edizione [edizione] del Concorso Nazionale Wanda Capodaglio,','wanda' ) ); ?></h2>
			<h3 class="text-primary-100 small-caps text-center mb-4 text-xl"> <?= __('Le iscrizioni si chiuderanno tra', 'wanda'); ?> </h3>
			<?= do_shortcode('[countdown_scadenza]'); ?>
			<div class="flex flew-row gap-4 mt-8 justify-center items-center">
				<a 
				class="primary-button bg-secondary-900 border-primary-100 text-white"
				href="<?php esc_html_e($bando); ?>">
					<?= __('Leggi di più','wanda'); ?>
				</a>
				<?php if( ! empty( $bando ) ): ?>
				<a 
				class="primary-button bg-primary-900 border-primary-900 text-white"
				href="<?php esc_html_e($bando); ?>"
				target="_blank"
				rel="noopener nofollow noreferrer">
					<?= __('Scarica il bando','wanda') . ' ' . date("Y"); ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section id="primary" class="mt-8">
		<main id="main" class="max-w-wide mx-auto">

			<div class="flex md:flex-flow my-6 gap-8 justify-between">
				<article class="prose max-w-content p-2">
					<?= wp_kses_post( $homepage['descrizione_del_sito'] ); ?>
				</article>
				<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<aside role="complementary" aria-label="<?php esc_attr_e( 'Sidebar del Footer', 'wanda' ); ?>">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside>
				<?php endif; ?>
			</div>

			<?php
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) : ?>
				
				<h2 class="text-xl text-center"> <?= __('Gli ultimi articoli', 'wanda'); ?> </h2>
				<div id="#latest-posts" class="grid grid-flow-col md:grid-cols-3 gap-4">

				<?php
				while ( $query->have_posts() ) : 
					$query->the_post();
					
					get_template_part( 'template-parts/content/content-excerpt', 'page' );
					
					// If comments are open, or we have at least one comment, load
					// the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
			
				endwhile; // End of the loop.
				wp_reset_postdata(); // Restores the global $post object
				?>

			</div> <!-- #latest-posts -->

			<?php endif; ?>

		</main><!-- #main -->

		<?php
		if ( $homepage ): 
		?>
		<section id="final-cta" class="bg-primary text-white flex md:flex-row mx-auto max-w-content my-12 p-12 gap-4">
			<?php if ( ! empty( $homepage['cta_finale_img'] ) ) : ?>
				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" role="presentation" loading="lazy" />
			<?php endif; ?>
			<div>
				<?php if ( ! empty($homepage['cta_finale_titolo'])): ?>
					<h2 class="text-primary-100 text-4xl italic font-regular small-caps">
						<?= sanitize_text_field( $homepage['cta_finale_titolo'] ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty($homepage['cta_finale_body']) ): ?>
				<p class="italic text-2xl">
					<?= sanitize_text_field( $homepage['cta_finale_body'] ); ?>
				</p>
				<?php endif; ?>
				<?php if ( ! empty( $homepage['cta_finale_link'] ) ): ?>
					<a href="<?= wp_parse_url( $homepage['cta_finale_link'] ); ?>" class=" mt-4 primary-button bg-white text-primary">
						<?php 
						if( ! empty( $homepage['cta_finale_button_text'] ) ){
							echo esc_html( $homepage['cta_finale_button_text'] );
						} else {
							echo __('Scopri di più', 'wanda'); 
						}
						?>
					</a>
				<?php endif; ?>
			</div>
		</section><!-- #final-cta -->

		<?php endif; ?>

	</section><!-- #primary -->

<?php
get_footer();
