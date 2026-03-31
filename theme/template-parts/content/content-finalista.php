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

$border_colors = [
    '1' => 'border-amber-600',
    '2' => 'border-slate-500',
    '3' => 'border-yellow-900',
    '0' => 'border-transparent',
];
$border_color = $border_colors[(string) $posizione_in_classifica] ?? 'border-transparent';

?>

<article id="post-<?php echo esc_attr( $_id ); ?>" <?php post_class( 'my-4', $_id ); ?> data-classifica="<?= esc_attr($posizione_in_classifica); ?>">

    <div class="border-2 <?= esc_attr($border_color); ?>">
    <?php if ( has_post_thumbnail( $_id ) ) : ?>
        <?php echo get_the_post_thumbnail( $_id, 'medium', array( 
            'alt'   => the_title_attribute( array( 'echo' => false, 'post' => $_id ) ), 
            'class' => 'block w-full h-auto max-h-96 aspect-3/5 object-cover' 
        ) ); ?>
    <?php else : ?>
        <div class="block aspect-3/5 h-full max-h-96 w-full border bg-linear-to-b from-primary-100 to-secondary-100"></div>
    <?php endif; ?>
    </div>

	<header class="entry-header mt-4">
        <h3 class="entry-title">
            <a href="<?php echo esc_url( get_permalink( $_id ) ); ?>" rel="bookmark">
                <?php echo get_the_title( $_id ); ?>
            </a>
        </h3>
        <?php if ( $posizione_label ) : ?>
        <p class="small-caps text-bold p-2<?= $is_winner ? ' text-primary bg-primary-50' : 'text-gray-600 bg-gray-50' ; ?>">
            <?= esc_html($posizione_label); ?>
        </p>
        <?php endif; ?>
	</header><!-- .entry-header -->

</article><!-- #post-<?php echo esc_attr( $_id ); ?> -->
