<?php
/**
 * The template for displaying all edizione posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wanda
 */


get_header();

$luogo_serata 		= get_field('edizione_luogo'); // testo
$data_serata 		= DateTime::createFromFormat('Y-m-d H:i:s', get_field('edizione_data_serata')); // data Y-m-d H:i:s (required)
$promosso_da 		= get_field('edizione_promosso_da'); // WYSIWYG Editor 
$ingresso 			= get_field('edizione_ingresso'); // testo
$presentatore 		= get_field('edizione_presentatore'); // testo
$esibizione 		= get_field('edizione_esibizione'); // WYSIWYG Editor 
$finalisti 			= get_field('edizione_finalisti'); // WYSIWYG Editor 
$finalisti_list_raw = get_field('edizione_finalisti_list'); // Repeater (Relationship + Select)
$giuria_intro 		= get_field('edizione_giuria_wysiwyg'); // WYSIWYG Editor 
$giuria_list 		= get_field('edizione_giuria_relationship'); // Relationship 
$giuria_comm_list 	= get_field('edizione_commissione_relationship'); // WYSIWYG Editor 
$altre_informazioni = get_field('edizione_altre_informazioni'); // WYSIWYG Editor 
$regolamento 		= get_field('edizione_regolamento'); // WYSIWYG Editor 
$file_regolamento 	= get_field('edizione_regolamento_file'); // file 
$file_catalogo 		= get_field('edizione_catalogo_file'); // file 
$loghi_patrocini 	= get_field('edizione_lista_patrocini'); // gallery 
$loghi_sostenitori 	= get_field('edizione_lista_sostenitori'); // gallery

$anno_edizione 		= $data_serata->format('Y'); 
$orario_serata		= $data_serata->format('H:i');
$giorno_serata 		= date_i18n('j F', $data_serata->getTimestamp()); // data tradotta wp
$is_past_event_date = new DateTime() > $data_serata;


// Filter duplicates from $finalisti_list_raw
$podio_rows = [];   // posizione 1,2,3
$other_rows = [];   // posizione 0 (or missing/invalid)
$seen_ids   = [];

foreach ((array) $finalisti_list_raw as $ui_index => $row) {
	$finalista = $row['finalista'][0] ?? null;
	if (! $finalista || empty($finalista->ID)) {
		continue;
	}

	$id = (int) $finalista->ID;
	if (isset($seen_ids[$id])) {
		continue; // dedupe
	}
	$seen_ids[$id] = true;

	$pos = (string) ($row['posizione_in_classifica'] ?? '0');
	$row['_ui_index'] = $ui_index;

	if (in_array($pos, ['1', '2', '3'], true)) {
		$podio_rows[] = $row;
	} else {
		$other_rows[] = $row;
	}
}

// Order winners by rank 1 -> 2 -> 3, preserving UI order inside same rank
$rank = ['1' => 0, '2' => 1, '3' => 2];
usort($podio_rows, function ($a, $b) use ($rank) {
	$pa = (string) ($a['posizione_in_classifica'] ?? '0');
	$pb = (string) ($b['posizione_in_classifica'] ?? '0');
	$ra = $rank[$pa] ?? 99;
	$rb = $rank[$pb] ?? 99;

	if ($ra !== $rb) {
		return $ra <=> $rb;
	}

	return ($a['_ui_index'] ?? 0) <=> ($b['_ui_index'] ?? 0);
});

// Keep others in UI order
usort($other_rows, fn($a, $b) => ($a['_ui_index'] ?? 0) <=> ($b['_ui_index'] ?? 0));



// TODO
// ?scheda=tabId
$active_tab = filter_input(INPUT_GET, 'scheda', FILTER_SANITIZE_SPECIAL_CHARS);
$accepted_tabs = [
	'intro',
	'news',
	'programma',
	'iscrizione',
	'giuria',
	'finalisti',
	'sostenitori',
];

if (! in_array($active_tab, $accepted_tabs)) {
	$active_tab = 'intro';
}

