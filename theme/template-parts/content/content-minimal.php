<?php
/**
 * Template part for displaying post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wanda
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
	<div <?php wanda_content_class( 'entry-content' ); ?>>
		<?php
		the_content();
		?>
	</div><!-- .entry-content -->

</article><!-- #post-${ID} -->
