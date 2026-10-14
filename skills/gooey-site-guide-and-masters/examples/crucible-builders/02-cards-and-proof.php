<?php
/**
 * Master builder 02: cards, proof and listings (logo bar, services grid, service panel, work rollup, news rollup,
 * stat testimonial, card grid, pricing, tabbed, FAQ, featured post).
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$post_id = static function ( $slug, $type ) {
		$p = get_page_by_path( $slug, OBJECT, $type );

		return $p ? (int) $p->ID : 0;
	};

	// Services and testimonials (real sample posts).
	$svc_seo  = $post_id( 'seo-ai-discoverability', 'service' );
	$svc_web  = $post_id( 'website-design-development', 'service' );
	$svc_ppc  = $post_id( 'pay-per-click', 'service' );
	$svc_sup  = $post_id( 'website-support', 'service' );
	$svc_host = $post_id( 'hosting', 'service' );

	$tm = array();
	foreach ( array( 'alex-rayner-braxted-park-bng', 'dan-goddard-broxap', 'marcia-garcia-crucible', 'sam-whybrow-whybrow' ) as $slug ) {
		$tm[] = $post_id( $slug, 'testimonial' );
	}

	// Case studies chosen by hand.
	$cs_manual = array();
	foreach ( array(
		'a-new-website-and-seo-migration-for-wci-group',
		'form-submits-up-580-seo-for-kenwood-damp-proofing',
		'9-85-ctr-paid-management-for-duralock',
		'impressions-tripled-from-5980-to-15670-seo-for-aliwood',
	) as $slug ) {
		$cs_manual[] = $post_id( $slug, 'case_study' );
	}

	// Insights chosen by hand, and the event and podcast episodes.
	$ins_manual = array(
		$post_id( 'how-to-create-web-pages-that-drive-architect-specification', 'post' ),
		$post_id( 'ep6-building-product-marketing', 'post' ),
		$post_id( 'toxic-backlinks-are-increasing-and-ai-is-the-reason', 'post' ),
	);
	$event_id   = $post_id( 'event-monthly-industry-breakfast-at-langhams', 'post' );
	$podcast_a  = $post_id( 'ep6-building-product-marketing', 'post' );
	$podcast_b  = $post_id( 'ep5-winning-specification-in-construction', 'post' );

	// FAQ posts: there are none on the dev site yet, so the master makes four.
	$faq_ids = array();
	$faq_set = array(
		'master-faq-how-long'    => array( 'How long does it take to see SEO results?', '<p>Most construction and manufacturing websites see steady movement within three to six months. Competitive terms take longer, so we report on progress every month rather than waiting for the finish line.</p>' ),
		'master-faq-contract'    => array( 'Do I have to sign a long contract?', '<p>No. Our services run month to month once the first set-up work is done, so you stay because the results are good, not because you are tied in.</p>' ),
		'master-faq-own-site'    => array( 'Will I own my website and my data?', '<p>Yes. The website, the content and the analytics accounts are yours. We simply look after them for you.</p>' ),
		'master-faq-sector'      => array( 'Do you only work with construction businesses?', '<p>We specialise in the built environment, which includes construction, property and manufacturers of building products. That focus is why we understand how your customers search and buy.</p>' ),
	);
	foreach ( $faq_set as $slug => $faq ) {
		$faq_ids[] = wlm_entry(
			'faq',
			$slug,
			$faq[0],
			array(
				'post_content' => $faq[1],
			)
		);
	}

	// Logos.
	$logo_keys = array( 'logo-whybrow', 'logo-broxap', 'logo-croudace', 'logo-westbury', 'logo-wci', 'logo-mortgagebrain', 'logo-barbourabi' );
	$logos     = array();
	foreach ( $logo_keys as $key ) {
		$logos[] = array( 'logo' => wlm_img( $key ) );
	}
	$logos_6  = array_slice( $logos, 0, 6 );
	$logos_15 = array_merge( $logos, $logos, array( $logos[0] ) );

	$contact = home_url( '/contact/' );

	// -------------------------------------------------------------------------
	$c = wlm_note(
		'Cards, proof and listings',
		'This page shows the blocks that present your services, your work, your news and your evidence. Each block has a dashed note above it that says what it is for and which options you can see in the examples below it. To use one, add the block on any page and copy the settings you like from here.',
		array(
			'Logo bar: six logos, and fifteen logos that scroll.',
			'Services grid: cards from services, cards with your own wording, with and without a quote and additional services.',
			'Service panel: every service, or services you choose.',
			'Work rollup: the latest projects (two or four), or projects you choose.',
			'News rollup: latest, chosen by hand, or related.',
			'Stat testimonial: one testimonial, or four.',
			'Card grid: statistics (two to four) and text cards (one to three).',
			'Pricing: two, three and four columns, standard and custom.',
			'Tabbed: two, three and four tabs.',
			'FAQ: from FAQ posts, typed in, and the Questions version with pictures.',
			'Featured post: an event, a podcast, and the latest-episode label.',
		),
		'Pages, then Add New, then Add block, then Page Sections. Every example here is a normal block you can copy and paste.',
		'page',
		'top'
	);

	// ---- Logo bar -----------------------------------------------------------
	$c .= wlm_note(
		'Logo bar',
		'A row of client logos in black, to show who you work with. When the logos do not fit on one line they slide across slowly like a ticker, and they always do on tablet and mobile.',
		array(
			'First example: six logos, the smallest number allowed. On a wide screen they sit still in one row.',
			'Second example: fifteen logos, the most allowed. There are too many to fit, so they scroll. The sample set only has seven different logos, so some repeat here. Your own page should use fifteen different ones.',
			'Logos should be PNG or SVG, at least 470 pixels tall. Add the brand name as the picture description.',
		),
		'Add block, then Page Sections, then Logo bar.',
		'block',
		'logo-bar'
	);
	$c .= wlm_blk( 'logo-bar', array( 'logos' => $logos_6 ) );
	$c .= wlm_blk( 'logo-bar', array( 'logos' => $logos_15 ) );

	// ---- Services grid ------------------------------------------------------
	$c .= wlm_note(
		'Services grid',
		'The main services as big cards, with an optional customer quote and a list of additional services underneath. Most of the wording and pictures come from the service pages, so they stay in step.',
		array(
			'First example: three cards picked from your services, with a customer quote, and the additional services listed automatically.',
			'Second example: two cards, which fill the full width. No quote. The additional services are chosen by hand and given their own heading.',
			'Third example: three cards where the card title, text, button label, link and picture have all been replaced with your own. No quote, and the additional services are switched off.',
			'Leave any card field empty to use the service page instead.',
		),
		'Add block, then Page Sections, then Services grid.',
		'block',
		'services-grid'
	);
	$c .= wlm_blk(
		'services-grid',
		array(
			'title'           => 'Digital services',
			'show_quote'      => 1,
			'quote'           => $tm[0],
			'cards'           => array(
				array( 'service' => $svc_seo ),
				array( 'service' => $svc_web ),
				array( 'service' => $svc_ppc ),
			),
			'show_additional' => 1,
			'additional_label' => 'Additional services',
			'additional'      => array(),
		)
	);
	$c .= wlm_blk(
		'services-grid',
		array(
			'title'            => 'Our core services',
			'show_quote'       => 0,
			'cards'            => array(
				array( 'service' => $svc_seo ),
				array( 'service' => $svc_web ),
			),
			'show_additional'  => 1,
			'additional_label' => 'Looked after for you',
			'additional'       => array(
				array( 'service' => $svc_host ),
			),
		)
	);
	$c .= wlm_blk(
		'services-grid',
		array(
			'title'           => 'How we can help',
			'show_quote'      => 0,
			'cards'           => array(
				array(
					'service'     => $svc_seo,
					'title'       => 'Be found by specifiers',
					'description' => 'Reach the architects, contractors and buyers who search for what you make, with search that looks after Google and AI tools alike.',
					'button_text' => 'See how',
					'link'        => wlm_lnk( 'See how', home_url( '/services/' ) ),
					'image'       => wlm_img( 'spec-construction' ),
				),
				array(
					'service'     => $svc_web,
					'title'       => 'A website that sells',
					'description' => 'A fast, clear website built around the way your customers decide, with every page working towards an enquiry.',
					'button_text' => 'Find out more',
					'link'        => wlm_lnk( 'Find out more', home_url( '/services/' ) ),
					'image'       => wlm_img( 'spec-manufacturers' ),
				),
				array(
					'service'     => $svc_ppc,
					'title'       => 'Paid ads that pay back',
					'description' => 'Search and social advertising that is watched every week, so your budget goes to the clicks that turn into work.',
					'button_text' => 'Talk to us',
					'link'        => wlm_lnk( 'Talk to us', $contact ),
					'image'       => wlm_img( 'spec-property' ),
				),
			),
			'show_additional' => 0,
		)
	);

	// ---- Service panel ------------------------------------------------------
	$c .= wlm_note(
		'Service panel',
		'A tall, tidy list of services, each with its picture, used on the Services page and alongside long-form content.',
		array(
			'First example: Show every service. It lists all of your services in the order of the menu.',
			'Second example: Choose services by hand. Three services picked and put in a different order.',
		),
		'Add block, then Page Sections, then Service panel.',
		'block',
		'service-panel'
	);
	$c .= wlm_blk( 'service-panel', array( 'manual' => 0 ) );
	$c .= wlm_blk(
		'service-panel',
		array(
			'manual'   => 1,
			'services' => array( $svc_ppc, $svc_seo, $svc_host ),
		)
	);

	// ---- Work rollup --------------------------------------------------------
	$c .= wlm_note(
		'Work rollup',
		'Case studies as large picture cards, to show results. Use it on the home page, service pages and anywhere you want proof next to a promise.',
		array(
			'First example: two projects in one row, the two latest case studies.',
			'Second example: four projects in a two by two grid, the four latest.',
			'Third example: four projects chosen by hand. If you choose fewer than needed, the latest case studies fill the gaps.',
			'The heading is yours to change. The full stop after it is added for you.',
		),
		'Add block, then Page Sections, then Work rollup.',
		'block',
		'work-rollup'
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Recent projects',
			'count'  => '2',
			'manual' => 0,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Selected projects',
			'count'  => '4',
			'manual' => 0,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'    => 'Projects we are proud of',
			'count'    => '4',
			'manual'   => 1,
			'projects' => $cs_manual,
		)
	);

	// ---- News rollup --------------------------------------------------------
	$c .= wlm_note(
		'News rollup',
		'Three insight cards in a row (a slider on tablet and mobile) to link people to your latest thinking.',
		array(
			'First example: Latest. The three newest insights.',
			'Second example: Choose manually. An article, a podcast episode and another article that were picked by hand.',
			'Third example: Related. On an insight page it shows other insights that share a topic and always says Related insights. On this page, which is not an insight, it falls back to the latest, so it looks like the first example.',
		),
		'Add block, then Page Sections, then News rollup.',
		'block',
		'news-rollup'
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Latest insights',
			'source'  => 'latest',
		)
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Worth a read',
			'source'  => 'manual',
			'posts'   => $ins_manual,
		)
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Related insights',
			'source'  => 'related',
		)
	);

	// ---- Stat testimonial ---------------------------------------------------
	$c .= wlm_note(
		'Stat testimonial',
		'A big number with a customer quote beside it. The number, caption, quote and person all come from the testimonial itself, so you only choose which ones to show.',
		array(
			'First example: one testimonial, with no controls because there is nothing to switch between.',
			'Second example: four testimonials. Visitors move between them with the arrows, and each has its own number.',
			'You can choose from one to five testimonials. Add new ones under Testimonials in the menu.',
		),
		'Add block, then Page Sections, then Stat testimonial.',
		'block',
		'stat-testimonial'
	);
	$c .= wlm_blk(
		'stat-testimonial',
		array(
			'label'        => 'Customer testimonial',
			'testimonials' => array( $tm[3] ),
		)
	);
	$c .= wlm_blk(
		'stat-testimonial',
		array(
			'label'        => 'Customer testimonials',
			'testimonials' => $tm,
		)
	);

	// ---- Card grid ----------------------------------------------------------
	$c .= wlm_note(
		'Card grid',
		'A title with a row of cells beneath it. It has two versions: Stats, for big numbers, and Text, for short cards with a button.',
		array(
			'Stats, two stats, with the big coral dot before the title.',
			'Stats, three stats, with the dot switched off.',
			'Stats, four stats, with a small label, a coral highlight in the title, an introduction and the dot on.',
			'Text, one card. The two empty cells beside it show as decorative filler.',
			'Text, two cards, with one filler cell.',
			'Text, three cards, a full row. Each card has a small label, a title, a short description and a button.',
			'The coral full stop after the title is added for you.',
		),
		'Add block, then Page Sections, then Card grid.',
		'block',
		'card-grid'
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'stats',
			'show_bullet' => 1,
			'title'       => 'Results that speak for themselves',
			'stats'       => array(
				array(
					'value'   => '150+',
					'caption' => 'Projects delivered across SEO, PPC and websites',
				),
				array(
					'value'   => '10',
					'caption' => 'Years in business',
				),
			),
		)
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'stats',
			'show_bullet' => 0,
			'title'       => 'Our year in numbers',
			'stats'       => array(
				array(
					'value'   => '5*',
					'caption' => 'Average Google review score',
				),
				array(
					'value'   => '87%',
					'caption' => 'Increase in conversions for one garden room company',
				),
				array(
					'value'   => '3x',
					'caption' => 'Growth in search impressions for one manufacturer',
				),
			),
		)
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'stats',
			'show_bullet' => 1,
			'eyebrow'     => 'Why Crucible',
			'title'       => 'Built for the built environment',
			'highlight'   => 'built environment',
			'description' => 'We work with construction, property and manufacturing businesses every day, so we already know how your customers search and decide.',
			'stats'       => array(
				array(
					'value'   => '150+',
					'caption' => 'Projects delivered',
				),
				array(
					'value'   => '10',
					'caption' => 'Years in business',
				),
				array(
					'value'   => '5*',
					'caption' => 'Average review score',
				),
				array(
					'value'   => '100%',
					'caption' => 'Of our work is for the built environment',
				),
			),
		)
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'text',
			'show_bullet' => 1,
			'eyebrow'     => 'One card',
			'title'       => 'Start with a conversation',
			'description' => 'One card on its own leaves two decorative cells either side.',
			'cards'       => array(
				array(
					'eyebrow'     => 'Free of charge',
					'title'       => 'Book a website review',
					'description' => 'We look at your website through the eyes of your customers and tell you what to fix first.',
					'link'        => wlm_lnk( 'Book a review', $contact ),
				),
			),
		)
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'text',
			'show_bullet' => 0,
			'title'       => 'Two ways to begin',
			'cards'       => array(
				array(
					'eyebrow'     => 'For new websites',
					'title'       => 'Plan a new website',
					'description' => 'Tell us what your business sells and who buys it, and we will map out the pages that win the work.',
					'link'        => wlm_lnk( 'Plan a website', home_url( '/services/' ) ),
				),
				array(
					'eyebrow'     => 'For existing websites',
					'title'       => 'Improve what you have',
					'description' => 'Keep the website you have and make it work harder, with better search, speed and clearer calls to action.',
					'link'        => wlm_lnk( 'Talk to us', $contact ),
				),
			),
		)
	);
	$c .= wlm_blk(
		'card-grid',
		array(
			'variant'     => 'text',
			'show_bullet' => 1,
			'eyebrow'     => 'Three cards',
			'title'       => 'Join the team',
			'highlight'   => 'team',
			'description' => 'A full row of three cards, each with a small label, a title, a short description and a button.',
			'cards'       => array(
				array(
					'eyebrow'     => 'Hybrid',
					'title'       => 'Search specialist',
					'description' => 'Help our clients get found by the people who specify and buy building products.',
					'link'        => wlm_lnk( 'View role', home_url( '/careers/' ) ),
				),
				array(
					'eyebrow'     => 'Hybrid',
					'title'       => 'Website developer',
					'description' => 'Build fast, accessible websites in WordPress for manufacturers and property groups.',
					'link'        => wlm_lnk( 'View role', home_url( '/careers/' ) ),
				),
				array(
					'eyebrow'     => 'Office based',
					'title'       => 'Account manager',
					'description' => 'Be the friendly voice our clients rely on, from the first call through to the monthly results.',
					'link'        => wlm_lnk( 'View role', home_url( '/careers/' ) ),
				),
			),
		)
	);

	// ---- Pricing ------------------------------------------------------------
	$c .= wlm_note(
		'Pricing',
		'Side-by-side price columns for packages. The prices in these examples are placeholders only, so please enter your own.',
		array(
			'Two columns, both standard, each with a price, a short description, a list of features and a button.',
			'Three columns. The last one is Custom, which swaps the price for a big heading, for work that is quoted.',
			'Four columns: three standard (one with a different ending, such as per year) and one custom.',
			'Standard shows a label, a price and a small ending such as /month. Custom shows a big heading only.',
			'The features list is one feature per line. The button is optional.',
		),
		'Add block, then Page Sections, then Pricing.',
		'block',
		'pricing'
	);
	$c .= wlm_blk(
		'pricing',
		array(
			'title'   => 'Website care plans',
			'intro'   => 'Two simple plans for looking after your website once it is live.',
			'columns' => array(
				array(
					'style'       => 'standard',
					'label'       => 'Essential care',
					'price'       => '£000',
					'suffix'      => '/month',
					'description' => '<p>Everything that keeps a small website safe and running.</p>',
					'features'    => "Security updates\nDaily backups\nUptime monitoring",
					'link'        => wlm_lnk( 'Choose Essential', $contact ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Care and improve',
					'price'       => '£000',
					'suffix'      => '/month',
					'description' => '<p>Care plus regular improvements that help your site win more enquiries.</p>',
					'features'    => "Everything in Essential\nMonthly improvements\nA short report each month",
					'link'        => wlm_lnk( 'Choose Care and improve', $contact ),
				),
			),
		)
	);
	$c .= wlm_blk(
		'pricing',
		array(
			'title'   => 'Search packages',
			'intro'   => 'Pick the level of help that fits where your business is now. The last column shows the Custom style.',
			'columns' => array(
				array(
					'style'       => 'standard',
					'label'       => 'Starter',
					'price'       => '£000',
					'suffix'      => '/month',
					'description' => '<p>A solid base for businesses new to search.</p>',
					'features'    => "Search audit\nTechnical fixes\nQuarterly review",
					'link'        => wlm_lnk( 'Get started', $contact ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Growth',
					'price'       => '£000',
					'suffix'      => '/month',
					'description' => '<p>For businesses ready to compete for the best terms.</p>',
					'features'    => "Everything in Starter\nNew content every month\nLink building\nMonthly review",
					'link'        => wlm_lnk( 'Get started', $contact ),
				),
				array(
					'style'       => 'custom',
					'label'       => 'Tailored to you',
					'description' => '<p>Larger or more complex needs, such as several websites or many products.</p>',
					'features'    => "A plan built around your goals\nA named specialist\nFlexible reporting",
					'link'        => wlm_lnk( 'Talk to us', $contact ),
				),
			),
		)
	);
	$c .= wlm_blk(
		'pricing',
		array(
			'title'   => 'Choose your package',
			'intro'   => 'Four columns is the most this block holds. Three are standard, one is custom.',
			'columns' => array(
				array(
					'style'       => 'standard',
					'label'       => 'Single website',
					'price'       => '£0,000',
					'suffix'      => '',
					'description' => '<p>One website, designed and built from scratch.</p>',
					'features'    => "Up to ten pages\nMobile friendly\nSearch ready",
					'link'        => wlm_lnk( 'Enquire', $contact ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Website and search',
					'price'       => '£0,000',
					'suffix'      => '',
					'description' => '<p>A new website with the first six months of search work included.</p>',
					'features'    => "Everything in Single website\nSix months of search\nMonthly reports",
					'link'        => wlm_lnk( 'Enquire', $contact ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Paid ads set-up',
					'price'       => '£000',
					'suffix'      => '/year',
					'description' => '<p>Account set-up, tracking and your first campaigns.</p>',
					'features'    => "Account set-up\nConversion tracking\nFirst campaigns",
					'link'        => wlm_lnk( 'Enquire', $contact ),
				),
				array(
					'style'       => 'custom',
					'label'       => 'Bespoke',
					'description' => '<p>Several websites, a rebrand or a big migration.</p>',
					'features'    => "Planned together\nFixed milestones",
					'link'        => wlm_lnk( 'Talk to us', $contact ),
				),
			),
		)
	);

	// ---- Tabbed -------------------------------------------------------------
	$c .= wlm_note(
		'Tabbed',
		'A set of tabs that explain what happens over time, such as the first months of a service. The first tab is open when the page loads.',
		array(
			'Two tabs.',
			'Three tabs.',
			'Four tabs, the most allowed.',
			'Each tab has a short label, a coral heading, some text (use Heading 3 for a small sub-heading) and a bulleted list with its own label.',
		),
		'Add block, then Page Sections, then Tabbed.',
		'block',
		'tabbed'
	);
	$c .= wlm_blk(
		'tabbed',
		array(
			'title'       => 'What you can expect',
			'description' => 'Two stages: getting set up, and then getting going.',
			'tabs'        => array(
				array(
					'label'      => 'Set-up',
					'heading'    => 'The first month',
					'content'    => '<h3>Understanding your business</h3><p>We learn how your customers search and buy, then check your website and tracking.</p>',
					'list_label' => 'Deliverables',
					'items'      => "A search and website audit\nTracking and reporting set up\nA clear plan for the next quarter",
				),
				array(
					'label'      => 'Ongoing',
					'heading'    => 'Month two onwards',
					'content'    => '<h3>Steady improvement</h3><p>We carry out the plan and meet each month to talk through what the numbers say.</p>',
					'list_label' => 'Deliverables',
					'items'      => "New and improved pages\nA monthly report in plain English\nA monthly call with your specialist",
				),
			),
		)
	);
	$c .= wlm_blk(
		'tabbed',
		array(
			'title'       => 'Transparent deliverables',
			'description' => 'Three tabs. Everything we do is written down, so you always know what you are getting.',
			'tabs'        => array(
				array(
					'label'      => 'Month 1',
					'heading'    => 'Foundations',
					'content'    => '<h3>Audit and set-up</h3><p>We fix what is holding you back and put the measuring in place.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Technical audit\nKeyword and competitor research\nAnalytics and goals set up",
				),
				array(
					'label'      => 'Month 2 to 3',
					'heading'    => 'Momentum',
					'content'    => '<h3>Building what is missing</h3><p>New content and page improvements start to go live.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Improved key pages\nNew supporting content\nA first progress report",
				),
				array(
					'label'      => 'Month 4 +',
					'heading'    => 'Growth',
					'content'    => '<h3>Doing more of what works</h3><p>We put more effort where the results are coming from.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Ongoing content\nLink building\nQuarterly strategy review",
				),
			),
		)
	);
	$c .= wlm_blk(
		'tabbed',
		array(
			'title'       => 'Your first year',
			'description' => 'Four tabs, the most this block holds.',
			'tabs'        => array(
				array(
					'label'      => 'Quarter 1',
					'heading'    => 'Discover',
					'content'    => '<h3>Listening first</h3><p>Research, an audit and a plan.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Audit\nResearch\nPlan",
				),
				array(
					'label'      => 'Quarter 2',
					'heading'    => 'Build',
					'content'    => '<h3>Making the changes</h3><p>The big improvements go live.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Page improvements\nNew content\nTracking checks",
				),
				array(
					'label'      => 'Quarter 3',
					'heading'    => 'Grow',
					'content'    => '<h3>Reaching further</h3><p>We widen the net to the next set of terms.</p>',
					'list_label' => 'Deliverables',
					'items'      => "New landing pages\nLink building\nA progress review",
				),
				array(
					'label'      => 'Quarter 4',
					'heading'    => 'Plan ahead',
					'content'    => '<h3>Looking to next year</h3><p>We look back at the year and agree what comes next.</p>',
					'list_label' => 'Deliverables',
					'items'      => "A year in review\nNext year plan\nBudget advice",
				),
			),
		)
	);

	// ---- FAQ ----------------------------------------------------------------
	$c .= wlm_note(
		'FAQ',
		'A dark accordion where each question opens to show its answer. The first question is open when the page loads.',
		array(
			'First example: FAQs from FAQ posts. Title and a contact button on the left, and four questions you chose from the FAQs in the menu. Good for questions you reuse on several pages.',
			'Second example: FAQs typed in, with your own title and your own text under the button. Good for one-off questions.',
			'Third example: Questions. A picture on the left changes as each question opens. Each question can have an image. A short muted video is also possible but there is no sample video, so only pictures are shown here.',
			'The master makes four sample FAQ posts (slugs starting master-faq) so the first example has something to choose. Feel free to delete them once you have your own.',
			'Turn on FAQ data for search engines only when these are real, frequently asked questions.',
		),
		'Add block, then Page Sections, then FAQ.',
		'block',
		'faq'
	);
	$c .= wlm_blk(
		'faq',
		array(
			'variant'     => 'faqs',
			'source'      => 'posts',
			'faq_posts'   => $faq_ids,
			'schema_faqs' => 0,
		)
	);
	$c .= wlm_blk(
		'faq',
		array(
			'variant'     => 'faqs',
			'title'       => 'Good to know',
			'intro'       => 'Still have a question? We are always happy to talk it through with you.',
			'source'      => 'manual',
			'items'       => array(
				array(
					'question' => 'What do I need to get started?',
					'answer'   => '<p>Just a conversation. Tell us about your business and where you want it to go, and we will suggest where to begin.</p>',
				),
				array(
					'question' => 'Can you work with our existing website?',
					'answer'   => '<p>Yes. Many clients keep their current website and we improve it. If it is holding you back we will say so, and explain why.</p>',
				),
				array(
					'question' => 'How do you report on results?',
					'answer'   => '<p>You get a short monthly report in plain English, with the numbers that matter to your business, not a page of jargon.</p>',
				),
			),
			'schema_faqs' => 0,
		)
	);
	$c .= wlm_blk(
		'faq',
		array(
			'variant'          => 'questions',
			'schema_questions' => 0,
			'questions'        => array(
				array(
					'question'   => 'Why choose a specialist agency?',
					'answer'     => '<p>Because we already understand how your customers search, how long they take to decide and who influences the choice. That saves you months of explaining.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
				array(
					'question'   => 'How do you keep the website easy to use?',
					'answer'     => '<p>We design around the visitor, test with real people and keep the pages simple, so the next step is always obvious.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'photo-userfocused' ),
				),
				array(
					'question'   => 'What happens in the shadows of a project?',
					'answer'     => '<p>Planning, research and checking. Most of the work that makes a project succeed is quiet, and we show you all of it in our monthly report.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-shadow' ),
				),
			),
		)
	);

	// ---- Featured post ------------------------------------------------------
	$c .= wlm_note(
		'Featured post',
		'One insight shown big, with its picture, title, a short introduction, a button and the host. It suits an upcoming event or the newest podcast episode.',
		array(
			'First example: an event, chosen by hand, with the button text left empty so it says More details. Events show who is hosting.',
			'Second example: a podcast episode with the label set to Latest episode and the sponsor logo switched on.',
			'Third example: another podcast episode with the label Featured, no logo and a button wording of your own.',
			'Fourth example: no post chosen at all. It then shows the next event by itself, or the latest podcast when no event is coming up.',
		),
		'Add block, then Page Sections, then Featured post.',
		'block',
		'featured-post'
	);
	$c .= wlm_blk(
		'featured-post',
		array(
			'post'      => $event_id,
			'label'     => 'featured',
			'show_logo' => 0,
		)
	);
	$c .= wlm_blk(
		'featured-post',
		array(
			'post'      => $podcast_a,
			'label'     => 'latest',
			'show_logo' => 1,
		)
	);
	$c .= wlm_blk(
		'featured-post',
		array(
			'post'        => $podcast_b,
			'label'       => 'featured',
			'button_text' => 'Hear the episode',
			'show_logo'   => 0,
		)
	);
	$c .= wlm_blk(
		'featured-post',
		array(
			'post'      => 0,
			'label'     => 'featured',
			'show_logo' => 0,
		)
	);

	$id = wlm_page( 'cards-and-proof', 'Master: Cards, proof and listings', $c );

	return 'cards-and-proof page ' . $id . ' (+' . count( $faq_ids ) . ' FAQ posts)';
};
