<?php
/**
 * Base home template
 *
 * @package wanda
 */

get_header();
?>

	<section id="primary">
		<main id="main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1>
				</header><!-- .entry-header -->
				<?php
			endif;

            ?>
            <div class="posts-grid">
            <?php
			// Load posts loop.
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content', 'excerpt' );
			}
            ?>
            </div><!-- .posts-grid -->
            <?php
			// Previous/next page navigation.
			wanda_the_posts_navigation();
        else :
            // If no content, include the "No posts found" template.
            get_template_part( 'template-parts/content/content', 'none' );
        endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
