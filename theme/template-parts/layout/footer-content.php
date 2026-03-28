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
?>

<footer id="colophon" class="bg-neutral-100 text-muted">
	<div class="mx-auto max-w-wide py-6 flex flex-row flex-wrap gap-8 justify-between items-start">

		<div class="flex flex-row max-w-6/12 gap-4 items-start">
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
			<h2 class="text-lg small-caps mb-4"><?php _e('Link utili','wanda'); ?></h2>
			<nav aria-label="<?php esc_attr_e( 'Menu del Footer', 'wanda' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_class'     => 'footer-menu menu flex flex-col gap-2',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			</div>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'menu-4' ) ) : ?>
			<div>
			<h2 class="text-lg small-caps mb-4"><?php _e('Link Social','wanda'); ?></h2>
			<nav aria-label="<?php esc_attr_e( 'Menu dei social', 'wanda' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-4',
						'menu_class'     => 'social-menu menu flex flex-col gap-2',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			</div>
		<?php endif; ?>


	</div>

	<div class="text-center font-sans bg-primary text-primary-100 text-sm p-3 border-t-2 border-secondary">
		<?php
		$wanda_blog_info = get_bloginfo( 'name' );
		if ( ! empty( $copyright ) ): echo wp_kses_post( do_shortcode( $copyright ) );
		elseif ( ! empty( $wanda_blog_info ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>,
			<?php
		endif;
		?>
		<nav class="flex flex-row justify-center items-center mt-2 gap-4 opacity-50 text-xs transition-all text-inherit focus:opacity-100">
			<?php
				wp_nav_menu(
				array(
					'theme_location' => 'menu-3',
					'menu_id'        => 'legal-menu',
					'items_wrap'     => '<ul id="%1$s" class="%2$s flex flex-row gap-4" aria-label="submenu">%3$s</ul>',

				)
			);
			?>
			<a class="no-underline hover:underline focus:underline" href="https://nicc-olo.com" rel="nofollow">Credits: <span class="sr-only">olo</span> <attr title="OLO" aria-hidden="true">o&iota;͜&sigma;</attr></a>
		</nav>
	</div>

</footer><!-- #colophon -->
