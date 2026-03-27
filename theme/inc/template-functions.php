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
		$title = __( 'Category Archives: ', 'wanda' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'wanda' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'wanda' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'wanda' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'wanda' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'wanda' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'wanda' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'wanda' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'wanda' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'wanda' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'wanda' );
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
			wp_kses( __( 'Continue reading %s', 'wanda' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
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
		$moderation_note = __( 'Your comment is awaiting moderation.', 'wanda' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'wanda' );
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
						wp_kses_post( __( '%s <span class="says">says:</span>', 'wanda' ) ),
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
								__( '%1$s at %2$s', 'wanda' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'wanda' ), ' <span class="edit-link">', '</span>' );
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



/** Returns the current year  */
function wanda_current_year( ) {
	return date('Y');
}

add_shortcode('year', 'wanda_current_year');


/** Returns the end date to enroll */
function wanda_data_ultima() {

    if ( function_exists( 'get_field' ) ) { 
        $data = get_field( 'data_scadenza_iscrizioni', 'options' );
        
        if ( $data ) {
            return date_i18n( 'j F Y', strtotime( $data ) );
        }
    }
    
    // Fallback se ACF non c'è o il campo è vuoto
    return __('data da definirsi', 'wanda');
}

add_shortcode('data_ultima', 'wanda_data_ultima');


/**
 * return NULL | True | False;
 */
function wanda_is_past_enrollment_date() {
	if ( !function_exists( 'get_field' ) ) return NULL;

	$data_scadenza = get_field( 'data_scadenza_iscrizioni', 'options' ) ;
 	if ( !$data_scadenza ) return NULL;

	// se il tempo trascorso fino ad adesso è maggiore del tempo trascorso fino alla data_scadenza, le iscrizioni sono già concluse
	return time() > strtotime( $data_scadenza ); 
}



/**
 * Countdown alla data iscrizioni
 */
function wanda_countdown_scadenza() {
    if ( !function_exists( 'get_field' ) ) return '';

    $data_scadenza = get_field( 'data_scadenza_iscrizioni', 'options' );
    if ( !$data_scadenza ) return '<p>' . __('Data non disponibile', 'wanda') . '</p>';

	/* translators: %s: estensione della parola giorni per screen reader */
	$label_days = sprintf(__('g%s', 'wanda'), '<span class="sr-only">' . __('iorni', 'wanda') . '</span>');
    $label_hours = sprintf(__('h%s', 'wanda'), '<span class="sr-only">' . __('ore', 'wanda') . '</span>');
    $label_mins = sprintf(__('m%s', 'wanda'), '<span class="sr-only">' . __('inuti', 'wanda') . '</span>');
    $label_secs = sprintf(__('s%s', 'wanda'), '<span class="sr-only">' . __('econdi', 'wanda') . '</span>');

    $iso_date = date('c', strtotime($data_scadenza));
	$id = uniqid();

    ob_start(); 
    ?>
    <div class="wanda-countdown">
        <div class="wanda-timer mx-auto w-fit" 
			 id="wanda-timer-<?= $id; ?>"
             data-deadline="<?php echo esc_attr($iso_date); ?>" 
             role="timer" 
             aria-live="polite" 
             aria-atomic="true">
            
            <span class="unit-group">
                <span class="number days">00</span>
                <span class="label"><?= $label_days; ?></span>
            </span>
            
            <span class="unit-group">
                <span class="number hours">00</span>
                <span class="label"><?= $label_hours; ?></span>
            </span>

            <span class="unit-group">
                <span class="number minutes">00</span>
                <span class="label"><?= $label_mins; ?></span>
            </span>

            <span class="unit-group">
                <span class="number seconds">00</span>
                <span class="label"><?= $label_secs; ?></span>
            </span>
        </div>
        
        <div class="bg-red-100 text-red-950 border-l-4 border-red-900 p-2" id="wanda-expired-<?= $id; ?>" style="display:none;" role="alert">
            <?php _e('Le iscrizioni sono ufficialmente chiuse.', 'wanda'); ?>
        </div>
    </div>

    <script>
    (function() {
        const timerEl = document.getElementById('wanda-timer-<?= $id; ?>');
        if (!timerEl) return;

        const deadline = new Date(timerEl.getAttribute('data-deadline')).getTime();
        const expiredEl = document.getElementById('wanda-expired-<?= $id; ?>');

        const updateTimer = setInterval(() => {
            const now = new Date().getTime();
            const diff = deadline - now;

            if (diff <= 0) {
                clearInterval(updateTimer);
                timerEl.style.display = 'none';
                expiredEl.style.display = 'block';
                return;
            }

            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            timerEl.querySelector('.days').textContent = d;
            timerEl.querySelector('.hours').textContent = h.toString().padStart(2, '0');
            timerEl.querySelector('.minutes').textContent = m.toString().padStart(2, '0');
            timerEl.querySelector('.seconds').textContent = s.toString().padStart(2, '0');
        }, 1000);
    })();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('countdown_scadenza', 'wanda_countdown_scadenza');


/** Returns the latest "edition" of the festival based on the last "edition" post type  published, or the current year */
function wanda_current_edition() {

	$edtion = get_posts([
		'numberposts' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_type' => 'edizione',
		'post_status' => 'publish'
	]);
	if ( ! empty($edition) ){
		$edition_number = [];

		preg_match(
			'/^[MDCLXVI]{2,}|[MDCLXVI]{2,}$/i', // search for roman numbers in the string either at the start or end
			apply_filters( 'the_title', $edition[0]->title ), 
			$edition_number
		);
	}

	$last_edition = ! empty( $edition_number ) ? $edition_number[0] : date('Y');
	return __('Edizione', 'wanda') . ' ' . $last_edition;
}

add_shortcode('edizione', 'wanda_current_edition');