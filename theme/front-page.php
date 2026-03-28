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

$last_edition = new WP_Query(
    array(
        'post_status'         => 'publish',
        'post_type'           => 'edizione',
        'posts_per_page'      => 1,
        'meta_key'            => 'edizione_data_serata', // Y-m-d H:i:s (required)
        'orderby'             => 'meta_value',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    )
);

$last_edition_URL = '#';
$is_past_event_date = false;

if ( $last_edition->have_posts() ) {
    $last_edition->the_post();
    
    $last_edition_URL = get_permalink();
    
    $date_raw = get_field('edizione_data_serata', get_the_ID());
    
    if ($date_raw) {
        $event_date = DateTime::createFromFormat('Y-m-d H:i:s', $date_raw);
        if ($event_date) {
            $is_past_event_date = new DateTime() > $event_date;
        }
    }

    wp_reset_postdata();
}

?>
	<?php if ( ! wanda_is_past_enrollment_date() ): ?>
	<section class="bg-primary-900 text-white py-12">
		<div class="mx-auto max-w-content p-2">
			<?php if ( has_custom_logo() ) {
				$logo = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $logo , 'full' );
				echo '<img src='. $image[0] .' alt="" role="presentation" class="custom-logo max-w-48 mx-auto mb-8 block">';
			} ?>
			<h2 class="text-white text-center mb-4 text-4xl"> <?= do_shortcode( __( 'Sono in corso le selezioni per l&apos;[edizione] del Concorso Nazionale Wanda Capodaglio','wanda' ) ); ?></h2>
			<h3 class="text-primary-100 small-caps text-center mb-4 text-xl"> <?= __( 'Le iscrizioni si chiuderanno tra', 'wanda' ); ?> </h3>
			<?= do_shortcode('[countdown_scadenza]'); ?>
			<div class="flex flex-col md:flex-row gap-4 mt-8 justify-center items-center">
				<a 
				class="primary-button bg-secondary-900 border-primary-100 text-white"
				href="<?= $last_edition_URL; ?>">
					<?= __( 'Guarda l&apos;', 'wanda' ) . do_shortcode( '[edizione]' ); ?>
				</a>
				<?php if( ! empty( $bando ) && ! $is_past_event_date ): ?>
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
	<?php else : ?>
	<section class="bg-neutral-900 text-white">
		<?php if ( $homepage['foto_slider_principale'] ): ?>
			<div class="swiper w-full h-full p-4">
				<div class="swiper-wrapper">
					<?php foreach( $homepage['foto_slider_principale'] as $image ): ?>
						<div class="swiper-slide flex items-center justify-center">
							<img class="block max-w-full h-full object-contain" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
						</div>
					<?php endforeach; ?>
				</div>
				
				<div class="swiper-pagination"></div>
				<div class="autoplay-progress">
					<svg viewBox="0 0 48 48">
						<circle cx="24" cy="24" r="20"></circle>
					</svg>
					<span></span>
				</div>

				<button class="swiper-button-prev opacity-50 hover:opacity-100">
					<span class="sr-only"><?= __('Immagine precedente', 'wanda');?></span>
					<svg class="fill-none! ml-4" tabindex="-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-left-icon lucide-circle-arrow-left"><circle cx="12" cy="12" r="10"/><path d="m12 8-4 4 4 4"/><path d="M16 12H8"/></svg>
				</button>
				<button class="swiper-button-next opacity-50 hover:opacity-100">
					<span class="sr-only"><?= __('Prossima immagine', 'wanda');?></span>
					<svg class="fill-none! mr-4" tabindex="-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-right-icon lucide-circle-arrow-right"><circle cx="12" cy="12" r="10"/><path d="m12 16 4-4-4-4"/><path d="M8 12h8"/></svg>
				</button>
			</div>
		<?php else : ?>
		<div class="mx-auto max-w-content">
			<h2 class="my-12 text-center text-6xl text-white">
				<em><?= bloginfo('description');?></em><br>
				<?= bloginfo('name');?>
			</h2>
			<a 
			class="primary-button bg-secondary-900 border-primary-100 text-white"
			href="<?= $last_edition_URL; ?>">
				Rivedi l'ultima edizione
			</a>
		</div>
		<?php endif; ?>
	</section>
	<?php endif; ?>

	<section id="primary" class="mt-8">
		<main id="main" class="max-w-wide mx-auto">

			<div class="flex flex-col md:flex-row my-6 gap-8 justify-between">
				<article class="prose max-w-content p-2">
					<?= wp_kses_post( $homepage['descrizione_del_sito'] ); ?>
				</article>
				<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<aside class="p-2" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar del Footer', 'wanda' ); ?>">
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
				<hr class="border-0 border-b-4 border-dotted border-secondary/50 w-32 mx-auto mt-12 mb-6" />
				<h2 class="text-3xl italic text-center"> <?= __('Gli ultimi articoli', 'wanda'); ?> </h2>
				<div id="#latest-posts" class="posts-grid">

				<?php
				while ( $query->have_posts() ) : 
					$query->the_post();
					
					get_template_part( 'template-parts/content/content-title', 'page' );
			
				endwhile; // End of the loop.
				wp_reset_postdata(); // Restores the global $post object
				?>

			</div> <!-- #latest-posts -->

			<?php endif; ?>

		</main><!-- #main -->

		<?php
		if ( $homepage ): 
		?>
		<section id="final-cta" class="bg-primary-900 text-white flex flex-col md:flex-row justify-between mx-auto max-w-content my-12 p-6 gap-4">
			<?php if ( ! empty( $homepage['cta_finale_img'] ) ) : ?>
				<img class="max-w-4/12" src="<?php echo esc_url($homepage['cta_finale_img']['url']); ?>" alt="<?php echo esc_attr($homepage['cta_finale_img']['alt']); ?>" role="presentation" loading="lazy" />
			<?php endif; ?>
			<div class="p-6">
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