?>

	<div class="bg-tertiary-900 text-tertiary-100 text-center min-h-48 flex flex-col justify-center items-center gap-2">
		<h1 class="entry-title text-4xl mb-0 text-white">
			<strong class="text-6xl"><?php the_title(); ?></strong><br>
			del <?= bloginfo('description');?>  <em><?= bloginfo('name');?></em>
		</h1>
		<p>&mdash; Anno <?= esc_html( $anno_edizione ); ?> &mdash; </p>
	</div>

	<section id="primary" class="max-w-wide mx-auto mb-12">
		<noscript>
			<p role="alert" class="bg-red-50 border-l-4 border-red-900 text-red-950 p-2 my-2"> 
				<?php _e('Devi attivare javascript per poter visualizzare correttamente questo contenuto','wanda'); ?> 
				<a href="https://www.enable-javascript.com/it/"> <?php _e('Abilita Javascript','wanda');?> </a>
			</p>
		</noscript>
		<main id="main" class="relative">
			<?php /*TABLIST Accessibile per navigare tra le varie sezioni dell'Evento */ ?>
			<nav class="sticky top-0 z-20 max-w-full bg-neutral-100 p-2">
				<ul class="tab-nav-list overflow-scroll flex flex-row gap-2 items-center justify-evenly" role="tablist" aria-label="<?php _e('Navigazione dei contenuti','wanda'); ?>">
					<li><button role="tab" aria-controls="intro" id="intro-control" aria-selected="<?= $active_tab == 'intro'; ?>">
						<?php _e('Introduzione','wanda'); ?>
					</button></li>
					<li><button role="tab" aria-controls="news" id="news-control" aria-selected="<?= $active_tab == 'news'; ?>">
						<?php _e('News e Info','wanda'); ?>
					</button></li>
					<li><button role="tab" aria-controls="programma" id="programma-control" aria-selected="<?= $active_tab == 'programma'; ?>">
						<?php _e('Programma','wanda'); ?>
					</button></li>
					<?php if ( ! $is_past_event_date ): ?>
						<li><button role="tab" aria-controls="iscrizione" id="iscrizione-control" aria-selected="<?= $active_tab == 'iscrizione'; ?>">
						<?php _e('Come partecipare','wanda'); ?>
					</button></li>
					<?php endif; ?>
					<li><button role="tab" aria-controls="giuria" id="giuria-control" aria-selected="<?= $active_tab == 'giuria'; ?>">
						<?php _e('La Giuria','wanda'); ?>
					</button></li>
					<li><button role="tab" aria-controls="finalisti" id="finalisti-control" aria-selected="<?= $active_tab == 'finalisti'; ?>">
						<?php
						if ( $is_past_event_date ) {
							_e('Vincitori (e finalisti)','wanda');
						} else {
							_e('Finalisti','wanda');
						} ?>
					</button></li>
					<li><button role="tab" aria-controls="sostenitori" id="sostenitori-control" aria-selected="<?= $active_tab == 'sostenitori'; ?>">
						<?php _e('Sostenitori','wanda'); ?>
					</button></li>
				</ul>
			</nav>

			<?php /* INTRO: Featured Image + Contenuto del post */ ?>
            <section role="tabpanel" id="intro" aria-labelledby="intro-control" <?= $active_tab == 'intro' ? '' : 'hidden'; ?>>
				<div class="w-prose">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content-minimal', 'single' );
					// End the loop.
				endwhile;
				?>
				</div>
            </section> <!-- #intro -->
			<section role="tabpanel" id="news" aria-labelledby="news-control" <?= $active_tab == 'news' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Ultime informazioni e novità su quest&apos;edizione','wanda'); ?></h2>
				<div class="posts-grid">
				<?php  
					$news_query = new WP_Query( array(
						'post_type' => 'post',
						'posts_per_page' => 9,
						'paged' => 1,
						'orderby' => 'date',
						'order' => 'DESC',
						'taxonomy' => 'tag-edizione',
					) );
					while ( $news_query->have_posts() ) {
						$news_query->the_post();
						get_template_part( 'template-parts/content/content-excerpt', 'page' );
					}
					wp_reset_postdata();
				?>
				</div>
			</section> <!-- #news -->
			<section role="tabpanel" id="programma" aria-labelledby="programma-control" <?= $active_tab == 'programma' ? '' : 'hidden'; ?>>
				<div class="flex flex-col-reverse items-start gap-6 lg:flex-row">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'class' => 'sticky top-20 w-full max-w-prose h-auto object-contain' ) );
					} ?>
					<div class="w-prose">
						<h2 class="entry-title text-center"><?php _e('Informazioni sulla serata','wanda'); ?></h2>
						<div class="p-4 bg-slate-50 my-6 w-full">
							<h3 class="small-caps text-xl mb-2"><?php _e('Data dell&apos;evento','wanda'); ?></h3>
							<p class="text-lg my-2"><?php printf(
								__('Il Concorso si svolgerà il %s alle ore %s','wanda'),
								'<strong>' . $giorno_serata . '</strong>',
								'<strong>' . $orario_serata . '</strong>',
							); ?></p>
							<p class="my-2 italic"><?php echo wp_kses(do_shortcode($ingresso), wanda_allowed_html()); ?></p>
						</div>
						<div class="p-4 bg-slate-50 my-6 w-full">
							<h3 class="small-caps text-xl mb-2"><?php _e('Luogo dell&apos;evento','wanda'); ?></h3>
							<p class="my-2"><?php echo $luogo_serata; ?></p>
							<a class="primary-button my-4" href="https://maps.google.com/?q=<?php echo urlencode($luogo_serata); ?>" target="_blank" rel="noopener nofollow noreferrer">
								<?php _e('Visualizza su Google Maps','wanda'); ?>
							</a>
						</div>
						<div class="mb-6 p-4 bg-slate-50 w-full">
							<h3 class="small-caps text-xl mb-2"><?php _e('Promosso da','wanda'); ?></h3>
							<div class="prose promotori">
								<?php echo wp_kses($promosso_da, wanda_allowed_html()); ?>
							</div>
						</div>
						<div class="my-6 p-4 bg-slate-50 w-full">
							<h3 class="text-2xl text-center"> <?php _e('Il programma della serata','wanda'); ?></h3>
							<h4 class="text-lg mt-4"><?php _e('A presentare la serata','wanda'); ?></h4>
							<p class="font-bold"><?php echo $presentatore; ?></p>
							<h4 class="text-lg mt-4"><?php _e('Esibizione','wanda'); ?></h4>
							<?php echo wp_kses($esibizione, wanda_allowed_html()); ?>
							<h4 class="text-lg mt-4"> <?php _e('A seguire si esibiranno i finalisti','wanda'); ?></p>
							<?php echo wp_kses($finalisti, wanda_allowed_html()); ?>
						</div>
						<?php if ( $giuria_intro ): ?>
							<div class="my-6 bg-slate-50 p-4 w-full">
								<h3 class="text-2xl text-center"><?php _e('La Giuria','wanda'); ?></h3>
								<div class="w-prose mx-auto max-w-content mb-8">
									<?php echo wp_kses($giuria_intro, wanda_allowed_html(true)); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( $altre_informazioni ): ?>
						<div class="prose p-4 bg-slate-50 my-6 w-full">
							<?php echo wp_kses($altre_informazioni, wanda_allowed_html(true)); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</section> <!-- #programma -->
			<?php if ( !$is_past_event_date ): ?>
			<section role="tabpanel" id="iscrizione" aria-labelledby="iscrizione-control" <?= $active_tab == 'iscrizione' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Come partecipare al concorso','wanda'); ?></h2>
				<a href="<?php echo $file_regolamento['url']; ?>" target="_blank" rel="noopener nofollow noreferrer" class="secondary-button mx-auto block w-fit">
					<?php _e('Scarica il bando','wanda'); ?>
				</a>
				<?php if ( $regolamento ): ?>
				<div class="prose max-w-content mx-auto my-6 w-full">
					<?php echo wp_kses_post($regolamento); ?>
				</div>
				<?php endif; ?>
			</section> <!-- #iscrizione -->
			<?php endif; ?>
			<section role="tabpanel" id="giuria" aria-labelledby="giuria-control" <?= $active_tab == 'giuria' ? '' : 'hidden'; ?>>
				<?php if ( $giuria_list ): ?>
					<h2 class="small-caps text-2xl text-center mt-8 mb-4"><?php _e('La Giuria','wanda'); ?></h2>
					<div class="posts-grid lg:grid-cols-4 place-items-center">
					<?php foreach ( $giuria_list as $giudice ) {
						get_template_part( 'template-parts/content/content', 'giudice', [
							'giudice_id' => $giudice->ID
						]);
					}?>
					</div>
				<?php endif; ?>
				<?php if ( $giuria_comm_list ): ?>
					<h2 class="small-caps text-2xl text-center mt-8 mb-4"><?php _e('La Commissione di selezione','wanda'); ?></h2>
					<div class="posts-grid lg:grid-cols-4 place-items-center">
						<?php foreach ( $giuria_comm_list as $giudice ) {
							get_template_part( 'template-parts/content/content', 'giudice', [
								'giudice_id' => $giudice->ID
							]);
						}?>
					</div>
				<?php endif; ?>
			</section> <!-- #giuria -->
			<section role="tabpanel" id="finalisti" aria-labelledby="finalisti-control" <?= $active_tab == 'finalisti' ? '' : 'hidden'; ?>>
				<?php if ($is_past_event_date): ?>
					<h2 class="entry-title text-center mb-2"><?php _e('I vincitori del Concorso','wanda'); ?></h2>
					<div class="posts-grid">
					<?php foreach ( $podio_rows as $row ) {
						get_template_part( 'template-parts/content/content', 'finalista', [
							'finalista_id' => $row['finalista'][0]->ID,
							'posizione_in_classifica' => (string) ($row['posizione_in_classifica'] ?? '0'),
						]);
					}?>
					</div>
					<h3 class="text-center text-xl mt-12 mb-4"><?php _e('E gli altri finalisti','wanda'); ?></h3>
				<?php else: ?>
					<h2 class="entry-title text-center"><?php _e('I Finalisti','wanda'); ?></h2>
				<?php endif; ?>
				<div class="posts-grid">
				<?php foreach ( $other_rows as $row ) {
					get_template_part( 'template-parts/content/content', 'finalista', [
						'finalista_id' => $row['finalista'][0]->ID,
						'posizione_in_classifica' => (string) ($row['posizione_in_classifica'] ?? '0'),
					]);
				}?>
				</div>
			</section> <!-- #finalisti -->
			<section role="tabpanel" id="sostenitori" aria-labelledby="sostenitori-control" <?= $active_tab == 'sostenitori' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Patrocini e Sostenitori','wanda'); ?></h2>
				<p class="max-w-content mx-auto my-2">
					<?php 
					/** translators: edizione, definizioe concorso, nome concorso */
					printf(
						__('L&apos;%s del %s %s è stata possibile grazie al sostegno di numerosi patrocini e sostenitori, di seguito elencati.'),
						apply_filters( 'the_title', get_the_title() ),
						apply_filters( 'bloginfo', get_bloginfo( 'description' ) ),
						'<em>' . apply_filters( 'bloginfo', get_bloginfo( 'title' ) ) . '</em>'
					); ?>
				</p>
				<div class="max-w-wide flex flex-col lg:flex-row gap-4 mt-8">
					<div class="border border-tertiary bg-neutral-50 p-8">
						<h3 class="text-primary text-center small-caps mb-4">Con il patrocinio di:</h3>
						<div class="flex flex-col gap-4">
							<?php foreach ( $loghi_patrocini as $logo ) {
								echo wp_get_attachment_image( $logo['ID'], 'medium', false, array( 'class' => 'w-full h-auto object-contain' ) );
							} ?>
						</div>
					</div>
					<div class="bg-neutral-50 p-8">
						<h3 class="text-primary text-center small-caps mb-4">Con il sostegno di:</h3>
						<div class="gap-4 md:columns-2 lg:columns-3">
							<?php foreach ( $loghi_sostenitori as $logo ) {
								echo wp_get_attachment_image( $logo['ID'], 'medium', false, array( 'class' => 'w-full h-auto object-contain mb-4' ) );
							} ?>
						</div>
					</div>
				</div>
			</section> <!-- #sostenitori -->
		</main><!-- #main -->

		<div>
			<?php
			/* NAGIVAZIONE TRA EDIZIONI */
			the_post_navigation(
				array(
					'next_text' => '<span aria-hidden="true">' . __( 'Edizione più recente', 'wanda' ) . '</span> ' .
						'<span class="sr-only">' . __( 'Prossima edizione:', 'wanda' ) . '</span> <br/>' .
						'<span>%title</span>',
					'prev_text' => '<span aria-hidden="true">' . __( 'Edizione passata', 'wanda' ) . '</span> ' .
						'<span class="sr-only">' . __( 'Edizione precedente:', 'wanda' ) . '</span> <br/>' .
						'<span>%title</span>',
					'in_same_term' => true,
				)
			);
			?>
		</div>
	</section><!-- #primary -->

<?php
get_footer();
