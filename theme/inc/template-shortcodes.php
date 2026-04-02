<?php
/**
 * Custom shortcodes for this theme
 *
 * @package wanda
 */




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



/** Shortcode per mostrare i contatti */
function wanda_contatti($atts) {

    $pairs = [
        'tipo' => 'email', // default: email
    ];
	if ( isset($atts[0]) ) {
        $pairs['tipo'] = $atts[0]; // tipo: email, numero_di_telefono
    }
    $atts = shortcode_atts($pairs, $atts, 'contatto');

    $allowed_types = ['email', 'numero_di_telefono'];
    if ( ! in_array($atts['tipo'], $allowed_types, true) ) {
        return 'tipo di contatto non valido'; 
    }

    $contatti = get_field( 'contatti', 'option' );
    $value = is_array( $contatti ) ? ( $contatti[ $atts['tipo'] ] ?? '' ) : '';

    if ( ! $value ) {
        return 'N/A';
    }

    if ( $atts['tipo'] === 'email' ) {
        $clean_email = antispambot($value);
        return '<a href="mailto:' . $clean_email . '">' . $clean_email . '</a>';
    }

    if ( $atts['tipo'] === 'numero_di_telefono' ) {
        // Remove spaces/dashes for the tel: link
        $tel_link = preg_replace('/[^0-9+]/', '', $value);
        return '<a href="tel:' . esc_attr($tel_link) . '">' . esc_html($value) . '</a>';
    }

    return esc_html($value);
}

add_shortcode('contatto', 'wanda_contatti');



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


function wanda_get_edition_details( $post_id = null ) {
    // 1. If no ID is provided, find the last edition
    if ( ! $post_id ) {
        $last_edition = new WP_Query( array(
            'post_type'           => 'edizione',
            'posts_per_page'      => 1,
            'meta_key'            => 'edizione_data_serata',
            'orderby'             => 'meta_value',
            'order'               => 'DESC',
            'post_status'         => 'publish',
            'fields'              => 'ids', // Performance: only get IDs
            'no_found_rows'       => true,
        ) );

        if ( $last_edition->have_posts() ) {
            $post_id = $last_edition->posts[0];
        } else {
            return false; // No editions found
        }
    }

    $data_raw = get_field( 'edizione_data_serata', $post_id ); // Y-m-d H:i:s
    
    if ( ! $data_raw ) return false;

    $date_obj = DateTime::createFromFormat( 'Y-m-d H:i:s', $data_raw );

    if ( ! $date_obj ) return false;

    // 4. Return an easy-to-use array of data
    return array(
        'id'           => $post_id,
        'url'          => get_permalink( $post_id ),
        'title'        => get_the_title( $post_id ),
        'year'         => $date_obj->format( 'Y' ),
        'time'         => $date_obj->format( 'H.i' ),
        'date_display' => date_i18n( 'j F', $date_obj->getTimestamp() ), // Translated
        'is_past'      => ( new DateTime() > $date_obj ),
        'raw_obj'      => $date_obj // For custom
    );
}


/** Returns the latest "edition" of the festival based on the last "edition" post type, or the current year */
function wanda_current_edition() {

	$edition = wanda_get_edition_details();

	if ( ! $edition ) {
        return __('Edizione', 'wanda') . ' ' . date('Y');
    }

	$edition_number = [];

	if ( ! empty($edition) ){
		$edition_number = [];

		preg_match(
			'/^[MDCLXVI]{2,}|[MDCLXVI]{2,}$/i', // search for roman numbers in the string either at the start or end
			$edition['title'], 
			$edition_number
		);
	}

	$display_value = ! empty( $edition_number ) ? strtoupper($edition_number[0]) : $edition['year'];

	return __('Edizione', 'wanda') . ' ' . $display_value;
}

add_shortcode('edizione', 'wanda_current_edition');


// Shortcode per creare una galleria con Swiper.js:
// [gallery]<img src="..." /><img src="..." /><img src="..." />[/gallery]
function wanda_swiper_gallery( $atts, $content = null ) {
    if ( empty( $content ) ) {
        return '';
    }

    preg_match_all( '/<img\b[^>]*>/i', $content, $matches );

    if ( empty( $matches[0] ) ) {
        return '';
    }

    $allowed_img_attrs = array(
        'src'      => true,
        'srcset'   => true,
        'sizes'    => true,
        'alt'      => true,
        'class'    => true,
        'id'       => true,
        'width'    => true,
        'height'   => true,
        'loading'  => true,
        'decoding' => true,
        'title'    => true,
    );

    $output = '<div class="swiper bg-slate-900 my-8">';
    $output .= '<div class="swiper-wrapper items-center">';

    foreach ( $matches[0] as $img_tag ) {
        $output .= '<div class="swiper-slide max-h-full max-w-full">';
        $output .= wp_kses( $img_tag, array( 'img' => $allowed_img_attrs ) );
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '<div class="swiper-pagination"></div>';
    $output .= '<div class="swiper-button-prev"></div>';
    $output .= '<div class="swiper-button-next"></div>';
    $output .= '<div class="autoplay-progress"><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="20"></circle></svg><span></span></div>';
    $output .= '</div>';

    return $output;
}

add_shortcode('gallery', 'wanda_swiper_gallery');



/** shortcode per mettere due elementi in colonna  */

function wanda_two_columns( $atts, $content = null ) {

    if ( empty( $content ) ) {
        return '';
    }

    $allowed_alignments = [
        'left' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
        'between' => 'justify-between',
        'around' => 'justify-around',
        'evenly' => 'justify-evenly',
    ];

    $allowed_columns = [
        'auto' => 'grid-cols-auto',
        '2' => 'grid-cols-2',
        '4' => 'md:grid-cols-2 lg:grid-cols-4',
    ];

    $pairs = [ 
        'align' => 'left',
        'cols' => '2',
    ];

    $atts = shortcode_atts($pairs, $atts, 'in_linea');
    if ( ! in_array($atts['align'], array_keys($allowed_alignments), true) ) {
        $atts['align'] = 'left';
    }

    if ( ! in_array($atts['cols'], array_keys($allowed_columns), true) ) {
        $atts['cols'] = '2';
    }

    // remove br and p tags
    $content = preg_replace( '/<br\s*\/?>|<p\s*\/?>/', '', $content );
    return '<div class="grid gap-4 ' . $allowed_alignments[$atts['align']] . ' ' . $allowed_columns[$atts['cols']] . '">' . $content . '</div>';
}

add_shortcode('in_linea', 'wanda_two_columns');