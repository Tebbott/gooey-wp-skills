<?php
/**
 * Master page styles: finished example pages built from blocks, the way the real pages are.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$post_id = function ( $slug, $type ) {
		$p = get_page_by_path( $slug, OBJECT, $type );
		return $p ? (int) $p->ID : 0;
	};
	$ids     = function ( $slugs, $type ) use ( $post_id ) {
		return array_values( array_filter( array_map( function ( $s ) use ( $post_id, $type ) {
			return $post_id( $s, $type );
		}, $slugs ) ) );
	};

	$tip = function ( $what ) {
		return wlm_note(
			'Copy this page',
			'Open this page in the editor, select all the blocks (Ctrl or Cmd + A), copy them, then paste them into a new page. Delete the dashed notes (including this one), then swap in your own words, photographs and links.',
			array( 'This note and the dashed note above it are only here to guide you: they never appear on a real page.', $what ),
			'Pages, then Add New, then paste.',
			'tip',
			'top'
		);
	};

	$cta = function () {
		$ref = (int) wl_get_synced_pattern_id( 'crucible/cta-working-with-us' );
		return $ref ? '<!-- wp:block {"ref":' . $ref . '} /-->' . "\n\n" : '';
	};

	$logos = function () {
		$l = array();
		foreach ( array( 'logo-barbourabi', 'logo-westbury', 'logo-whybrow', 'logo-wci', 'logo-broxap', 'logo-croudace' ) as $k ) {
			$l[] = array( 'logo' => wlm_img( $k ) );
		}
		return array( 'logos' => $l );
	};

	$stats = function ( $title, $highlight, $description ) {
		return array(
			'variant'     => 'stats',
			'show_bullet' => 1,
			'title'       => $title,
			'highlight'   => $highlight,
			'description' => $description,
			'stats'       => array(
				array( 'value' => '5*', 'caption' => 'Average Google review score' ),
				array( 'value' => '10', 'caption' => 'Years in business' ),
				array( 'value' => '120%', 'caption' => 'Year on year website performance' ),
				array( 'value' => '150+', 'caption' => 'Projects delivered' ),
			),
		);
	};

	$stats_text = 'We take difficult digital challenges, such as niche audiences, complex website requirements and challenging markets, and melt them down to their core components, so our clients\' websites and campaigns can sing.';

	$contact_url   = home_url( '/contact/' );
	$services_url  = home_url( '/services/' );
	$spec_url      = home_url( '/specialism/' );
	$testimonials  = $ids( array( 'alex-rayner-braxted-park-bng', 'dan-goddard-broxap', 'marcia-garcia-crucible', 'sam-whybrow-whybrow' ), 'testimonial' );
	$projects_four = $ids(
		array(
			'87-increase-in-conversions-seo-for-westbury-garden-rooms',
			'website-design-build-for-building-product-design-installation-company',
			'5-1-average-position-improvements-in-six-months-seo-for-outdoor-gym-installation-company',
			'website-support-and-management-for-leading-property-group',
		),
		'case_study'
	);
	$projects_two  = array_slice( $projects_four, 0, 2 );
	$insights      = $ids(
		array(
			'profound-raised-180m-heres-why-profound-is-already-our-ai-visibility-platform-of-choice',
			'how-to-create-a-digital-marketing-plan-top-strategies-for-smes',
			'toxic-backlinks-are-increasing-and-ai-is-the-reason',
		),
		'post'
	);
	$services      = $ids( array( 'seo-ai-discoverability', 'website-design-development', 'pay-per-click' ), 'service' );
	$extra_svc     = $ids( array( 'website-support', 'hosting' ), 'service' );
	$podcast       = $post_id( 'ep6-building-product-marketing', 'post' );
	$summary       = array();

	/* ------------------------------------------------------------------ Home */
	$c  = wlm_note(
		'Home page',
		'The front page of the site. It opens with the big hero, shows who the agency has worked with, what it does, proof in the form of projects and testimonials, and ends by pointing people to the latest insights and the call to action.',
		array(
			'Hero (Home) with image, two buttons and the featured podcast card',
			'Logo bar',
			'Services grid with a testimonial quote and the additional services list',
			'Work roll-up showing four hand-picked projects',
			'Stat and testimonial slider',
			'Text and image (image left, white background) twice, once with two buttons and once with one',
			'Card grid (stats variant)',
			'The shared band "Interested in working with us?"',
			'News roll-up with three chosen insights',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'home-page'
	);
	$c .= $tip( 'Use this layout for the front page only. Most other pages start with Hero (Simple) or Hero (Service) instead.' );
	$c .= wlm_blk(
		'hero-home',
		array(
			'heading'          => 'Construction & Property Digital Marketing Agency',
			'description'      => 'SEO, PPC and websites for construction, property and specialist manufacturers in the built environment.',
			'button_1'         => wlm_lnk( 'See projects', home_url( '/case-studies/' ) ),
			'button_2'         => wlm_lnk( 'Contact', $contact_url ),
			'media_type'       => 'image',
			'image'            => wlm_img( 'hero-poster' ),
			'loop'             => 1,
			'show_card'        => 1,
			'card_post'        => $podcast,
			'card_button_text' => 'Listen now',
		)
	);
	$c .= wlm_blk( 'logo-bar', $logos() );
	$cards = array();
	foreach ( $services as $sid ) {
		$cards[] = array( 'service' => $sid, 'button_text' => 'More details' );
	}
	$c .= wlm_blk(
		'services-grid',
		array(
			'title'            => 'Digital Services',
			'show_quote'       => 1,
			'quote'            => $testimonials ? $testimonials[0] : 0,
			'cards'            => $cards,
			'show_additional'  => 1,
			'additional_label' => 'Additional services',
			'additional'       => array_map( function ( $s ) {
				return array( 'service' => $s );
			}, $extra_svc ),
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'    => 'Selected projects',
			'count'    => '4',
			'manual'   => 1,
			'projects' => $projects_four,
		)
	);
	$c .= wlm_blk(
		'stat-testimonial',
		array(
			'label'        => 'Testimonials',
			'testimonials' => $testimonials,
		)
	);
	$c .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'primary',
			'image_effect' => 1,
			'title'        => 'We\'re a specialist, not a generalist',
			'highlight'    => 'We\'re a specialist',
			'description'  => '<h3>We do the digital. Not the marketing suite.</h3><p>Websites, search and paid campaigns are what we do every day, and we are good at them. We leave the hoardings, the events and the brand suites to others.</p><h3>You don\'t have to explain the industry to us.</h3><p>We have worked with builders, developers and manufacturers for over a decade, so we already know your buyers and how they choose.</p>',
			'buttons'      => array(
				array( 'link' => wlm_lnk( 'Services', $services_url ) ),
				array( 'link' => wlm_lnk( 'Specialism', $spec_url ) ),
			),
			'media_type'   => 'image',
			'image'        => wlm_img( 'photo-specialist' ),
		)
	);
	$c .= wlm_blk( 'card-grid', $stats( 'Award-Winning Digital Agency, Industry Specialists', 'Award-Winning', $stats_text ) );
	$c .= $cta();
	$c .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'primary',
			'image_effect' => 1,
			'title'        => 'User Focused. Results-First. Problem Solving. Data Driven',
			'highlight'    => 'User Focused.',
			'description'  => '<p>As a creative digital agency, we pride ourselves on the close, consultative relationships we build with our clients. We care about every project and want to see our clients grow.</p><p>Before writing a single line of code, we take the time to understand three things: your business, your user and your measures of success. Then we get to work.</p>',
			'buttons'      => array( array( 'link' => wlm_lnk( 'Meet the team', home_url( '/about/' ) ) ) ),
			'media_type'   => 'image',
			'image'        => wlm_img( 'photo-userfocused' ),
		)
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Latest insights',
			'source'  => 'manual',
			'posts'   => $insights,
		)
	);
	$summary[] = 'home-page ' . wlm_page( 'home-page', 'Master: Home page', $c );

	/* ----------------------------------------------------------------- About */
	$c  = wlm_note(
		'About page',
		'Tells visitors who the agency is: a big opening statement, the clients it works with, the numbers, the people and a handful of projects, finishing with a contact form.',
		array(
			'Hero (Simple) on the sand background, with large text switched on',
			'Logo bar',
			'Card grid (stats variant)',
			'The shared band "Interested in working with us?"',
			'Team grid with the intro panel and a Careers button, showing the whole team automatically',
			'Work roll-up with two hand-picked projects',
			'Form (contact variant) with testimonials and the booking band',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'about-page'
	);
	$c .= $tip( 'On the form, choose the testimonials to show beside it. The enquiry goes to the address set in the form settings.' );
	$c .= wlm_blk(
		'hero-simple',
		array(
			'title'       => 'At Crucible, we\'re a Construction Marketing Agency that designs & builds websites, and grows those websites through SEO & PPC.',
			'description' => 'We work with construction, property and specialist manufacturing businesses across the UK. From a website that explains what you do to the search and paid campaigns that bring in the right enquiries, we do the digital so you can get on with the building.',
			'background'  => 'secondary',
			'large_text'  => 1,
		)
	);
	$c .= wlm_blk( 'logo-bar', $logos() );
	$c .= wlm_blk( 'card-grid', $stats( 'Our Agency in Numbers', 'Numbers', $stats_text ) );
	$c .= $cta();
	$c .= wlm_blk(
		'team-grid',
		array(
			'title'         => 'Crucible Team',
			'intro_heading' => 'Working at Crucible',
			'intro_text'    => 'Here at Crucible, we\'re lucky to have some of the best brains in the business, and we are always on the look out for more brilliant people to join us.',
			'intro_button'  => wlm_lnk( 'Careers', home_url( '/careers/' ) ),
			'manual'        => 0,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Selected Projects',
			'count'    => '2',
			'manual'   => 1,
			'projects' => $projects_two,
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'       => 'contact',
			'heading'       => 'I am interested in',
			'heading_dot'   => 0,
			'show_contact'  => 0,
			'testimonials'  => array_slice( $testimonials, 0, 3 ),
			'show_band'     => 1,
			'band_heading'  => 'Book an intro conversation with us',
			'band_text'     => 'Here at Crucible, we get excited about working with clients on dynamic digital projects. We love working on marketing campaigns and website builds which exceed user expectations and deliver tangible results.',
		)
	);
	$summary[] = 'about-page ' . wlm_page( 'about-page', 'Master: About page', $c );

	/* ------------------------------------------------------- Services overview */
	$c  = wlm_note(
		'Services overview page',
		'The page that lists every service. A short opening, then the services as stacked panels, a piece about the agency, and proof and reading to finish.',
		array(
			'Hero (Simple) on the sand background',
			'Service panel, listing every service automatically',
			'Intro text on the sand background with two buttons',
			'Work roll-up with two hand-picked projects',
			'News roll-up with three chosen insights',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'services-overview-page'
	);
	$c .= $tip( 'The Service panel picks up your services on its own. To choose and order them yourself, switch on Choose manually in the block settings.' );
	$c .= wlm_blk(
		'hero-simple',
		array(
			'title'       => 'Digital Services',
			'description' => 'At Crucible, we\'re a construction marketing agency that designs & builds websites, and grows those websites through SEO & PPC. We work with the construction and property industries and have been in business since 2015.',
			'background'  => 'secondary',
		)
	);
	$c .= wlm_blk( 'service-panel', array( 'manual' => 0 ) );
	$c .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'Experts in Digital for Property and Construction since 2015',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'Contact', $contact_url ) ),
				array( 'link' => wlm_lnk( 'Specialism', $spec_url ) ),
			),
			'text'       => '<p>We only work with construction, property and specialist manufacturing businesses, so we already understand who you sell to and how they buy.</p><p>That focus means shorter briefings, fewer surprises and campaigns that speak your customers\' language from day one.</p>',
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Selected Projects',
			'count'    => '2',
			'manual'   => 1,
			'projects' => $projects_two,
		)
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Latest insights',
			'source'  => 'manual',
			'posts'   => $insights,
		)
	);
	$summary[] = 'services-overview-page ' . wlm_page( 'services-overview-page', 'Master: Services overview page', $c );

	/* ----------------------------------------------------- Service detail (full) */
	$faq_items = array(
		array(
			'question' => 'What does your service actually include?',
			'answer'   => '<p>Technical work, on-page optimisation, content strategy and reporting, tailored to your sector. Just the work that moves the needle.</p>',
		),
		array(
			'question' => 'Do you work with sites outside of WordPress?',
			'answer'   => '<p>Yes. We can work with most platforms, although we build and recommend WordPress for the sites we deliver.</p>',
		),
		array(
			'question' => 'How is this different from a generalist marketing agency?',
			'answer'   => '<p>We only work with construction, property and specialist manufacturers, so we already understand your buyers, your sector and your sales cycle.</p>',
		),
		array(
			'question' => 'Can you work alongside our in-house marketing team?',
			'answer'   => '<p>Absolutely. We regularly plug into in-house teams and agree clear responsibilities from the start.</p>',
		),
	);
	$faq_block = function () use ( $faq_items ) {
		return wlm_blk(
			'faq',
			array(
				'variant'     => 'faqs',
				'title'       => 'FAQs',
				'source'      => 'manual',
				'items'       => $faq_items,
				'schema_faqs' => 1,
			)
		);
	};

	$c  = wlm_note(
		'Service detail page',
		'The full page for one service, built to rank well and to answer a buyer\'s questions in order: what it is, how it works, what it costs, what you get each month, proof and common questions.',
		array(
			'Hero (Service) with the breadcrumb, a short introduction and a large image',
			'Intro text on the sand background with two buttons',
			'FAQ in its Questions variant (the image changes with each question)',
			'Pricing with Standard and Custom columns',
			'The shared band "Interested in working with us?"',
			'Tabbed content with a deliverables list in each tab',
			'Work roll-up with two hand-picked projects',
			'FAQ in its FAQs variant with the contact panel on the left and search-engine question markup switched on',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections. For a real service, create it under Services instead and choose the service in the Hero (Service) block.',
		'page',
		'service-detail-page'
	);
	$c .= $tip( 'On a real service, the prices, tabs and questions are the places to change. If the service has no pricing, use the short version of this page instead.' );
	$c .= wlm_blk(
		'hero-service',
		array(
			'eyebrow'         => 'SERVICE',
			'show_breadcrumb' => 1,
			'title'           => 'SEO and AI Discoverability',
			'intro'           => 'With over a decade of experience in digital marketing for construction companies, we provide clear, defined packages. Our SEO is driven by human expertise and content written entirely by people.',
			'image'           => wlm_img( 'service-seo' ),
		)
	);
	$c .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'How Businesses are Found Online is Changing',
			'highlight'  => 'Found Online',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'Book a 30 min call', $contact_url ) ),
				array( 'link' => wlm_lnk( 'Specialism', $spec_url ) ),
			),
			'text'       => '<p>SEO helps your organisation achieve stronger organic search results, which is one part of the discovery process. AI Discoverability is the other part, making sure your brand and products are recognised and cited by AI systems when people ask them questions.</p><p>The structure and content of your pages is only one piece of the puzzle. Through combined SEO and AI-driven services, we aim to increase organic traffic and strengthen your presence across both search engines and AI platforms.</p>',
		)
	);
	$c .= wlm_blk(
		'faq',
		array(
			'variant'          => 'questions',
			'questions'        => array(
				array(
					'question'   => 'What is SEO?',
					'answer'     => '<p>SEO stands for Search Engine Optimisation. In simple terms, it is the process of increasing the quantity and quality of organic traffic to your website from search engines.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
				array(
					'question'   => 'What is AI Discoverability?',
					'answer'     => '<p>AI Discoverability is making sure AI assistants and answer engines can find, understand and cite your brand when people ask them about your products and services.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-shadow' ),
				),
				array(
					'question'   => 'How long will it take to see results?',
					'answer'     => '<p>Most clients see early movement within three months, with stronger results building over six to twelve months. We report on progress every month so you always know where you stand.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
			),
			'schema_questions' => 1,
		)
	);
	$c .= wlm_blk(
		'pricing',
		array(
			'title'   => 'Pricing',
			'intro'   => 'Clear packages with no surprises. Choose the one that fits, or talk to us about something tailored. The figures below are examples only.',
			'columns' => array(
				array(
					'style'       => 'standard',
					'label'       => 'Single website',
					'price'       => '£000',
					'suffix'      => 'per month',
					'description' => '<p>A clear monthly package for one website, delivered by a specialist team.</p>',
					'features'    => "Technical audit\nContent plan\nMonthly reporting",
					'link'        => wlm_lnk( 'Get a quote', $contact_url ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Multiple websites',
					'price'       => '£000',
					'suffix'      => 'per month',
					'description' => '<p>If your business has several brands or websites, we offer a multi-site discount for the same deliverables.</p>',
					'features'    => "Everything in Single website\nShared reporting\nMulti-site discount",
					'link'        => wlm_lnk( 'Get a quote', $contact_url ),
				),
				array(
					'style'       => 'custom',
					'label'       => 'Custom',
					'description' => '<p>Some projects are larger or more complex than others, and some clients want specific deliverables. We can quote on a custom package whenever you need one.</p>',
					'link'        => wlm_lnk( 'Talk to us', $contact_url ),
				),
			),
		)
	);
	$c .= $cta();
	$c .= wlm_blk(
		'tabbed',
		array(
			'title'       => 'Transparent Deliverables',
			'description' => 'Every month has a clear list of what we will do, so you always know what you are paying for.',
			'tabs'        => array(
				array(
					'label'      => 'Month 1',
					'heading'    => 'Month 1',
					'content'    => '<p>In the first month we audit your site, agree the priorities and fix the most urgent technical issues.</p><h3>What happens first?</h3><p>We start with a full technical and content audit, then agree a plan with you.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Technical SEO audit\nKeyword and competitor research\nTracking and reporting set up\nUrgent fixes",
				),
				array(
					'label'      => 'Month 2',
					'heading'    => 'Month 2',
					'content'    => '<p>In month two we begin the planned improvements and start producing new content.</p><h3>How do we prioritise?</h3><p>Every action is linked to a real business outcome for you.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Technical improvements\nContent optimisation\nNew content\nMonthly reporting meeting",
				),
				array(
					'label'      => 'Month 3+',
					'heading'    => 'Month 3 +',
					'content'    => '<p>By month three the urgent issues are fixed and we start reporting early results.</p><h3>How do we keep improving?</h3><p>Working in three month sprints lets us prioritise, and every change we make is tracked so you can see direct results for the work you pay for.</p>',
					'list_label' => 'Deliverables',
					'items'      => "Ongoing technical SEO\nExisting content optimisation\nNew content\nWeekly check in meetings\nFirst reporting meeting and sprint reset",
				),
			),
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Related Projects',
			'count'    => '2',
			'manual'   => 1,
			'projects' => $projects_two,
		)
	);
	$c .= $faq_block();
	$summary[] = 'service-detail-page ' . wlm_page( 'service-detail-page', 'Master: Service detail page', $c );

	/* ----------------------------------------------------- Service detail (short) */
	$c  = wlm_note(
		'Service detail page (short)',
		'A lighter page for a service that needs no pricing or tabs, such as hosting. It explains what is included, answers the main questions and shows related work.',
		array(
			'Hero (Service) with the breadcrumb, a custom title and a large image',
			'Intro text on the sand background with two buttons',
			'FAQ in its Questions variant with four questions',
			'Work roll-up with two hand-picked projects',
			'FAQ in its FAQs variant with the contact panel on the left',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'service-detail-short-page'
	);
	$c .= $tip( 'Choose this version when a service has no price list. Add the Pricing and Tabbed blocks from the full version if you need them later.' );
	$c .= wlm_blk(
		'hero-service',
		array(
			'show_breadcrumb' => 1,
			'title'           => 'WordPress Hosting',
			'intro'           => 'We host the sites we build and support on managed infrastructure, with the same team handling the server and the site. When something breaks there is no argument about whose problem it is.',
			'image'           => wlm_img( 'service-hosting' ),
		)
	);
	$c .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'What the Hosting Includes',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'Book a 30 min call', $contact_url ) ),
				array( 'link' => wlm_lnk( 'Specialism', $spec_url ) ),
			),
			'text'       => '<p>Sites run on dedicated resources rather than a shared box with hundreds of other tenants, so a neighbouring site\'s bad code can\'t slow yours down. Server-level caching and a CDN sit in front of everything, and SSL is issued and renewed automatically.</p><p>Every site gets a staging environment as standard. Updates and development work are tested there first, then pushed live once they are checked.</p>',
		)
	);
	$c .= wlm_blk(
		'faq',
		array(
			'variant'          => 'questions',
			'questions'        => array(
				array(
					'question'   => 'Security',
					'answer'     => '<p>A web application firewall filters malicious traffic before it reaches your site, with rate limiting on login and form endpoints to stop spam and brute force attempts.</p><p>WordPress core, plugins and PHP are patched on a schedule rather than left to drift, and we monitor for malware and unexpected file changes.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
				array(
					'question'   => 'Backups and Recovery',
					'answer'     => '<p>Your site is backed up automatically every day and the copies are stored away from the server. If something goes wrong we can restore a clean version quickly, and we test restores regularly.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-shadow' ),
				),
				array(
					'question'   => 'Uptime and Performance Monitoring',
					'answer'     => '<p>We monitor your site around the clock. If it goes down or slows noticeably, we are alerted and start investigating before most visitors notice.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
				array(
					'question'   => 'Support that Includes the Server',
					'answer'     => '<p>Because the same team looks after the site and the server, you only have one place to go when you need help.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-shadow' ),
				),
			),
			'schema_questions' => 0,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Related Projects',
			'count'    => '2',
			'manual'   => 1,
			'projects' => $projects_two,
		)
	);
	$c .= $faq_block();
	$summary[] = 'service-detail-short-page ' . wlm_page( 'service-detail-short-page', 'Master: Service detail page (short)', $c );

	/* ------------------------------------------------------------- Specialism */
	$c  = wlm_note(
		'Specialism page',
		'Explains which industries the agency serves and why it is the right partner for them.',
		array(
			'Hero (Service) with an eyebrow, no breadcrumb and a large image',
			'Logo bar',
			'Services grid used for sectors: three cards with their own title, text, link and image, a testimonial quote and no additional services',
			'Card grid (stats variant)',
			'The shared band "Interested in working with us?"',
			'Text and image (image left, white background) with one button',
			'Stat and testimonial slider',
			'Work roll-up with two hand-picked projects',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'specialism-page'
	);
	$c .= $tip( 'In the Services grid, leave the service empty on a card and fill in its own title, text, link and image to use it for something other than a service, like a sector.' );
	$c .= wlm_blk(
		'hero-service',
		array(
			'eyebrow'         => 'SPECIALISM',
			'show_breadcrumb' => 0,
			'title'           => 'Experts in Digital for Property and Construction since 2015',
			'intro'           => 'We only work with the built environment, so we already know your customers, your sector and the way your projects are won.',
			'image'           => wlm_img( 'spec-hero' ),
		)
	);
	$c .= wlm_blk( 'logo-bar', $logos() );
	$c .= wlm_blk(
		'services-grid',
		array(
			'title'           => 'Who we Work Best With',
			'show_quote'      => 1,
			'quote'           => $testimonials ? $testimonials[1] : 0,
			'cards'           => array(
				array(
					'title'       => 'Construction',
					'button_text' => 'Case studies',
					'description' => 'Manufacturers, product suppliers, main and subcontractors, architects and consultants.',
					'link'        => wlm_lnk( 'Case studies', home_url( '/case-studies/sector/construction/' ) ),
					'image'       => wlm_img( 'spec-construction' ),
				),
				array(
					'title'       => 'Property',
					'button_text' => 'Case studies',
					'description' => 'Agents, developers, planning consultants and project managers.',
					'link'        => wlm_lnk( 'Case studies', home_url( '/case-studies/sector/property/' ) ),
					'image'       => wlm_img( 'spec-property' ),
				),
				array(
					'title'       => 'Specialist Manufacturers',
					'button_text' => 'Case studies',
					'description' => 'Companies supplying technical products into construction, property and infrastructure projects.',
					'link'        => wlm_lnk( 'Case studies', home_url( '/case-studies/sector/specialist-manufacturers/' ) ),
					'image'       => wlm_img( 'spec-manufacturers' ),
				),
			),
			'show_additional' => 0,
		)
	);
	$c .= wlm_blk( 'card-grid', $stats( 'Award-Winning Digital Agency, Industry Specialists', 'Award-Winning', $stats_text ) );
	$c .= $cta();
	$c .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'primary',
			'image_effect' => 1,
			'title'        => 'Win more customers',
			'description'  => '<p>We design and build websites, run SEO campaigns and manage paid budgets. We have a decade of industry experience and are here to help.</p>',
			'buttons'      => array( array( 'link' => wlm_lnk( 'Contact', $contact_url ) ) ),
			'media_type'   => 'image',
			'image'        => wlm_img( 'spec-win' ),
		)
	);
	$c .= wlm_blk(
		'stat-testimonial',
		array(
			'label'        => 'Testimonials',
			'testimonials' => $testimonials,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Selected Projects',
			'count'    => '2',
			'manual'   => 1,
			'projects' => $projects_two,
		)
	);
	$summary[] = 'specialism-page ' . wlm_page( 'specialism-page', 'Master: Specialism page', $c );

	/* -------------------------------------------------------------- Thank you */
	$c  = wlm_note(
		'Thank you page',
		'The page people land on after they send a form. One calm message and a way back to the site.',
		array(
			'Message frame with the label, an H1 heading with its first words highlighted, and one button',
		),
		'Pages, then Add New. Use the Add block button and look under Page Sections.',
		'page',
		'thank-you-page'
	);
	$c .= $tip( 'Set a form to send people to a page like this once it is submitted. Keep the page short and friendly.' );
	$c .= wlm_blk(
		'message-frame',
		array(
			'label'       => 'Message received',
			'heading_tag' => 'h1',
			'heading'     => 'Thank you. We will be in touch soon',
			'highlight'   => 'Thank you.',
			'button'      => wlm_lnk( 'Home', home_url( '/' ) ),
		)
	);
	$summary[] = 'thank-you-page ' . wlm_page( 'thank-you-page', 'Master: Thank you page', $c );

	return implode( '; ', $summary );
};
