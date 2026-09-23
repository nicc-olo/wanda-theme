<?php
/**
 * Template part for displaying judges
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

$_id = $args['giudice_id'] ?? get_the_ID();
$short_description = get_the_excerpt( $_id );

?>

<article id="post-<?php echo esc_attr( $_id ); ?>" <?php post_class( 'portrait-card', $_id ); ?>>

	<div class="portrait-photo">
	<?php if ( has_post_thumbnail( $_id ) ) : ?>
		<?php echo get_the_post_thumbnail( $_id, 'medium', array(
			'alt' => the_title_attribute( array( 'echo' => false, 'post' => $_id ) ),
		) ); ?>
	<?php endif; ?>
	</div>

	<div class="portrait-body">
		<h3 class="portrait-name">
			<a href="<?php echo esc_url( get_permalink( $_id ) ); ?>" rel="bookmark">
				<?php echo esc_html( get_the_title( $_id ) ); ?>
			</a>
		</h3>
		<?php if ( $short_description ) : ?>
		<p class="portrait-meta"><?php echo esc_html( wp_trim_words( $short_description, 18, '…' ) ); ?></p>
		<?php endif; ?>
	</div>

</article><!-- #post-<?php echo esc_attr( $_id ); ?> -->
