<?php
/**
 * The hub at /master-pages/: what the master pages are, how to use them, every page style and content template, a directory
 * of every block (read from where each block appears on the masters), and the field maps for the content types.
 * Runs last. Safe to re-run.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$hub = wlm_parent();

	/** Link to a master page by slug (or to an entry by type and slug). */
	$link = function ( $slug, $type = 'page' ) {
		$post = 'page' === $type ? get_page_by_path( 'master-pages/' . $slug ) : get_page_by_path( $slug, OBJECT, $type );

		return $post ? get_permalink( $post ) : '';
	};

	$li = function ( $label, $url, $text ) {
		return ( $url ? '<a href="' . esc_url( $url ) . '"><strong>' . esc_html( $label ) . '</strong></a>' : '<strong>' . esc_html( $label ) . '</strong>' ) . '. ' . esc_html( $text );
	};

	// ---------------------------------------------------------------- the block pages and page styles
	$block_pages = array(
		array( 'content-blocks', 'Content blocks', 'The page tops and the written-content blocks: heroes, text with photographs, image and video, gallery, statistics, banners and the call-to-action strip.' ),
		array( 'cards-and-proof', 'Cards, proof and listings', 'Logos, services, projects, insights, testimonials, headline numbers, pricing, tabs, FAQs and the featured post.' ),
		array( 'people-forms-and-contact', 'People, forms and contact blocks', 'The team grid, vacancies, contact and careers forms, and the social and map strip.' ),
	);

	$style_pages = array(
		array( 'home-page', 'Home page', 'The home page: video hero with the podcast card, logos, services, projects, testimonials, numbers and insights.' ),
		array( 'about-page', 'About page', 'A large statement, headline numbers, the team and a contact form.' ),
		array( 'services-overview-page', 'Services overview page', 'Every service as a stacked panel, then projects and insights.' ),
		array( 'service-detail-page', 'Service detail page', 'The full service page with pricing and a month-by-month tabbed plan.' ),
		array( 'service-detail-short-page', 'Service detail page (short)', 'A simpler service page without pricing or tabs.' ),
		array( 'specialism-page', 'Specialism page', 'Who you work best with: sector cards that link to filtered case studies.' ),
		array( 'careers-page', 'Careers page', 'Vacancies that open in a side panel, the team photo mosaic and the application form.' ),
		array( 'contact-page', 'Contact page', 'The contact form beside client quotes, plus social links and a map strip.' ),
		array( 'legal-page', 'Legal page', 'A long page of plain text with headings, lists and a table. Used for the cookie and privacy policies.' ),
		array( 'thank-you-page', 'Thank you page', 'The confirmation shown after a form is sent.' ),
	);

	$templates = array(
		array( 'master-insight-article', 'post', 'Insight article', 'article', 'An article with a contents list, statistics, video, banners, a gallery and questions.' ),
		array( 'master-insight-event', 'post', 'Insight event', 'event', 'An event with its date, place and booking link.' ),
		array( 'master-insight-podcast', 'post', 'Insight podcast episode', 'podcast', 'A podcast episode with the episode number, sponsor and listen link.' ),
		array( 'master-case-study', 'case_study', 'Case study', 'case-study', 'A case study with the headline result, the quote and related projects.' ),
		array( 'master-service', 'service', 'Service', 'service', 'A service entry: its card, its menu thumbnail and its page.' ),
		array( 'master-career', 'career', 'Vacancy', 'career', 'A role that opens in a side panel and has its own page.' ),
		array( 'master-proposal', 'proposal', 'Proposal', 'proposal', 'A private proposal that only signed-in users can read.' ),
	);

	$block_cards = array();
	foreach ( $block_pages as $row ) {
		if ( $link( $row[0] ) ) {
			$block_cards[] = $li( $row[1], $link( $row[0] ), $row[2] );
		}
	}

	$style_cards = array();
	foreach ( $style_pages as $row ) {
		if ( $link( $row[0] ) ) {
			$style_cards[] = $li( $row[1], $link( $row[0] ), $row[2] );
		}
	}

	$template_cards = array();
	foreach ( $templates as $row ) {
		if ( $link( $row[0], $row[1] ) ) {
			$template_cards[] = $li( $row[2], $link( $row[0], $row[1] ), $row[4] );
		}
	}

	// ---------------------------------------------------------------- directory of blocks
	// get_pages() drops the children of a private parent, so ask for them directly. The meta_key keeps the master filter out of the way.
	$children = get_posts(
		array(
			'post_type'      => 'page',
			'post_parent'    => $hub,
			'post_status'    => array( 'publish', 'private' ),
			'posts_per_page' => -1,
			'meta_key'       => '_wl_master', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);

	$where = array();

	foreach ( $children as $page ) {
		foreach ( parse_blocks( $page->post_content ) as $b ) {
			if ( $b['blockName'] && 0 === strpos( $b['blockName'], 'acf/' ) ) {
				$where[ substr( $b['blockName'], 4 ) ][ $page->ID ] = $page;
			}
		}
	}

	$rows  = array();
	$files = (array) glob( get_theme_file_path( 'blocks/*/block.json' ) );
	sort( $files );

	foreach ( $files as $file ) {
		$json = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$slug = basename( dirname( $file ) );

		if ( 'master-note' === $slug ) {
			continue;
		}

		$see = array();
		foreach ( isset( $where[ $slug ] ) ? $where[ $slug ] : array() as $page ) {
			$see[] = '<a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( preg_replace( '/^Master: /', '', get_the_title( $page ) ) ) . '</a>';
		}

		$rows[] = array(
			'<strong>' . esc_html( $json['title'] ) . '</strong>',
			esc_html( rtrim( (string) $json['description'], '.' ) ) . '.',
			implode( '<br>', $see ),
		);
	}

	// ---------------------------------------------------------------- field maps
	$maps = '';
	foreach ( $templates as $row ) {
		$file = get_theme_file_path( 'master/fieldmaps/' . $row[3] . '.json' );

		if ( ! is_readable( $file ) ) {
			continue;
		}

		$data = (array) json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$map  = array();

		foreach ( $data as $part ) {
			$map[] = array(
				'<strong>' . esc_html( $part['section'] ) . '</strong>',
				implode( '<br>', array_map( 'esc_html', (array) ( isset( $part['fields'] ) ? $part['fields'] : array() ) ) ),
				implode( '<br>', array_map( 'esc_html', (array) ( isset( $part['notes'] ) ? $part['notes'] : array() ) ) ),
			);
		}

		$maps .= wlm_h( esc_html( $row[2] ) . ': where each part comes from', 3 )
			. wlm_table( array( 'Part of the page', 'Where to find it in the editor', 'Good to know' ), $map );
	}

	// ---------------------------------------------------------------- the questions
	$qa = array(
		array( 'How do I add a block to a page?', '<p>Open the page in the editor and press the <strong>+</strong> button where you want the block. Choose <strong>Page Sections</strong> and pick the block. Fill in its fields in the panel on the right and the page shows a preview as you go.</p>' ),
		array( 'How do I copy a section from a master page?', '<p>Open the master page in the editor, click the block you like, press <strong>Ctrl+C</strong>, then open your own page, click where it should go and press <strong>Ctrl+V</strong>. Change the words and photographs, and do not copy the dashed grey notes: they are only labels.</p>' ),
		array( 'How do I copy a whole master page?', '<p>Open the master, press <strong>Ctrl+A</strong> twice to select every block, copy, and paste into a new page. Delete the dashed notes, then rewrite the content.</p>' ),
		array( 'How do I change the order of blocks?', '<p>Click a block and use the up and down arrows in its toolbar, or drag it by the handle. The page keeps the order you set.</p>' ),
		array( 'Which things are fields and which are blocks?', '<p>Case studies, insights, services and vacancies follow a fixed layout filled in from fields (boxes) in the editor, so each one looks consistent. Ordinary pages are built from blocks, so you choose the order. The field guides at the bottom of this page show which field feeds which part of each template.</p>' ),
		array( 'Who can see the master pages?', '<p>Only people signed in to the site. They are private, hidden from search engines, and kept out of listings and search results.</p>' ),
	);

	// ---------------------------------------------------------------- the page
	$content = wlm_blk(
		'hero-simple',
		array(
			'title'       => 'Master pages',
			'description' => 'One example of every kind of page and content template, filled in with every option, so you can see what the site can do and build your own pages from them.',
			'background'  => 'secondary',
		)
	)
		. wlm_list(
			array(
				'<a href="#how">How to use these pages</a>',
				'<a href="#pages">Page examples</a>',
				'<a href="#blocks">Block pages</a>',
				'<a href="#templates">Content templates</a>',
				'<a href="#directory">Every block</a>',
				'<a href="#questions">Questions</a>',
				'<a href="#fields">Where each part comes from</a>',
			)
		)
		. wlm_h( 'How to use these pages', 2, 'how' )
		. wlm_p( 'Each master is a real page or entry, built the way the site is built. Open it, look at how it is put together, then copy what you need.' )
		. wlm_p( 'Pages are made of <strong>blocks</strong>. A block is a ready-made section: a hero, a row of services, a testimonial slider. You choose the blocks, put them in the order you like and fill in the words and photographs. Everything adjusts for phones, tablets and large screens.' )
		. wlm_p( 'Case studies, insights, services and vacancies work a little differently. They follow a set layout that is filled in from boxes (fields) in the editor, so every one looks consistent. The field guides at the bottom of this page show which box feeds which part of the page.' )
		. wlm_p( 'Above each block on the example pages there is a grey dashed label that names the block, says what it is for and lists the options shown. Those labels are only there to explain. Do not copy them onto a real page.' )
		. wlm_h( 'Page examples', 2, 'pages' )
		. wlm_p( 'Finished pages, put together from blocks. Copy one and change the content.' )
		. wlm_list( $style_cards )
		. wlm_h( 'Block pages', 2, 'blocks' )
		. wlm_p( 'Every block with every layout and option on display, each with a label saying what it does.' )
		. wlm_list( $block_cards )
		. wlm_h( 'Content templates', 2, 'templates' )
		. wlm_p( 'One fully filled entry of each content type.' )
		. wlm_list( $template_cards )
		. wlm_h( 'Every block', 2, 'directory' )
		. wlm_p( 'A directory of every block, what it is for and which example shows it.' )
		. wlm_table( array( 'Block', 'What it is for', 'See it on' ), $rows )
		. wlm_h( 'Questions', 2, 'questions' )
		. wlm_blk(
			'faq',
			array(
				'variant'     => 'faqs',
				'title'       => 'Questions',
				'intro'       => 'Short answers about using the master pages.',
				'source'      => 'manual',
				'items'       => array_map(
					function ( $row ) {
						return array(
							'question' => $row[0],
							'answer'   => $row[1],
						);
					},
					$qa
				),
				'schema_faqs' => 0,
			)
		)
		. wlm_h( 'Where each part comes from', 2, 'fields' )
		. wlm_p( 'For each content template, this lists the part of the page and the field in the editor that feeds it.' )
		. $maps;

	wp_update_post(
		wp_slash(
			array(
				'ID'           => $hub,
				'post_title'   => 'Master pages',
				'post_content' => $content,
			)
		)
	);

	wlm_mark( $hub, 'Master pages' );

	return 'hub ' . $hub . ' (' . count( $rows ) . ' blocks in the directory)';
};
