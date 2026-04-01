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
<div class="bg-tertiary p-2 text-center font-sans text-sm text-white/90" role="banner" aria-live="polite" >
	<?= wp_kses_post( do_shortcode( $banner['testo'] ) ); ?>
</div>
<?php endif; ?>

<header id="masthead" class="mx-auto flex max-w-wide flex-row items-center justify-between border-b border-foreground/15 px-2 py-4">

	<div>
		<?php
		$wanda_description = get_bloginfo( 'description', 'display' );
		if ( $wanda_description || is_customize_preview() ) :
			?>
			<p class="font-title leading-none font-light text-primary-900 italic"><?php echo $wanda_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>

		<?php
		if ( is_front_page() ) :
			?>
			<h1 class="font-title font-bold text-primary uppercase"><?php bloginfo( 'name' ); ?></h1>
			<?php
		else :
			?>
			<p class="font-title font-bold text-primary uppercase"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>
	</div>

	<input aria-hidden="true" type="checkbox" id="menu-state" />
	<nav id="site-navigation" class="relative flex flex-row items-center justify-end gap-4" aria-label="<?php esc_attr_e( 'Navigazione principale', 'wanda' ); ?>">
		<div class="hamburger-button p-4 transition-transform hover:scale-105 hover:cursor-pointer hover:bg-primary-50">
			<a class="menu-open" role="button" href="#menu-state" aria-controls="primary-menu" aria-expanded="false">
				<span class="sr-only"><?= __('Apri il menu','wanda'); ?></span>
			</a>
			<a class="menu-close" role="button" href="#" aria-controls="primary-menu" aria-expanded="true">
				<span class="sr-only"><?= __('Chiudi il menu','wanda'); ?></span>
			</a>
		<label for="menu-state" aria-hidden="true">
			<span class="menu-open">&equiv;</span>
			<span class="menu-close">&times;</span>
		</label>
		<!-- <button class="lg:hidden" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'wanda' ); ?></button> -->
		</div>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s hamburger-menu" aria-label="submenu">%3$s</ul>',
				'walker'         => new Wanda_Details_Menu_Walker(),
			)
		);
		if( ! empty( $bando ) && ! wanda_is_past_enrollment_date() ): ?>
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
<div class="mx-auto max-w-wide px-2 py-4">
	<?php rank_math_the_breadcrumbs(); ?>
</div>
<?php endif; ?>
