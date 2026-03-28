<?php
/**
 * Template part for displaying finalists
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */


$_id = $args['giudice_id'] ?? get_the_ID();

$posizione_in_classifica = get_field('finalista_posizione_in_classifica', $_id);
$ha_vinto_concorso = get_field('finalista_ha_vinto_concorso', $_id);
?>

<article id="post-<?php echo esc_attr( $_id ); ?>" <?php post_class( 'my-4', $_id ); ?>>

    <div class="border-2 <?= $ha_vinto_concorso ? 'border-amber-600' : 'border-transparent'; ?>">
    <?php if ( has_post_thumbnail( $_id ) ) : ?>
        <?php echo get_the_post_thumbnail( $_id, 'medium', array( 
            'alt'   => the_title_attribute( array( 'echo' => false, 'post' => $_id ) ), 
            'class' => 'block w-full h-auto max-h-96 aspect-3/5 object-cover' 
        ) ); ?>
    <?php else : ?>
        <div class="block aspect-3/5 h-full max-h-96 w-full border from-primary-100 to-secondary-100 bg-linear-to-b"></div>
    <?php endif; ?>
    </div>

	<header class="entry-header mt-4">
        <h3 class="entry-title">
            <a href="<?php echo esc_url( get_permalink( $_id ) ); ?>" rel="bookmark">
                <?php echo get_the_title( $_id ); ?>
            </a>
        </h3>
        <?php if ( $posizione_in_classifica ) : ?>
        <p class="small-caps text-bold text-lg text-tertiary">
            <?= esc_html($posizione_in_classifica); ?>
        </p>
        <?php endif; ?>
	</header><!-- .entry-header -->

</article><!-- #post-${ID} -->
