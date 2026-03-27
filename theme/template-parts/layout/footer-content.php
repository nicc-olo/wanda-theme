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

<footer id="colophon">

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<aside role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'wanda' ); ?>">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
		<nav aria-label="<?php esc_attr_e( 'Footer Menu', 'wanda' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-2',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>

	<div>
		<?php
		$wanda_blog_info = get_bloginfo( 'name' );
		if ( ! empty( $copyright ) ): echo do_shortcode($copyright);
		elseif ( ! empty( $wanda_blog_info ) ) :
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>,
			<?php
		endif;
		?>
		<br />
		<a class="opacity-50 font-sans text-sm transition-all text-inherit no-underline hover:underline focus:underline focus:opacity-100" href="https://nicc-olo.com" rel="nofollow">Credits: <span class="sr-only">olo</span> <attr title="OLO" aria-hidden="true">o&iota;͜&sigma;</attr></a>
	</div>

</footer><!-- #colophon -->
