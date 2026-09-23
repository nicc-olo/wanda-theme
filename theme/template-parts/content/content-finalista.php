<?php
/**
 * Template part for displaying finalists
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

$_id = $args['finalista_id'] ?? get_the_ID();
$posizione_in_classifica = (string) ( $args['posizione_in_classifica'] ?? get_field( 'finalista_posizione_in_classifica', $_id ) );
$short_description = get_the_excerpt( $_id );
$has_premio_critica = $args['finalista_premio_critica'] ?? get_field( 'finalista_premio_critica', $_id );

$labels = [
	'1' => '1º Classificato',
	'2' => '2º Classificato',
	'3' => '3º Classificato',
	'0' => 'Partecipante',
];
$posizione_label = $labels[ $posizione_in_classifica ] ?? '';

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
		<h3 class="portrait-name"><?php echo esc_html( get_the_title( $_id ) ); ?></h3>
		<?php if ( $posizione_label ) : ?>
		<p class="rank rank--<?php echo esc_attr( $posizione_in_classifica ); ?>">
			<span class="rank-rule" aria-hidden="true"></span>
			<span class="rank-label small-caps"><?php echo esc_html( $posizione_label ); ?></span>
			<span class="rank-rule" aria-hidden="true"></span>
		</p>
		<?php endif; ?>
		<?php if ( $short_description ) : ?>
		<p class="portrait-meta"><?php echo esc_html( $short_description ); ?></p>
		<?php endif; ?>
		<?php if ( $has_premio_critica ) : ?>
		<p class="premio small-caps">&bull; <?php esc_html_e( 'Premio della critica', 'wanda' ); ?> &bull;</p>
		<?php endif; ?>
	</div>

</article><!-- #post-<?php echo esc_attr( $_id ); ?> -->
