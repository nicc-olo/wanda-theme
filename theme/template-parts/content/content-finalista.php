<?php
/**
 * Template part for displaying finalists
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

$_id = $args['finalista_id'] ?? get_the_ID();
$posizione_in_classifica = $args['posizione_in_classifica'] ?? get_field('finalista_posizione_in_classifica', $_id);
$is_winner = in_array((string) $posizione_in_classifica, ['1', '2', '3'], true);

$labels = [
	'1' => '1º Classificato',
	'2' => '2º Classificato',
	'3' => '3º Classificato',
	'0' => 'Partecipante',
];
$posizione_label = $labels[(string) $posizione_in_classifica] ?? '';

$colors = [
    '1' => 'bg-amber-50 text-amber-800 border-amber-600',
    '2' => 'bg-slate-100 text-slate-700 border-slate-500',
    '3' => 'bg-yellow-50 text-yellow-900 border-yellow-800',
    '0' => 'text-gray-600 bg-gray-50 border-transparent',
];
$color = $colors[(string) $posizione_in_classifica] ?? 'border-transparent';

?>

<article id="post-<?php echo esc_attr( $_id ); ?>" <?php post_class( 'my-4 bg-gray-50', $_id ); ?> data-classifica="<?= esc_attr($posizione_in_classifica); ?>">

    <div>
    <?php if ( has_post_thumbnail( $_id ) ) : ?>
        <?php echo get_the_post_thumbnail( $_id, 'medium', array( 
            'alt'   => the_title_attribute( array( 'echo' => false, 'post' => $_id ) ), 
            'class' => 'block w-full h-42 md:h-96 object-contain' 
        ) ); ?>
    <?php else : ?>
        <div class="block w-full h-42 md:h-96 border bg-linear-to-b from-primary-100 to-secondary-100"></div>
    <?php endif; ?>
    </div>

	<header class="entry-header mt-4 border-2 <?= esc_attr($color); ?> p-2">
        <h3 class="entry-title text-xl">
            <?php echo get_the_title( $_id ); ?>
        </h3>
        <?php if ( $posizione_label ) : ?>
        <p class="small-caps text-bold text-center">
            <?= esc_html($posizione_label); ?>
        </p>
        <?php endif; ?>
	</header><!-- .entry-header -->

</article><!-- #post-<?php echo esc_attr( $_id ); ?> -->
