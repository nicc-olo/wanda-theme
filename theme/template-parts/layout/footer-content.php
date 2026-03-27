<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

 $copyright = get_field( 'copyright', 'options' );
?>

<footer id="colophon" class="bg-background-alt text-muted">
	<div class="mx-auto max-w-wide flex md:flex-row py-6">

		<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Menu del Footer', 'wanda' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_class'     => 'footer-menu menu',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>

	</div>

	<div class="text-center font-sans bg-primary text-primary-100 text-sm p-2">
		<?php
		$wanda_blog_info = get_bloginfo( 'name' );
		if ( ! empty( $copyright ) ): echo wp_kses_post( do_shortcode( $copyright ) );
		elseif ( ! empty( $wanda_blog_info ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>,
			<?php
		endif;
		?>
		<nav class="flex flex-row justify-center items-center gap-4 opacity-50 text-xs transition-all text-inherit focus:opacity-100">
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
