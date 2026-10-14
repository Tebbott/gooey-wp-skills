<?php
/**
 * Master page: Content blocks (heroes, text, media, stats, banners and closing blocks).
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$post_id = function ( $slug, $type ) {
		$p = get_page_by_path( $slug, OBJECT, $type );
		return $p ? (int) $p->ID : 0;
	};

	// Services, case studies and insights that exist as sample content.
	$svc_seo = $post_id( 'seo-ai-discoverability', 'service' );
	$svc_web = $post_id( 'website-design-development', 'service' );
	$cs_wci  = $post_id( 'a-new-website-and-seo-migration-for-wci-group', 'case_study' );
	$podcast = $post_id( 'ep6-building-product-marketing', 'post' );
	$event   = $post_id( 'event-monthly-industry-breakfast-at-langhams', 'post' );
	$article = $post_id( 'how-to-create-a-digital-marketing-strategy', 'post' );

	$services = home_url( '/services/' );
	$contact  = home_url( '/contact/' );
	$cases    = home_url( '/case-studies/' );
	$insights = home_url( '/insights/' );
	$about    = home_url( '/about/' );

	// Captions for the pair gallery (captions come from the photo's own caption in the media library).
	$captions = array(
		'insight-2' => 'Planning a content calendar around the buying cycle.',
		'insight-3' => 'Reviewing search results with a client at the studio.',
	);
	foreach ( $captions as $key => $text ) {
		$aid = wlm_img( $key );
		if ( $aid ) {
			wp_update_post(
				array(
					'ID'           => $aid,
					'post_excerpt' => $text,
				)
			);
		}
	}

	$content = '';

	// ---------------------------------------------------------------- Page note.
	$content .= wlm_note(
		'Content blocks',
		'This page shows the everyday building blocks: the page openers (heroes), text, pictures, video, numbers, banners and the closing blocks. Each block has a label like this one, then one or more examples. Because every hero provides a page title, this page has several of them, which a real page should not: use one hero per page. To copy a block, open this page in the editor, select the block, copy it and paste it into your own page.',
		array(
			'Hero (Simple): first example below, then its variants',
			'Hero (Home), Hero (Service), Hero (Dark)',
			'Intro text, Text and image',
			'Image, Gallery, Video',
			'Stats, Banner, Call to action band, Message frame',
		),
		'Add block, then choose the block name from Page Sections.',
		'page',
		'top'
	);

	// ---------------------------------------------------------------- Hero (Simple).
	$content .= wlm_note(
		'Hero (Simple)',
		'A plain page opener with a title and a short introduction. It is the page title (h1), so use one per page. The first example below is the plain version.',
		array(
			'Example 1 (the plain hero at the top of this page): sand background, title and introduction.',
			'Example 2: white (primary) background.',
			'Example 3: large text option, for short, bold openers.',
			'Example 4: "Last updated" line switched on, as used on policy pages.',
			'Example 5: custom small line under the title, instead of the automatic last-updated line.',
		),
		'Add block, then Page Sections, then Hero (Simple).',
		'block',
		'hero-simple'
	);
	$content .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'Marketing that builds your pipeline',
			'description'       => 'Search, paid media and websites for construction, property and manufacturing businesses, planned around the way your customers actually buy.',
			'background'        => 'secondary',
			'large_text'        => 0,
			'show_last_updated' => 0,
			'meta'              => '',
		)
	);
	$content .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'Clear plans, honest reporting',
			'description'       => 'White background version. Use it when the page below starts with a sand section.',
			'background'        => 'primary',
			'large_text'        => 0,
			'show_last_updated' => 0,
			'meta'              => '',
		)
	);
	$content .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'Let us help you win more work',
			'description'       => 'Large text version, with a short introduction.',
			'background'        => 'secondary',
			'large_text'        => 1,
			'show_last_updated' => 0,
			'meta'              => '',
		)
	);
	$content .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'How we look after your information',
			'description'       => 'The last-updated line is filled in for you from the date the page was last saved.',
			'background'        => 'primary',
			'large_text'        => 0,
			'show_last_updated' => 1,
			'meta'              => '',
		)
	);
	$content .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'Insights for the built environment',
			'description'       => 'A custom line sits under the title when you type one in the Meta field.',
			'background'        => 'secondary',
			'large_text'        => 0,
			'show_last_updated' => 0,
			'meta'              => 'Reading time: about five minutes',
		)
	);

	// ---------------------------------------------------------------- Hero (Home).
	$content .= wlm_note(
		'Hero (Home)',
		'The big opener for the home page: a full-width picture or looping video, a heading, an introduction, one or two buttons and an optional coral card that promotes a post such as the latest podcast.',
		array(
			'Example 1: image, two buttons, coral card promoting a podcast episode (the sponsor logo comes from the episode).',
			'Example 2: image, one button, card promoting an event with its own logo and a custom button text.',
			'Example 3: image, one button, card switched off.',
			'Video option: set "Hero media" to Video, upload an MP4 and a poster image, and choose whether it loops. Not shown here because it needs an uploaded video file. With no file the poster image is shown instead.',
		),
		'Add block, then Page Sections, then Hero (Home).',
		'block',
		'hero-home'
	);
	$content .= wlm_blk(
		'hero-home',
		array(
			'heading'          => 'Digital marketing for the built environment',
			'description'      => 'We help construction, property and manufacturing businesses get found, get specified and get enquiries that turn into work.',
			'button_1'         => wlm_lnk( 'Talk to us', $contact ),
			'button_2'         => wlm_lnk( 'See our services', $services ),
			'media_type'       => 'image',
			'image'            => wlm_img( 'hero-poster' ),
			'show_card'        => 1,
			'card_post'        => $podcast,
			'card_button_text' => 'Listen now',
		)
	);
	$content .= wlm_blk(
		'hero-home',
		array(
			'heading'          => 'Meet the people behind the plan',
			'description'      => 'A small team of specialists who work as an extension of yours. Join us at our next industry breakfast.',
			'button_1'         => wlm_lnk( 'About the team', $about ),
			'media_type'       => 'image',
			'image'            => wlm_img( 'spec-hero' ),
			'show_card'        => 1,
			'card_post'        => $event,
			'card_logo'        => wlm_img( 'logo-wci' ),
			'card_button_text' => 'Book a place',
		)
	);
	$content .= wlm_blk(
		'hero-home',
		array(
			'heading'     => 'Websites that work as hard as you do',
			'description' => 'The same opener without a card, for when there is nothing to promote.',
			'button_1'    => wlm_lnk( 'Our work', $cases ),
			'media_type'  => 'image',
			'image'       => wlm_img( 'spec-win' ),
			'show_card'   => 0,
		)
	);

	// ---------------------------------------------------------------- Hero (Service).
	$content .= wlm_note(
		'Hero (Service)',
		'The opener for a service or specialism page: a text panel beside a large picture, with a link to a related case study.',
		array(
			'Example 1: linked to a service, so the title, introduction, picture and latest case study fill in by themselves. Breadcrumb shown.',
			'Example 2: eyebrow label (for example Specialism) instead of the breadcrumb, with your own introduction and picture and a hand-picked case study.',
			'Example 3: no linked service, a custom title, breadcrumb switched off and no case study.',
		),
		'Add block, then Page Sections, then Hero (Service).',
		'block',
		'hero-service'
	);
	$content .= wlm_blk(
		'hero-service',
		array(
			'service'         => $svc_seo,
			'show_breadcrumb' => 1,
		)
	);
	$content .= wlm_blk(
		'hero-service',
		array(
			'service'         => $svc_web,
			'eyebrow'         => 'Specialism',
			'show_breadcrumb' => 0,
			'title'           => 'Websites for manufacturers',
			'intro'           => 'Fast, easy-to-manage websites that show your products clearly and help specifiers find what they need.',
			'image'           => wlm_img( 'photo-specialist' ),
			'case_study'      => $cs_wci,
		)
	);
	$content .= wlm_blk(
		'hero-service',
		array(
			'service'         => 0,
			'show_breadcrumb' => 0,
			'title'           => 'A custom heading without a service',
			'intro'           => 'Use this when the page is not tied to one of your services.',
			'image'           => wlm_img( 'photo-userfocused' ),
		)
	);

	// ---------------------------------------------------------------- Hero (Dark).
	$content .= wlm_note(
		'Hero (Dark)',
		'A bold dark opener for "get in touch" style pages: two lines of heading and a short line of text.',
		array(
			'Example 1: heading on two lines with a short text.',
			'Example 2: heading only, text left empty.',
		),
		'Add block, then Page Sections, then Hero (Dark).',
		'block',
		'hero-dark'
	);
	$content .= wlm_blk(
		'hero-dark',
		array(
			'heading' => "Talk to us and\nstart your project",
			'text'    => 'Tell us what you are working on and we will come back within one working day.',
		)
	);
	$content .= wlm_blk(
		'hero-dark',
		array(
			'heading' => "Ready when\nyou are",
			'text'    => '',
		)
	);

	// ---------------------------------------------------------------- Intro text.
	$content .= wlm_note(
		'Intro text',
		'A section title with a paragraph of text and up to two buttons. Use it to open a section or introduce a list of cards.',
		array(
			'Example 1: sand background, a highlighted phrase in coral, two buttons.',
			'Example 2: white background, one button.',
			'Example 3: sand background, no buttons.',
			'Example 4: white background, title only (no text and no buttons).',
		),
		'Add block, then Page Sections, then Intro text.',
		'block',
		'intro-text'
	);
	$content .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'Marketing built around how your customers buy',
			'highlight'  => 'how your customers buy',
			'text'       => '<p>Long sales cycles, specifiers, trade buyers and tender lists all need different handling. We plan your search, paid media and website around each of them, then report on what actually brings in enquiries.</p>',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'Explore services', $services ) ),
				array( 'link' => wlm_lnk( 'Talk to us', $contact ) ),
			),
		)
	);
	$content .= wlm_blk(
		'intro-text',
		array(
			'background' => 'primary',
			'title'      => 'Recent work for ambitious businesses',
			'highlight'  => '',
			'text'       => '<p>A selection of projects across construction, property and manufacturing.</p>',
			'buttons'    => array(
				array( 'link' => wlm_lnk( 'View case studies', $cases ) ),
			),
		)
	);
	$content .= wlm_blk(
		'intro-text',
		array(
			'background' => 'secondary',
			'title'      => 'Straight answers to common questions',
			'highlight'  => 'common questions',
			'text'       => '<p>If you are weighing up agencies, these are the things most people ask us first.</p>',
		)
	);
	$content .= wlm_blk(
		'intro-text',
		array(
			'background' => 'primary',
			'title'      => 'Latest from our team',
			'highlight'  => '',
		)
	);

	// ---------------------------------------------------------------- Text and image.
	$content .= wlm_note(
		'Text and image',
		'A text panel beside a large picture. Good for explaining one idea or service at a time. Stack several to build a story down the page.',
		array(
			'Example 1: image left, white background, Crucible image effect on, one button.',
			'Example 2: image right, sand background, effect on, two buttons and a highlighted phrase.',
			'Example 3: image left, sand background, effect off (the photo keeps its natural colours), no buttons.',
			'Example 4: image right, white background, effect off, one button.',
			'Video option: set "Media" to "Image + video" and upload an MP4 to play over the image with a pause button. Not shown here because it needs an uploaded video file.',
		),
		'Add block, then Page Sections, then Text and image.',
		'block',
		'text-image'
	);
	$content .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'primary',
			'image_effect' => 1,
			'title'        => 'Be found by the people who specify',
			'highlight'    => '',
			'description'  => '<p>Architects, contractors and buyers start their search online. We make sure your products and expertise are what they find, in search results and in AI answers.</p>',
			'buttons'      => array(
				array( 'link' => wlm_lnk( 'SEO and AI discoverability', $services ) ),
			),
			'media_type'   => 'image',
			'image'        => wlm_img( 'service-seo' ),
		)
	);
	$content .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'right',
			'background'   => 'secondary',
			'image_effect' => 1,
			'title'        => 'A website that earns its keep',
			'highlight'    => 'earns its keep',
			'description'  => '<p>Clear structure, fast pages and an editor your team can use without calling us. Every page is built to turn visits into conversations.</p>',
			'buttons'      => array(
				array( 'link' => wlm_lnk( 'Website design', $services ) ),
				array( 'link' => wlm_lnk( 'See the work', $cases ) ),
			),
			'media_type'   => 'image',
			'image'        => wlm_img( 'service-web' ),
		)
	);
	$content .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'secondary',
			'image_effect' => 0,
			'title'        => 'Paid media you can measure',
			'highlight'    => '',
			'description'  => '<p>We set up and manage search and social advertising with clear targets, so you always know what each pound has produced.</p>',
			'media_type'   => 'image',
			'image'        => wlm_img( 'service-ppc' ),
		)
	);
	$content .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'right',
			'background'   => 'primary',
			'image_effect' => 0,
			'title'        => 'Hosting that stays out of the way',
			'highlight'    => '',
			'description'  => '<p>Secure, monitored hosting with regular backups and a team to call if anything goes wrong.</p>',
			'buttons'      => array(
				array( 'link' => wlm_lnk( 'Hosting', $services ) ),
			),
			'media_type'   => 'image',
			'image'        => wlm_img( 'service-hosting' ),
		)
	);

	// ---------------------------------------------------------------- Image.
	$content .= wlm_note(
		'Image',
		'A single picture, or a video that plays quietly in the background, with an optional caption.',
		array(
			'Example 1: image, full width (edge to edge), wide shape, with a caption.',
			'Example 2: image, contained (page margins), standard 16 to 9 shape, with a caption.',
			'Example 3: image, contained, original image shape, no caption.',
			'Example 4: image, full width, standard shape, no caption.',
			'Example 5: video from a YouTube or Vimeo link, contained, wide, with a poster picture and caption.',
			'Not shown: "Upload an MP4" as the video source (needs an uploaded video file). The image alternative text is required for accessibility.',
		),
		'Add block, then Page Sections, then Image.',
		'block',
		'image'
	);
	$content .= wlm_blk(
		'image',
		array(
			'media_type' => 'image',
			'width'      => 'full',
			'ratio'      => 'wide',
			'image'      => wlm_img( 'cs-aliwood' ),
			'alt'        => 'A manufacturing website shown on a laptop beside a product sample',
			'caption'    => 'Full width, wide shape, with a caption.',
		)
	);
	$content .= wlm_blk(
		'image',
		array(
			'media_type' => 'image',
			'width'      => 'contained',
			'ratio'      => 'standard',
			'image'      => wlm_img( 'cs-duralock' ),
			'alt'        => 'Security hardware product pages on a tablet and phone',
			'caption'    => 'Contained, standard 16 to 9 shape, with a caption.',
		)
	);
	$content .= wlm_blk(
		'image',
		array(
			'media_type' => 'image',
			'width'      => 'contained',
			'ratio'      => 'original',
			'image'      => wlm_img( 'cs-kenwood' ),
			'alt'        => 'A damp-proofing company website on a laptop',
		)
	);
	$content .= wlm_blk(
		'image',
		array(
			'media_type' => 'image',
			'width'      => 'full',
			'ratio'      => 'standard',
			'image'      => wlm_img( 'cs-pjbones' ),
			'alt'        => 'A plumbing and heating website on desktop and mobile',
		)
	);
	$content .= wlm_blk(
		'image',
		array(
			'media_type'   => 'video',
			'width'        => 'contained',
			'ratio'        => 'wide',
			'video_source' => 'embed',
			'video_url'    => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
			'poster'       => wlm_img( 'ins-event' ),
			'video_alt'    => 'A short sample video',
			'caption'      => 'YouTube link, plays muted on a loop, contained and wide.',
		)
	);

	// ---------------------------------------------------------------- Gallery.
	$content .= wlm_note(
		'Gallery',
		'A group of photos. The mosaic is a full-width grid for people and place pages. The pair sits side by side inside articles.',
		array(
			'Example 1: Mosaic with exactly 7 photos (the layout is designed around 7, and takes 1 to 9).',
			'Example 2: Pair with 1 photo, no captions.',
			'Example 3: Pair with 2 photos and captions switched on. Captions come from the caption of each photo in the media library.',
		),
		'Add block, then Page Sections, then Gallery.',
		'block',
		'gallery'
	);
	$content .= wlm_blk(
		'gallery',
		array(
			'layout' => 'mosaic',
			'images' => array(
				wlm_img( 'careers-mosaic-1' ),
				wlm_img( 'careers-mosaic-2' ),
				wlm_img( 'careers-mosaic-3' ),
				wlm_img( 'careers-mosaic-4' ),
				wlm_img( 'careers-mosaic-5' ),
				wlm_img( 'careers-mosaic-6' ),
				wlm_img( 'careers-mosaic-7' ),
			),
		)
	);
	$content .= wlm_blk(
		'gallery',
		array(
			'layout'        => 'pair',
			'images'        => array( wlm_img( 'insight-1' ) ),
			'show_captions' => 0,
		)
	);
	$content .= wlm_blk(
		'gallery',
		array(
			'layout'        => 'pair',
			'images'        => array( wlm_img( 'insight-2' ), wlm_img( 'insight-3' ) ),
			'show_captions' => 1,
		)
	);

	// ---------------------------------------------------------------- Video.
	$content .= wlm_note(
		'Video',
		'A YouTube or Vimeo video shown as a picture with a play button. The video only loads when a visitor presses play, which keeps the page fast.',
		array(
			'Example 1: YouTube link with a poster picture and caption.',
			'Example 2: YouTube link with no poster (a plain dark frame) and no caption.',
		),
		'Add block, then Page Sections, then Video.',
		'block',
		'video'
	);
	$content .= wlm_blk(
		'video',
		array(
			'url'     => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
			'poster'  => wlm_img( 'cs-wci' ),
			'caption' => 'With a poster picture and a caption.',
		)
	);
	$content .= wlm_blk(
		'video',
		array(
			'url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
		)
	);

	// ---------------------------------------------------------------- Stat.
	$content .= wlm_note(
		'Stats',
		'Big coral numbers with a short caption. Add one, two or three in a row. Each can link to where the number comes from.',
		array(
			'Example 1: one stat, with a source link.',
			'Example 2: two stats, one with a source link and one without.',
			'Example 3: three stats, no source links.',
		),
		'Add block, then Page Sections, then Stat.',
		'block',
		'stat'
	);
	$content .= wlm_blk(
		'stat',
		array(
			'stats' => array(
				array(
					'value'   => '87%',
					'caption' => 'increase in conversions after a new search plan.',
					'source'  => wlm_lnk( 'Source', $cases ),
				),
			),
		)
	);
	$content .= wlm_blk(
		'stat',
		array(
			'stats' => array(
				array(
					'value'   => '3x',
					'caption' => 'more search impressions in under a year.',
					'source'  => wlm_lnk( 'Source', $cases ),
				),
				array(
					'value'   => '9.85%',
					'caption' => 'click-through rate on managed paid campaigns.',
				),
			),
		)
	);
	$content .= wlm_blk(
		'stat',
		array(
			'stats' => array(
				array(
					'value'   => '580%',
					'caption' => 'rise in form submissions.',
				),
				array(
					'value'   => '5.1+',
					'caption' => 'average position gain in six months.',
				),
				array(
					'value'   => '1 day',
					'caption' => 'typical reply time to a new enquiry.',
				),
			),
		)
	);

	// ---------------------------------------------------------------- Banner.
	$content .= wlm_note(
		'Banner',
		'A short, eye-catching message between sections. Use it for a promotion, an offer or a pointer to another page.',
		array(
			'Example 1: image and text on sand, with a button.',
			'Example 2: image and text on sand, no button.',
			'Example 3: coral, text only. Links typed inside the text appear underlined. The image and button are not available on this version.',
		),
		'Add block, then Page Sections, then Banner.',
		'block',
		'banner'
	);
	$content .= wlm_blk(
		'banner',
		array(
			'variant' => 'image',
			'image'   => wlm_img( 'ins-tiles' ),
			'text'    => '<p><strong>Not sure where to start?</strong> Book a free, no-obligation review of your current search and website performance.</p>',
			'button'  => wlm_lnk( 'Book a review', $contact ),
		)
	);
	$content .= wlm_blk(
		'banner',
		array(
			'variant' => 'image',
			'image'   => wlm_img( 'ins-cubes' ),
			'text'    => '<p>Our latest guide to AI search for manufacturers is now online.</p>',
		)
	);
	$content .= wlm_blk(
		'banner',
		array(
			'variant' => 'coral',
			'text'    => '<p>Looking for a new website partner? <a href="' . esc_url( $contact ) . '">Get in touch</a> or read our <a href="' . esc_url( $cases ) . '">case studies</a>.</p>',
		)
	);

	// ---------------------------------------------------------------- CTA band.
	$content .= wlm_note(
		'Call to action band',
		'A slim band near the bottom of a page that asks the visitor to get in touch. Many pages use the version in Theme Options automatically, so only add it by hand when you want a different message.',
		array(
			'Example 1: message and a link.',
			'Example 2: message only, no link.',
		),
		'Add block, then Page Sections, then CTA band.',
		'block',
		'cta-band'
	);
	$content .= wlm_blk(
		'cta-band',
		array(
			'text' => 'Interested in working with us?',
			'link' => wlm_lnk( 'Get in touch', $contact ),
		)
	);
	$content .= wlm_blk(
		'cta-band',
		array(
			'text' => 'We would love to hear about your next project.',
		)
	);

	// ---------------------------------------------------------------- Message frame.
	$content .= wlm_note(
		'Message frame',
		'A framed confirmation message, for example after a form is sent. A heading, a small label above it, an optional coral highlighted phrase and an optional button.',
		array(
			'Example 1: heading level 1 (as used on a page of its own), coral highlighted phrase and a button.',
			'Example 2: heading level 2 (use this when the page already has a hero), highlight, no button.',
			'Example 3: heading level 2, no highlight and no button.',
		),
		'Add block, then Page Sections, then Message frame.',
		'block',
		'message-frame'
	);
	$content .= wlm_blk(
		'message-frame',
		array(
			'label'       => 'Message received',
			'heading_tag' => 'h1',
			'heading'     => 'Thank you. We will be in touch soon',
			'highlight'   => 'Thank you.',
			'button'      => wlm_lnk( 'Back to the home page', home_url( '/' ) ),
		)
	);
	$content .= wlm_blk(
		'message-frame',
		array(
			'label'       => 'Almost there',
			'heading_tag' => 'h2',
			'heading'     => 'Check your inbox for a confirmation',
			'highlight'   => 'confirmation',
		)
	);
	$content .= wlm_blk(
		'message-frame',
		array(
			'label'       => 'Heads up',
			'heading_tag' => 'h2',
			'heading'     => 'We are closed on bank holidays',
			'highlight'   => '',
		)
	);

	$id = wlm_page( 'content-blocks', 'Master: Content blocks', $content );

	return 'content-blocks page ' . $id;
};
