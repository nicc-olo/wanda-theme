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
$presentatore 		= get_field('edizione_presentatore'); // testo
$esibizione 		= get_field('edizione_esibizione'); // WYSIWYG Editor 
$finalisti 			= get_field('edizione_finalisti'); // WYSIWYG Editor 
$giuria 			= get_field('edizione_giuria'); // WYSIWYG Editor 
$commissione 		= get_field('edizione_commissione'); // WYSIWYG Editor 
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

// ?scheda=tabId
$active_tab = filter_input(INPUT_GET, 'scheda', FILTER_SANITIZE_SPECIAL_CHARS);
$accepted_tabs = [
	'intro',
	'info',
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
					<li><button role="tab" aria-controls="info" id="info-control" aria-selected="<?= $active_tab == 'info'; ?>">
						<?php _e('Info Evento','wanda'); ?>
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
				<div class="flex flex-col-reverse items-start gap-6 lg:flex-row">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'class' => 'w-full max-w-prose h-auto object-contain' ) );
					} ?>
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
				</div>
            </section> <!-- #intro -->
			<section role="tabpanel" id="info" aria-labelledby="info-control" <?= $active_tab == 'info' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Informazioni sull&apos;evento','wanda'); ?></h2>
			</section> <!-- #info -->
			<section role="tabpanel" id="programma" aria-labelledby="programma-control" <?= $active_tab == 'programma' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Il programma della serata','wanda'); ?></h2>
			</section> <!-- #programma -->
			<?php if ( ! $is_past_event_date ): ?>
			<section role="tabpanel" id="iscrizione" aria-labelledby="iscrizione-control" <?= $active_tab == 'iscrizione' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Come partecipare al concorso','wanda'); ?></h2>
			</section> <!-- #iscrizione -->
			<?php endif; ?>
			<section role="tabpanel" id="giuria" aria-labelledby="giuria-control" <?= $active_tab == 'giuria' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('La Giuria','wanda'); ?></h2>
			</section> <!-- #giuria -->
			<section role="tabpanel" id="finalisti" aria-labelledby="finalisti-control" <?= $active_tab == 'finalisti' ? '' : 'hidden'; ?>>
				<?php if ($is_past_event_date): ?>
					<h2 class="entry-title text-center"><?php _e('Vincitori del Concorso','wanda'); ?></h2>
				<?php endif; ?>
				<h2 class="entry-title text-center"><?php _e('I Finalisti','wanda'); ?></h2>
			</section> <!-- #finalisti -->
			<section role="tabpanel" id="sostenitori" aria-labelledby="sostenitori-control" <?= $active_tab == 'sostenitori' ? '' : 'hidden'; ?>>
				<h2 class="entry-title text-center"><?php _e('Patrocini e Sostenitori','wanda'); ?></h2>
				<p class="w-prose my-2">
					<?php 
					/** translators: edizione, definizioe concorso, nome concorso */
					printf(
						__('L&apos;%s del %s %s è stata possibile grazie al sostegno di numerosi patrocini e sostenitori, di seguito elencati.'),
						apply_filters( 'the_title', get_the_title() ),
						apply_filters( 'bloginfo', get_bloginfo( 'description' ) ),
						'<em>' . apply_filters( 'bloginfo', get_bloginfo( 'title' ) ) . '</em>'
					); ?>
				</p>
				<div class="max-w-wide flex flex-col lg:flex-row gap-4">
					<div class="border border-primary bg-background-alt p-8">
						<h3 class="text-primary text-center small-caps">Con il patrocinio di:</h3>
					</div>
					<div class="p-8">
						<h3 class="text-primary text-center small-caps">Con il sostegno di:</h3>
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
