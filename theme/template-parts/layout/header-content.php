<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */


$banner = get_field( 'banner', 'options' );    // group: testo (wysiwyg), banner_is_active (true_false)
$bando  = get_field( 'bando', 'options' );    // file (return: array)

if ( ! empty( $banner ) && $banner['banner_is_active']): ?>
<div class="bg-tertiary text-white/90 text-center p-2 text-sm font-sans" role="banner" aria-live="polite" >
	<?= wp_kses_post( do_shortcode( $banner['testo'] ) ); ?>
</div>
<?php endif; ?>

<header id="masthead" class="flex flex-row items-center justify-between px-2 py-4 max-w-wide mx-auto border-b border-foreground/15">

	<div>
		<?php
		$wanda_description = get_bloginfo( 'description', 'display' );
		if ( $wanda_description || is_customize_preview() ) :
			?>
			<p class="italic font-title font-light text-primary-900 leading-none"><?php echo $wanda_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>

		<?php
		if ( is_front_page() ) :
			?>
			<h1 class="font-bold font-title uppercase text-primary"><?php bloginfo( 'name' ); ?></h1>
			<?php
		else :
			?>
			<p class="font-bold font-title uppercase text-primary"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>
	</div>

	<nav id="site-navigation" class="flex flex-row justify-end items-center gap-4" aria-label="<?php esc_attr_e( 'Navigazione principale', 'wanda' ); ?>">
		<!-- <button aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu principale', 'wanda' ); ?></button> -->

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s menu" aria-label="submenu">%3$s</ul>',
			)
		);
		if( ! empty( $bando ) ): ?>
			<a 
			class="primary-button"
			href="<?php esc_html_e($bando); ?>"
			target="_blank"
			rel="noopener nofollow noreferrer">
				<?= __('Bando','wanda') . ' ' . date("Y"); ?>
			</a>
		<?php endif; ?>
	</nav><!-- #site-navigation -->

</header><!-- #masthead -->


<?php
if ( function_exists( 'rank_math_the_breadcrumbs' ) && ! is_front_page() ): ?>
<div class="px-2 py-4 max-w-wide mx-auto">
	<?php rank_math_the_breadcrumbs(); ?>
</div>
<?php endif; ?>
