<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package wanda
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function wanda_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'wanda_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function wanda_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'wanda_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function wanda_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Archivio categorie: ', 'wanda' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Archivio tag: ', 'wanda' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Archivio autori: ', 'wanda' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Archivio annuale: ', 'wanda' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'wanda' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Archivio mensile: ', 'wanda' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'wanda' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Archivio giornaliero: ', 'wanda' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archivi', 'wanda' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archivi', 'wanda' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archivi:', 'wanda' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'wanda_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function wanda_can_show_post_thumbnail() {
	return apply_filters( 'wanda_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function wanda_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 *
 * @param string $more_string The string shown within the more link.
 */
function wanda_continue_reading_link( $more_string ) {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continua a leggere %s', 'wanda' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '"> ' . $continue_reading . '</a>';
	}

	return $more_string;
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'wanda_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'wanda_continue_reading_link' );

/**
 * Outputs a comment in the HTML5 format.
 *
 * This function overrides the default WordPress comment output in HTML5
 * format, adding the required class for Tailwind Typography. Based on the
 * `html5_comment()` function from WordPress core.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */
function wanda_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Il tuo commento è in attesa di moderazione.', 'wanda' );
	} else {
		$moderation_note = __( 'Il tuo commento è in attesa di moderazione. Questo è un&apos;anteprima; il tuo commento sarà visibile dopo che sarà stato approvato.', 'wanda' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						wp_kses_post( __( '%s <span class="says">dice:</span>', 'wanda' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s alle ore %2$s', 'wanda' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Modifica', 'wanda' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div <?php wanda_content_class( 'comment-content' ); ?>>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}


function wanda_allowed_html($allow_titles = false) {

    $allowed_html = wp_kses_allowed_html('post');

    // Tags to STRIP (Layout, Media, and specific Headers)
    $strip_tags = [
        'h1', 
        'section', 'article', 'aside', 'main', 'header', 'footer', // Layout
        'video', 'audio', 'track', 'source', // Media
        'iframe', 'object', 'embed',         // Iframes/Embeds
        'canvas', 'math'             		 // Scriptable/Complex graphics
    ];

	if ( ! $allow_titles ) { // other title restrictions
		$strip_tags[] = 'h2';
		$strip_tags[] = 'h3';
	}

    foreach ($strip_tags as $tag) {
        if (isset($allowed_html[$tag])) {
            unset($allowed_html[$tag]);
        }
    }

    return $allowed_html;
}



// ACF Validation for Repeater (Relationship + Select)
add_filter('acf/validate_value/name=edizione_finalisti_list', function ($valid, $value, $field, $input) {
	if ($valid !== true) {
		return $valid;
	}

	if (empty($value) || !is_array($value)) {
		return $valid;
	}

	$seen = [];

	foreach ($value as $row_index => $row) {
		// Subfield key from config: field_69cc2c2d3847f (Finalista relationship)
		$selected = $row['field_69cc2c2d3847f'] ?? null;

		// Relationship with max=1 still often returns array([post_id])
		if (is_array($selected)) {
			$selected = reset($selected);
		}

		$finalista_id = (int) $selected;
		if (!$finalista_id) {
			continue;
		}

		if (isset($seen[$finalista_id])) {
			return __('Hai selezionato lo stesso finalista più di una volta nella lista.', 'wanda');
		}

		$seen[$finalista_id] = true;
	}

	return $valid;
}, 10, 4);