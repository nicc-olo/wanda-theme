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
    
	$loghi_patrocini = get_field('edizione_lista_patrocini', get_the_ID());
	$bando = get_field('edizione_regolamento_file', get_the_ID());

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
	<section class="bg-primary-900 py-12 text-white">
		<div class="mx-auto max-w-content p-2">
			<?php if ( has_custom_logo() ) {
				$logo = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $logo , 'full' );
				echo '<img src='. $image[0] .' alt="" role="presentation" class="custom-logo max-w-48 mx-auto mb-8 block">';
			} ?>
			<h2 class="mb-4 text-center text-4xl text-white"> <?= do_shortcode( __( 'Sono in corso le selezioni per l&apos;[edizione] del Concorso Nazionale Wanda Capodaglio','wanda' ) ); ?></h2>
			<h3 class="small-caps mb-4 text-center text-xl text-primary-100"> <?= __( 'Le iscrizioni si chiuderanno tra', 'wanda' ); ?> </h3>
			<?= do_shortcode('[countdown_scadenza]'); ?>
			<div class="mt-8 flex flex-col items-center justify-center gap-4 md:flex-row">
				<a 
				class="primary-button border-primary-100 bg-secondary-900 text-white"
				href="<?= $last_edition_URL; ?>">
					<?= __( 'Vai alll&apos;', 'wanda' ) . do_shortcode( '[edizione]' ); ?>
				</a>
				<?php if( ! empty( $bando ) && ! $is_past_event_date ): ?>
				<a 
				class="primary-button border-primary-900 bg-primary-900 text-white"
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
			<div class="swiper h-full w-full p-4">
				<div class="swiper-wrapper">
					<?php foreach( $homepage['foto_slider_principale'] as $image ): ?>
						<div class="swiper-slide flex items-center justify-center">
							<img class="block h-full max-w-full object-contain" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
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
					<svg class="ml-4 fill-none!" tabindex="-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-left-icon lucide-circle-arrow-left"><circle cx="12" cy="12" r="10"/><path d="m12 8-4 4 4 4"/><path d="M16 12H8"/></svg>
				</button>
				<button class="swiper-button-next opacity-50 hover:opacity-100">
					<span class="sr-only"><?= __('Prossima immagine', 'wanda');?></span>
					<svg class="mr-4 fill-none!" tabindex="-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-right-icon lucide-circle-arrow-right"><circle cx="12" cy="12" r="10"/><path d="m12 16 4-4-4-4"/><path d="M8 12h8"/></svg>
				</button>
			</div>
		<?php else : ?>
		<div class="mx-auto max-w-content">
			<h2 class="my-12 text-center text-6xl text-white">
				<em><?= bloginfo('description');?></em><br>
				<?= bloginfo('name');?>
			</h2>
			<a 
			class="primary-button border-primary-100 bg-secondary-900 text-white"
			href="<?= $last_edition_URL; ?>">
				Rivedi l'ultima edizione
			</a>
		</div>
		<?php endif; ?>
	</section>
	<?php endif; ?>

	<section id="primary">
		<?php if ( ! empty( $loghi_patrocini ) ) : ?>
		<aside class="py-8 bg-gray-50 px-2">
			<h2 class="sr-only"><?= __('Patrocini dell\'ultima edizione','wanda'); ?></h2>
			<div class="mx-auto max-w-wide grid auto-cols-fr gap-4 place-items-center">
				<?php foreach ( $loghi_patrocini as $logo ) {
					echo wp_get_attachment_image( $logo['ID'], 'medium', false, array( 'class' => 'w-full h-auto object-contain mb-4 max-w-26 max-h-20' ) );
				} ?>
			</div>
		</aside>
		<?php endif; ?>
		<main id="main" class="mx-auto max-w-wide">

			<div class="my-6 flex flex-col justify-between gap-8 md:flex-row px-2">
				<article class="prose max-w-content p-2">
					<?= wp_kses_post( $homepage['descrizione_del_sito'] ); ?>
				</article>
				<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<aside class="p-2 md:max-w-3/12" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'wanda' ); ?>">
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
				<hr class="mx-auto mt-12 mb-6 w-32 border-0 border-b-4 border-dotted border-secondary/50" />
				<h2 class="text-center text-3xl italic my-4"> <?= __('Gli ultimi articoli', 'wanda'); ?> </h2>
				<div id="#latest-posts" class="posts-grid">

				<?php
				while ( $query->have_posts() ) : 
					$query->the_post();
					
					get_template_part( 'template-parts/content/content', 'title' );
			
				endwhile; // End of the loop.
				wp_reset_postdata(); // Restores the global $post object
				?>

			</div> <!-- #latest-posts -->

			<?php endif; ?>

		</main><!-- #main -->

		<?php
		if ( $homepage ): 
		?>
		<section id="final-cta" class="mx-auto my-12 flex max-w-content flex-col justify-between gap-4 bg-primary-900 p-6 text-white md:flex-row">
			<?php if ( ! empty( $homepage['cta_finale_img'] ) ) : ?>
				<img class="md:max-w-4/12 aspect-square object-contain w-full h-full" src="<?php echo esc_url($homepage['cta_finale_img']['url']); ?>" alt="<?php echo esc_attr($homepage['cta_finale_img']['alt']); ?>" role="presentation" loading="lazy" />
			<?php endif; ?>
			<div class="p-2 md:p-6">
				<?php if ( ! empty($homepage['cta_finale_titolo'])): ?>
					<h2 class="font-regular small-caps text-4xl text-primary-100 italic">
						<?= sanitize_text_field( $homepage['cta_finale_titolo'] ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty($homepage['cta_finale_body']) ): ?>
				<p class="text-2xl italic">
					<?= sanitize_text_field( $homepage['cta_finale_body'] ); ?>
				</p>
				<?php endif; ?>
				<?php if ( ! empty( $homepage['cta_finale_link'] ) ): ?>
					<a href="<?= wp_parse_url( $homepage['cta_finale_link'] ); ?>" class=" primary-button mt-4 bg-white text-primary">
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
