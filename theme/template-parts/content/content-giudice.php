<?php
/**
 * Template part for displaying judges
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */


$_id = $args['giudice_id'] ?? get_the_ID();

?>

<article id="post-<?php echo esc_attr( $_id ); ?>" <?php post_class( 'my-4', $_id ); ?>>

    <?php if ( has_post_thumbnail( $_id ) ) : ?>
        <?php echo get_the_post_thumbnail( $_id, 'medium', array( 
            'alt'   => the_title_attribute( array( 'echo' => false, 'post' => $_id ) ), 
            'class' => 'block w-full h-auto max-h-96 aspect-3/5 object-cover' 
        ) ); ?>
    <?php else : ?>
        <div class="block aspect-3/5 h-full max-h-96 w-full bg-linear-to-b from-primary-100 to-secondary-100"></div>
    <?php endif; ?>

	<header class="entry-header mt-4">
        <h3 class="entry-title">
            <a href="<?php echo esc_url( get_permalink( $_id ) ); ?>" rel="bookmark">
                <?php echo get_the_title( $_id ); ?>
            </a>
        </h3>
	</header><!-- .entry-header -->

	<div <?php wanda_content_class( 'entry-content', $_id ); ?>>
        <?php echo wp_trim_words( get_the_excerpt( $_id ), 20, '...' ); ?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php echo esc_attr( $_id ); ?> -->
