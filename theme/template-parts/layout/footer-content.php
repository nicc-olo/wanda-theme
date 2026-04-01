<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

 $copyright = get_field( 'copyright', 'options' );
 $descrizione = get_field( 'breve_descrizione_del_sito', 'options' );
 $mostra_edizioni_footer = get_field( 'mostra_edizioni_footer', 'options' );
?>

<footer id="colophon" class="bg-neutral-100 text-muted">
	<div class="mx-auto flex max-w-wide flex-row flex-wrap items-start justify-between gap-8 p-4 md:py-6">

		<div class="flex md:max-w-6/12 flex-col md:flex-row items-start gap-4">
			<?php if ( has_custom_logo() ) {
				$logo = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $logo , 'full' );
				echo '<img src='. $image[0] .' alt="" role="presentation" class="custom-logo object-scale-down max-w-48 block">';
			} ?>

			<div>
				<h2 class="text-lg"><em><?php bloginfo('description'); ?></em> <strong><?php bloginfo('name'); ?></strong></h2>
				<p class="text-sm italic"><?= wp_kses($descrizione, array('strong','b','i','em')) ?></p>
			</div>
		</div>

		<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
			<div>
			<h2 class="small-caps mb-4 text-lg"><?php _e('Link utili','wanda'); ?></h2>
			<nav aria-label="<?php esc_attr_e( 'Menu del Footer', 'wanda' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_class'     => 'footer-menu menu flex flex-col gap-2 font-sans',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			</div>
		<?php endif; ?>
		<?php if ( $mostra_edizioni_footer ) : ?>
			<div>
				<h2 class="small-caps mb-4 text-lg"><?php _e('Edizioni','wanda'); ?></h2>
				<nav aria-label="<?php esc_attr_e( 'Menu delle edizioni', 'wanda' ); ?>">
					<ul class="menu flex flex-col gap-2 font-sans">
					<?php 
					// wp query latest 5 edizioni
					$args = array(
						'post_type' => 'edizione',
						'posts_per_page' => 5,
						'orderby' => 'date',
						'order' => 'DESC',
						'post_status' => 'publish',
					);
					$query = new WP_Query( $args );
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							echo '<li class="menu-item"><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
						endwhile;
					endif;
					wp_reset_postdata();
					?>
					<li>
						<a href="<?php echo home_url( '/edizioni-passate' ); ?>"><?php _e('Altre edizioni','wanda'); ?></a>
					</li>
					</ul>
				</nav>
			</div>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'menu-4' ) ) : ?>
			<div>
			<h2 class="small-caps mb-4 text-lg"><?php _e('Link Social','wanda'); ?></h2>
			<nav aria-label="<?php esc_attr_e( 'Menu dei social', 'wanda' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-4',
						'menu_class'     => 'social-menu menu flex flex-col gap-2 font-sans',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			</div>
		<?php endif; ?>


	</div>
	<div class="border-t-2 border-secondary bg-primary p-3 text-center font-sans text-sm text-primary-100">
		<?php
		$wanda_blog_info = get_bloginfo( 'name' );
		if ( ! empty( $copyright ) ): echo wp_kses_post( do_shortcode( $copyright ) );
		elseif ( ! empty( $wanda_blog_info ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>,
			<?php
		endif;
		?>
		<nav class="mt-2 flex flex-row items-center justify-center gap-4 text-xs text-inherit opacity-50 transition-all focus:opacity-100">
			<?php
				wp_nav_menu(
				array(
					'theme_location' => 'menu-3',
					'menu_id'        => 'legal-menu',
					'menu_class'     => 'flex flex-row gap-4',
					'items_wrap'     => '<ul id="%1$s" class="%2$s flex flex-row gap-4" aria-label="menu dei documenti legali">%3$s</ul>',

				)
			);
			?>
			<a class="no-underline hover:underline focus:underline" href="https://nicc-olo.com" rel="nofollow">Credits: <span class="sr-only">olo</span> <attr title="OLO" aria-hidden="true">o&iota;͜&sigma;</attr></a>
		</nav>
	</div>
</footer><!-- #colophon -->
