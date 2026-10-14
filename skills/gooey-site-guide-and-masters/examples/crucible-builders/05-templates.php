<?php
/**
 * Master entries: one fully filled example of each content type (article, event, podcast, case study, service,
 * career, proposal). Re-runnable: every entry is found by slug and updated.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$by_slug = function ( $slug, $type ) {
		$post = get_page_by_path( $slug, OBJECT, $type );

		return $post ? (int) $post->ID : 0;
	};

	$h = function ( $text, $level = 2 ) {
		return '<!-- wp:heading ' . ( 2 === $level ? '' : '{"level":' . $level . '} ' ) . '--><h' . $level . ' class="wp-block-heading">' . esc_html( $text ) . '</h' . $level . ">\n<!-- /wp:heading -->\n\n";
	};

	$p = function ( $text ) {
		return "<!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph -->\n\n";
	};

	$ul = function ( $items ) {
		$out = "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
		foreach ( $items as $item ) {
			$out .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
		}

		return $out . "</ul>\n<!-- /wp:list -->\n\n";
	};

	$quote = function ( $text, $cite ) {
		return "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph --><cite>" . $cite . "</cite></blockquote>\n<!-- /wp:quote -->\n\n";
	};

	$img = function ( $key, $alt, $caption = '' ) {
		$id  = wlm_img( $key );
		$url = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';

		return '<!-- wp:image {"id":' . $id . ',"sizeSlug":"large","linkDestination":"none"} -->' . "\n"
			. '<figure class="wp-block-image size-large"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . $id . '"/>'
			. ( $caption ? '<figcaption class="wp-element-caption">' . esc_html( $caption ) . '</figcaption>' : '' )
			. "</figure>\n<!-- /wp:image -->\n\n";
	};

	$host_carrie = $by_slug( 'carrie-adam', 'team_member' );
	$host_george = $by_slug( 'george-hosegood', 'team_member' );
	$host_kerrie = $by_slug( 'kerrie-bingle', 'team_member' );
	$summary     = array();

	// 1. Article.
	$body  = $p( 'This is a master article. It shows every part of an insight article, so you can copy it and replace the words. The list on the right is built from the headings below.' );
	$body .= $h( 'Why this matters for your business' );
	$body .= $p( 'Buyers in construction, property and manufacturing do most of their research online before they ever pick up the phone. A clear, well-structured page answers their questions early and builds trust before the first conversation.' );
	$body .= $ul(
		array(
			'Start with the question your buyer is actually asking.',
			'Keep paragraphs short, and give each section its own heading.',
			'End with one clear next step.',
		)
	);
	$body .= wlm_blk(
		'stat',
		array(
			'stats' => array(
				array(
					'value'   => '3x',
					'caption' => 'more enquiries from pages that answer one question well',
					'source'  => wlm_lnk( 'Source', home_url( '/insights/' ) ),
				),
				array(
					'value'   => '60%',
					'caption' => 'of research is done before a supplier is contacted',
					'source'  => array(),
				),
				array(
					'value'   => '120+',
					'caption' => 'words is a good length for a clear summary',
					'source'  => array(),
				),
			),
		)
	);
	$body .= $h( 'Make the page easy to scan' );
	$body .= $h( 'Use short sections', 3 );
	$body .= $p( 'Each section should do one job. Lead with the point, then add the detail, so a busy reader gets value from the first line.' );
	$body .= $quote( 'A good page does the selling before the salesperson picks up the phone.', 'The Crucible team' );
	$body .= $img( 'ins-workshop', 'A manufacturing workshop with a worker at a bench.', 'An inline image, with a caption underneath.' );
	$body .= $h( 'Show it in action', 3 );
	$body .= wlm_blk(
		'video',
		array(
			'url'     => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
			'poster'  => wlm_img( 'ins-laptop' ),
			'caption' => 'A video block with a cover image and a caption. Nothing loads until you press play.',
		)
	);
	$body .= wlm_blk(
		'gallery',
		array(
			'layout'        => 'pair',
			'images'        => array( wlm_img( 'ins-tiles' ), wlm_img( 'ins-pipes' ) ),
			'show_captions' => 1,
		)
	);
	$body .= wlm_blk(
		'banner',
		array(
			'variant' => 'image',
			'image'   => wlm_img( 'ins-phone' ),
			'text'    => '<p>Want help turning pages like this into enquiries? Read how our SEO work gets specifiers to find you.</p>',
			'button'  => wlm_lnk( 'See our SEO service', home_url( '/services/' ) ),
		)
	);
	$body .= wlm_blk(
		'banner',
		array(
			'variant' => 'coral',
			'text'    => '<p>Not sure where to start? <a href="' . esc_url( home_url( '/contact/' ) ) . '">Talk to the team</a> or <a href="' . esc_url( home_url( '/case-studies/' ) ) . '">read our case studies</a>.</p>',
		)
	);
	$body .= wlm_blk(
		'faq',
		array(
			'variant'     => 'faqs',
			'title'       => 'Questions about articles',
			'intro'       => 'Short answers to the questions we hear most. Get in touch if yours is not here.',
			'source'      => 'manual',
			'items'       => array(
				array(
					'question' => 'How long should an article be?',
					'answer'   => '<p>As long as it needs to be to answer the question well. Most useful articles are between 600 and 1,200 words.</p>',
				),
				array(
					'question' => 'How often should we publish?',
					'answer'   => '<p>Once or twice a month is plenty, as long as each piece is genuinely helpful.</p>',
				),
				array(
					'question' => 'Can we add video and images?',
					'answer'   => '<p>Yes. Add the Video, Gallery and Image blocks anywhere in the article, as shown above.</p>',
				),
			),
			'schema_faqs' => 0,
		)
	);
	$body .= $h( 'What to do next' );
	$body .= $p( 'Pick one page on your site, read it as a buyer would, and rewrite the first paragraph so it answers their question straight away.' );
	$body .= $img( 'ins-meeting', 'Colleagues meeting around a table to plan.', 'A second inline image at the end of the article.' );

	$id       = wlm_entry(
		'post',
		'master-insight-article',
		'Master: Insight article',
		array(
			'post_content' => $body,
			'post_excerpt' => 'A master article that shows every part of an insight page, from the hero and byline to the blocks you can add inside the text.',
			'fields'       => array(
				'title_highlight' => 'Insight article',
				'host'            => $host_carrie,
			),
			'terms'        => array(
				'format'   => array( 'article' ),
				'category' => array( 'marketing', 'design' ),
			),
			'thumbnail'    => wlm_img( 'ins-dashboard' ),
		)
	);
	$summary[] = 'article ' . $id;

	// 2. Event.
	$body  = $p( 'Join us for a relaxed morning with others from construction, property and manufacturing. This is a master event, so every detail can be replaced with your own.' );
	$body .= $h( 'What to expect' );
	$body .= $ul(
		array(
			'Breakfast and time to meet other local businesses.',
			'A short talk on getting found online.',
			'Plenty of time for questions.',
		)
	);
	$body .= $h( 'Who it is for' );
	$body .= $p( 'Marketing and sales leaders who want a clearer view of how buyers find and choose suppliers.' );

	$id        = wlm_entry(
		'post',
		'master-insight-event',
		'Master: Insight event',
		array(
			'post_content' => $body,
			'post_excerpt' => 'A master event that shows the date, place and booking button, with a hero that uses the event date instead of the publish date.',
			'fields'       => array(
				'title_highlight' => 'Insight event',
				'host'            => $host_george,
				'event_date'      => '20271118',
				'event_location'  => 'The Conference Room, Example Street, Chelmsford',
				'event_url'       => home_url( '/contact/' ),
			),
			'terms'        => array(
				'format'   => array( 'event' ),
				'category' => array( 'marketing' ),
			),
			'thumbnail'    => wlm_img( 'ins-event' ),
		)
	);
	$summary[] = 'event ' . $id;

	// 3. Podcast.
	$body  = $p( 'In this master episode we talk about how building product makers can win trust with specifiers. Replace this text with the show notes for your own episode.' );
	$body .= $h( 'In this episode' );
	$body .= $ul(
		array(
			'Why specifiers research long before they call.',
			'What a helpful product page looks like.',
			'One change you can make this week.',
		)
	);
	$body .= $h( 'Listen and subscribe' );
	$body .= $p( 'Press the Listen button above to open the episode, and subscribe wherever you get your podcasts.' );

	$id        = wlm_entry(
		'post',
		'master-insight-podcast',
		'Master: Insight podcast',
		array(
			'post_content' => $body,
			'post_excerpt' => 'A master podcast episode that shows the host, the episode number, the sponsor logo and the Listen button.',
			'fields'       => array(
				'title_highlight' => 'Insight podcast',
				'host'            => $host_kerrie,
				'episode'         => '7',
				'listen_url'      => home_url( '/insights/' ),
				'sponsor_logo'    => wlm_img( 'logo-barbourabi' ),
			),
			'terms'        => array(
				'format'   => array( 'podcast' ),
				'category' => array( 'marketing' ),
			),
			'thumbnail'    => wlm_img( 'ins-podcast' ),
		)
	);
	$summary[] = 'podcast ' . $id;

	// 4. Case study.
	$related = array_values(
		array_filter(
			array(
				$by_slug( '87-increase-in-conversions-seo-for-westbury-garden-rooms', 'case_study' ),
				$by_slug( 'website-design-build-for-building-product-design-installation-company', 'case_study' ),
				$by_slug( 'website-support-and-management-for-leading-property-group', 'case_study' ),
			)
		)
	);

	$body  = $h( 'The challenge' );
	$body .= $p( 'The client was getting steady traffic but too few enquiries. Their site was hard to use on a phone and did not answer the questions buyers were asking.' );
	$body .= $h( 'What we did' );
	$body .= $ul(
		array(
			'Rebuilt the key pages around real buyer questions.',
			'Made the site quicker and easier to use on mobile.',
			'Set up clear tracking so every enquiry could be measured.',
		)
	);
	$body .= wlm_blk(
		'stat',
		array(
			'stats' => array(
				array(
					'value'   => '+120%',
					'caption' => 'more enquiries from search in six months',
					'source'  => array(),
				),
				array(
					'value'   => '2x',
					'caption' => 'faster page loads on mobile',
					'source'  => array(),
				),
			),
		)
	);
	$body .= $h( 'The result' );
	$body .= $p( 'Enquiries grew steadily, and the sales team now spends less time explaining the basics. This is sample wording for a master case study, so replace the figures with the client\'s own.' );
	$body .= $img( 'cs-aliwood', 'A finished project photograph shown as a full-width closing band.', '' );

	$id        = wlm_entry(
		'case_study',
		'master-case-study',
		'Master: Case study',
		array(
			'post_content' => $body,
			'fields'       => array(
				'client_name'     => 'Example Client Ltd',
				'title_highlight' => 'Case study',
				'intro'           => 'A master case study that shows the hero with its stat and second photograph, the article, the client quote beside it, a closing image band and related projects.',
				'live_url'        => home_url( '/' ),
				'stat_value'      => '+120%',
				'stat_caption'    => 'more enquiries from search',
				'secondary_image' => wlm_img( 'cs-kenwood' ),
				'testimonial'     => $by_slug( 'sam-whybrow-whybrow', 'testimonial' ),
				'related_projects' => $related,
			),
			'terms'        => array(
				'service_area' => array( 'seo-ai-discoverability', 'website-design-development' ),
				'sector'       => array( 'construction' ),
			),
			'thumbnail'    => wlm_img( 'cs-pjbones' ),
		)
	);
	$summary[] = 'case study ' . $id;

	// 5. Service.
	$body  = wlm_blk(
		'hero-service',
		array(
			'show_breadcrumb' => 1,
			'title'           => 'A master service page',
			'intro'           => 'This master service page is built from blocks, so you can copy it for a new service and change the words and photographs.',
			'image'           => wlm_img( 'spec-win' ),
			'case_study'      => $by_slug( '87-increase-in-conversions-seo-for-westbury-garden-rooms', 'case_study' ),
		)
	);
	$body .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'Marketing that gets you found',
			'highlight'  => 'gets you found',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'Talk to us', home_url( '/contact/' ) ) ),
				array( 'link' => wlm_lnk( 'See our work', home_url( '/case-studies/' ) ) ),
			),
			'text'       => '<p>Start with a short, friendly introduction to the service. Say who it is for, what problem it solves and what the client gets.</p><p>Two buttons sit under the headline: the first is solid and the second is an outline.</p>',
		)
	);
	$body .= wlm_blk(
		'tabbed',
		array(
			'title'       => 'How it works',
			'description' => 'A tabbed block is a good way to show a process over time.',
			'tabs'        => array(
				array(
					'label'      => 'Month 1',
					'heading'    => 'Month 1',
					'content'    => '<h3>Getting set up</h3><p>We learn about your business, your buyers and your goals, then agree a plan.</p>',
					'list_label' => 'What we do',
					'items'      => "Audit the current site\nAgree goals and measures\nSet up tracking",
				),
				array(
					'label'      => 'Month 2',
					'heading'    => 'Month 2',
					'content'    => '<h3>Making the changes</h3><p>We start on the work that will make the biggest difference first.</p>',
					'list_label' => 'What we do',
					'items'      => "Improve key pages\nFix technical issues\nPublish helpful content",
				),
				array(
					'label'      => 'Month 3 +',
					'heading'    => 'Month 3 +',
					'content'    => '<h3>Growing steadily</h3><p>We review the results each month and keep improving what works.</p>',
					'list_label' => 'What we do',
					'items'      => "Report in plain English\nTest new ideas\nPlan the next quarter",
				),
			),
		)
	);
	$body .= wlm_blk(
		'pricing',
		array(
			'title'   => 'Ways to work with us',
			'intro'   => 'The prices below are examples only. Replace them with your own.',
			'columns' => array(
				array(
					'style'       => 'standard',
					'label'       => 'Starter',
					'price'       => '£0,000',
					'suffix'      => '/month',
					'description' => '<p>A good place to start for smaller sites.</p>',
					'features'    => "Monthly report\nSupport by email\nQuarterly review",
					'link'        => wlm_lnk( 'Get started', home_url( '/contact/' ) ),
				),
				array(
					'style'       => 'standard',
					'label'       => 'Growth',
					'price'       => '£0,000',
					'suffix'      => '/month',
					'description' => '<p>More time and more support for growing teams.</p>',
					'features'    => "Everything in Starter\nFortnightly calls\nContent planning",
					'link'        => wlm_lnk( 'Get started', home_url( '/contact/' ) ),
				),
				array(
					'style'       => 'custom',
					'label'       => 'Tailored to you',
					'price'       => '',
					'suffix'      => '',
					'description' => '<p>Tell us what you need and we will put a plan together.</p>',
					'features'    => '',
					'link'        => wlm_lnk( 'Talk to us', home_url( '/contact/' ) ),
				),
			),
		)
	);
	$body .= wlm_blk(
		'faq',
		array(
			'variant'          => 'questions',
			'title'            => 'Questions about this service',
			'intro'            => 'Short answers to the questions we hear most.',
			'questions'        => array(
				array(
					'question'   => 'How quickly will we see results?',
					'answer'     => '<p>Most clients see early movement within three months, with steady growth after that.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-laptop' ),
				),
				array(
					'question'   => 'Do we need a long contract?',
					'answer'     => '<p>No. We work month to month, so you stay because it works.</p>',
					'media_type' => 'image',
					'image'      => wlm_img( 'faq-shadow' ),
				),
			),
			'schema_questions' => 0,
		)
	);

	$id        = wlm_entry(
		'service',
		'master-service',
		'Master: Service',
		array(
			'post_content' => $body,
			'fields'       => array(
				'tier'              => 'additional',
				'card_text'         => 'A master service that shows the card summary, the overview button and a page built from blocks.',
				'panel_button_text' => 'Explore this service',
			),
			'thumbnail'    => wlm_img( 'service-web' ),
		)
	);
	$summary[] = 'service ' . $id;

	// 6. Career.
	$body  = $h( 'About the role' );
	$body .= $p( 'This is a master role. It shows how a vacancy looks on the Careers page and on its own page. Replace this text with the real description.' );
	$body .= $h( 'What you will do' );
	$body .= $ul(
		array(
			'Work with clients to understand what they need.',
			'Plan and deliver your own projects from start to finish.',
			'Share what you learn with the rest of the team.',
		)
	);
	$body .= $h( 'About you' );
	$body .= $ul(
		array(
			'You enjoy solving problems and explaining things clearly.',
			'You have some experience in a similar role.',
			'You like working in a friendly, close team.',
		)
	);
	$body .= $h( 'What we offer' );
	$body .= $p( 'A supportive team, flexible working and time to learn. Add your own benefits here.' );

	$id        = wlm_entry(
		'career',
		'master-career',
		'Master: Career',
		array(
			'post_content' => $body,
			'fields'       => array(
				'location' => 'Chelmsford, with flexible working',
				'summary'  => 'A master vacancy that shows the location, the short summary and a full description with headings and lists.',
			),
		)
	);
	$summary[] = 'career ' . $id;

	// 7. Proposal.
	$body  = $p( 'Thank you for the chance to work with you. This master proposal shows how a proposal page looks to a client. Replace each section with your own.' );
	$body .= $h( 'Where you are now' );
	$body .= $p( 'Describe the client\'s current situation and what they want to change.' );
	$body .= $h( 'What we will do' );
	$body .= $ul(
		array(
			'Review the current website and search results.',
			'Agree goals and a plan for the first three months.',
			'Deliver the work and report each month.',
		)
	);
	$body .= $h( 'Next steps' );
	$body .= $p( 'Reply to confirm, and we will book a kick-off call.' );

	$id        = wlm_entry(
		'proposal',
		'master-proposal',
		'Master: Proposal',
		array(
			'post_content' => $body,
			'fields'       => array(
				'client_name' => 'Example Client Ltd',
				'valid_until' => '20271231',
			),
		)
	);
	$summary[] = 'proposal ' . $id;

	return implode( ', ', $summary );
};
