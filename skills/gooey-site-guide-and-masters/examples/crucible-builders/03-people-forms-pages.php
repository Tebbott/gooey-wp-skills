<?php
/**
 * Master pages: people, forms and contact blocks; Contact page; Careers page; Legal page.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$post_id = function ( $slug, $type ) {
		$p = get_page_by_path( $slug, OBJECT, $type );
		return $p ? (int) $p->ID : 0;
	};

	$people = array();
	foreach ( array( 'carrie-adam', 'george-hosegood', 'kerrie-bingle', 'darren-bond', 'charles-obrien', 'alessio-maccuro' ) as $s ) {
		$id = $post_id( $s, 'team_member' );
		if ( $id ) {
			$people[] = $id;
		}
	}

	$quotes = array();
	foreach ( array( 'alex-rayner-braxted-park-bng', 'dan-goddard-broxap', 'marcia-garcia-crucible', 'sam-whybrow-whybrow' ) as $s ) {
		$id = $post_id( $s, 'testimonial' );
		if ( $id ) {
			$quotes[] = $id;
		}
	}

	$careers_url = home_url( '/careers/' );
	$contact_url = home_url( '/contact/' );
	$edit_note   = 'Add block, then ';

	/*
	 * Page 1: people, forms and contact blocks.
	 */
	$c  = wlm_note(
		'People, forms and contact blocks',
		'This page shows the blocks used to introduce your team, list vacancies, take enquiries and show where you are. Each one has a label above it listing its options. Copy a block into your own page, then change the words and pictures.',
		array(
			'Team grid: everyone, or a hand-picked set, with or without the side column and button.',
			'Vacancies: 3 or 6 roles, your own button wording, and an empty message.',
			'Form: the Contact form or the Careers application form, with testimonials and an optional dark band.',
			'Social and map: social links, a careers button, a photo and a directions button.',
		),
		'Add block, then use the groups named in each label below.',
		'page',
		'top'
	);

	$c .= wlm_note(
		'Team grid',
		'Shows your team as a grid of photo cards. Clicking a card opens that person\'s pull-out with their story. An optional side column beside the first row can carry a short heading, a few words and a button. The examples below show every combination.',
		array(
			'Example 1: everyone in the Team list (Manual selection off), with the side column: heading, text and button.',
			'Example 2: a hand-picked set of four people (Manual selection on), with the side column but no button.',
			'Example 3: a hand-picked set of three people with no side column at all, so the grid uses the full width.',
			'Title: the main heading. The full stop is added for you.',
			'Side heading, text and button: all optional. Leave all three empty to hide the column.',
			'Manual selection: off shows everyone in the order set in the Team list (Order field). On shows only the people you pick, in the order you pick them.',
		),
		$edit_note . 'Page Sections, then Team Grid. People themselves are added under Team in the dashboard menu.',
		'block',
		'team-grid'
	);
	$c .= wlm_blk(
		'team-grid',
		array(
			'title'         => 'Crucible Team',
			'intro_heading' => 'Working at Crucible',
			'intro_text'    => 'We are a friendly, hands-on team of marketers, designers and developers. Meet the people you will be working with.',
			'intro_button'  => wlm_lnk( 'Careers', $careers_url ),
			'manual'        => 0,
		)
	);
	$c .= wlm_blk(
		'team-grid',
		array(
			'title'         => 'Meet the web team',
			'intro_heading' => 'Who you will talk to',
			'intro_text'    => 'A small group looks after every project from the first call to launch day.',
			'manual'        => 1,
			'members'       => array_slice( $people, 0, 4 ),
		)
	);
	$c .= wlm_blk(
		'team-grid',
		array(
			'title'  => 'Your account team',
			'manual' => 1,
			'members' => array_slice( $people, 3, 3 ),
		)
	);

	$c .= wlm_note(
		'Vacancies',
		'Shows the latest roles from Careers in the dashboard menu as cards. The button on each card opens the full role in a pull-out. Nothing is typed here for the roles themselves: add, edit or unpublish a role under Careers and this block follows.',
		array(
			'Example 1: 3 roles (one row), the coral dot before the title on, the default button wording "More details" and a short intro.',
			'Example 2: 6 roles (two rows), the dot off, custom button wording ("Read the role") and no intro. With only three roles published, three cards show, plus quiet empty spaces that keep the row tidy.',
			'Show dot: turns the big coral dot before the title on or off.',
			'Number of roles: 3 or 6. The newest roles are shown first.',
			'Button text: the label on every card. Leave empty to use "More details".',
			'Empty message and Empty link: shown instead of the cards when there are no published roles, for example "No current vacancies. Send us your CV anyway." with a button that scrolls to the application form (it points to #apply unless you set your own link). This master cannot show it because it reads the live roles, and three are published. To see it, unpublish all roles temporarily under Careers.',
		),
		$edit_note . 'Page Sections, then Vacancies.',
		'block',
		'vacancies'
	);
	$c .= wlm_blk(
		'vacancies',
		array(
			'show_bullet' => 1,
			'title'       => 'Current Vacancies',
			'description' => 'We are growing and looking for talented people to join us. Read about each role, then apply using the form on the Careers page.',
			'count'       => '3',
			'empty_text'  => 'No current vacancies. Send us your CV anyway.',
			'empty_link'  => wlm_lnk( 'Send us your CV', $careers_url . '#apply' ),
		)
	);
	$c .= wlm_blk(
		'vacancies',
		array(
			'show_bullet' => 0,
			'title'       => 'Open roles',
			'count'       => '6',
			'button_text' => 'Read the role',
			'empty_text'  => 'We have no open roles right now, but we would still love to hear from you.',
			'empty_link'  => wlm_lnk( 'Get in touch', $contact_url ),
		)
	);

	$c .= wlm_note(
		'Form',
		'A split band with a testimonial slider on one side and a ready-made form on the other. The forms themselves are managed for you, so there is nothing to build: pick the variant and add the words around them.',
		array(
			'Example 1: Contact variant, contact details on (intro, email and phone from Theme Options), four testimonials, heading dot off, no band.',
			'Example 2: Contact variant with the dark band above the split (band heading and text), the heading dot on and a short line under the heading.',
			'Example 3: Contact variant with contact details off and only two testimonials.',
			'Example 4: Careers application variant (role dropdown and CV upload), three testimonials, dark band, heading dot on.',
			'Good to know: a form can only appear once on a page. On this reference page the form itself therefore shows in the first Contact example only; the other Contact examples show the heading, details, band and testimonials with an empty space where the form would be. On your own pages, use one Form block per variant.',
			'Variant: Contact shows the enquiry form. Careers application shows the role application form.',
			'Heading and heading dot: the text above the form, with or without the coral full stop.',
			'Form intro: optional line under the heading.',
			'Contact details: only for the Contact variant. Shows the intro line plus the email and phone number set in Theme Options.',
			'Testimonials: choose 2 to 4. They slide beside the form.',
			'Dark band: optional black strip above the split with its own heading and text, for example "Apply for a role." or "Book an intro conversation with us."',
		),
		$edit_note . 'Page Sections, then Form.',
		'block',
		'form'
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'       => 'contact',
			'heading'       => 'I am interested in',
			'heading_dot'   => 0,
			'show_contact'  => 1,
			'contact_intro' => 'Have questions? Reach out and we\'ll get in contact within the next 48 hours.',
			'testimonials'  => $quotes,
			'show_band'     => 0,
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'       => 'contact',
			'heading'       => 'Tell us about your project',
			'heading_dot'   => 1,
			'form_intro'    => 'Choose the services you are interested in and we will take it from there.',
			'show_contact'  => 1,
			'contact_intro' => 'Prefer to talk it through? Call or email and we will reply within two working days.',
			'testimonials'  => array_slice( $quotes, 0, 3 ),
			'show_band'     => 1,
			'band_heading'  => 'Book an intro conversation with us',
			'band_text'     => 'No pressure and no jargon. We will listen first, then suggest what could help.',
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'      => 'contact',
			'heading'      => 'How can we help',
			'heading_dot'  => 1,
			'show_contact' => 0,
			'testimonials' => array_slice( $quotes, 1, 2 ),
			'show_band'    => 0,
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'       => 'careers',
			'heading'       => 'Role I\'m interested in',
			'heading_dot'   => 1,
			'show_contact'  => 0,
			'testimonials'  => array_slice( $quotes, 1, 3 ),
			'show_band'     => 1,
			'band_heading'  => 'Apply for a role',
			'band_text'     => 'If you have any questions about working at Crucible, give us a call. We would love to hear from you.',
		)
	);

	$c .= wlm_note(
		'Social and map',
		'The closing strip of a contact page: where to find you on social media, a button to Careers, a photo, and a map-style panel with your address and a directions button.',
		array(
			'Connect label: the line above the social icons. The icons themselves come from Theme Options.',
			'Careers button: optional outline button under the icons (link text becomes the label).',
			'Photo: a picture of the area around your office.',
			'Map heading and Button text: the heading and the label of the button that opens your address (from Theme Options) in Google Maps.',
		),
		$edit_note . 'Page Sections, then Social and Map.',
		'block',
		'social-map'
	);
	$c .= wlm_blk(
		'social-map',
		array(
			'connect_label' => 'Connect on',
			'careers_link'  => wlm_lnk( 'Careers', $careers_url ),
			'image'         => wlm_img( 'spec-construction' ),
			'map_heading'   => 'Where to find us',
			'button_text'   => 'Get directions',
		)
	);

	$p1 = wlm_page( 'people-forms-and-contact', 'Master: People, forms and contact blocks', $c );

	/*
	 * Page 2: Contact page.
	 */
	$c  = wlm_note(
		'Contact page',
		'The Contact page is a black hero, then the enquiry form beside testimonials, then the social links and map. Copy this page to start your own.',
		array(
			'Hero (Dark): the heading breaks onto two lines and gets its coral full stop automatically.',
			'Form (Contact variant): contact details on, four testimonials, heading dot off.',
			'Social and Map: socials, Careers button, photo and directions button.',
		),
		'Add page, then add the three blocks in this order: Hero (Dark), Form, Social and Map.',
		'page',
		'top'
	);
	$c .= wlm_blk(
		'hero-dark',
		array(
			'heading' => "Talk to us and\nStart Your Project",
			'text'    => 'Tell us a little about what you are working on. A real person will read your message and reply within 48 hours.',
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'       => 'contact',
			'heading'       => 'I am interested in',
			'heading_dot'   => 0,
			'show_contact'  => 1,
			'contact_intro' => 'Have questions? Reach out and we\'ll get in contact within the next 48 hours.',
			'testimonials'  => $quotes,
			'show_band'     => 0,
		)
	);
	$c .= wlm_blk(
		'social-map',
		array(
			'connect_label' => 'Connect on',
			'careers_link'  => wlm_lnk( 'Careers', $careers_url ),
			'image'         => wlm_img( 'spec-construction' ),
			'map_heading'   => 'Where to find us',
			'button_text'   => 'Get directions',
		)
	);
	$p2 = wlm_page( 'contact-page', 'Master: Contact page', $c );

	/*
	 * Page 3: Careers page.
	 */
	$c  = wlm_note(
		'Careers page',
		'The Careers page introduces working at Crucible, lists live vacancies, and ends with the application form. Roles come from Careers in the dashboard menu, so the vacancies block updates itself.',
		array(
			'Hero (Simple), then Text and Image with the team photo.',
			'Vacancies (3 roles), then a call-to-action band whose button scrolls to the form.',
			'Stat and Testimonial, then a Gallery with 7 photos in the Mosaic layout.',
			'Work Rollup (2 case studies), then the Form block (Careers application variant) with the anchor "apply", then Latest Insights.',
			'The anchor "apply" is set under Advanced on the Form block. The button and the empty vacancies message link to #apply.',
		),
		'Add page, then add the blocks in the order shown.',
		'page',
		'top'
	);
	$c .= wlm_blk(
		'hero-simple',
		array(
			'title'       => 'Working at Crucible',
			'description' => 'We are a small, friendly team that does great work for construction, property and manufacturing clients. If you like solving interesting problems with good people, read on.',
			'background'  => 'secondary',
		)
	);
	$c .= wlm_blk(
		'text-image',
		array(
			'variant'      => 'left',
			'background'   => 'primary',
			'image_effect' => 0,
			'title'        => 'Here at Crucible, we are lucky enough to have some of the most talented minds in the business.',
			'highlight'    => 'Here at Crucible',
			'description'  => '<p>And we are always on the look out for more wonderful people to join the team.</p><p>We do our part to help our team feel supported, both professionally and personally.</p>',
			'buttons'      => array(
				array( 'link' => wlm_lnk( 'Meet the team', home_url( '/about/#team' ) ) ),
			),
			'media_type'   => 'image',
			'image'        => wlm_img( 'careers-team-photo' ),
		)
	);
	$c .= wlm_blk(
		'vacancies',
		array(
			'show_bullet' => 1,
			'title'       => 'Current Vacancies',
			'description' => 'We are growing and looking for talented people to join us. Read about our current roles below and apply using the form at the bottom of the page.',
			'count'       => '3',
		)
	);
	$c .= wlm_blk(
		'cta-band',
		array(
			'text' => 'Interested in applying?',
			'link' => wlm_lnk( 'Apply now', '#apply' ),
		)
	);
	$c .= wlm_blk(
		'stat-testimonial',
		array(
			'label'        => 'Testimonials',
			'testimonials' => array_slice( $quotes, 2, 1 ),
		)
	);
	$gallery = array();
	for ( $i = 1; $i <= 7; $i++ ) {
		$gallery[] = wlm_img( 'careers-mosaic-' . $i );
	}
	$c .= wlm_blk(
		'gallery',
		array(
			'layout'        => 'mosaic',
			'images'        => array_values( array_filter( $gallery ) ),
			'show_captions' => 0,
		)
	);
	$c .= wlm_blk(
		'work-rollup',
		array(
			'title'  => 'Selected Projects',
			'count'  => '2',
			'manual' => 0,
		)
	);
	$c .= wlm_blk(
		'form',
		array(
			'variant'      => 'careers',
			'heading'      => 'Role I\'m interested in',
			'heading_dot'  => 1,
			'show_contact' => 0,
			'testimonials' => array_slice( $quotes, 1, 2 ),
			'show_band'    => 1,
			'band_heading' => 'Apply for a role',
			'band_text'    => 'If you have any questions about working at Crucible, give us a call. We would love to hear from you.',
		),
		'apply'
	);
	$c .= wlm_blk(
		'news-rollup',
		array(
			'heading' => 'Latest insights',
			'source'  => 'latest',
		)
	);
	$p3 = wlm_page( 'careers-page', 'Master: Careers page', $c );

	/*
	 * Page 4: Legal page, written with core blocks.
	 */
	$h2 = function ( $t ) {
		return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $t . "</h2>\n<!-- /wp:heading -->\n\n";
	};
	$h3 = function ( $t ) {
		return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . $t . "</h3>\n<!-- /wp:heading -->\n\n";
	};
	$pp = function ( $t ) {
		return "<!-- wp:paragraph -->\n<p>" . $t . "</p>\n<!-- /wp:paragraph -->\n\n";
	};
	$ls = function ( $items, $ordered = false ) {
		$tag = $ordered ? 'ol' : 'ul';
		$out = $ordered ? "<!-- wp:list {\"ordered\":true} -->\n" : "<!-- wp:list -->\n";
		$out .= '<' . $tag . ' class="wp-block-list">';
		foreach ( $items as $item ) {
			$out .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
		}
		return $out . '</' . $tag . ">\n<!-- /wp:list -->\n\n";
	};

	$privacy = home_url( '/privacy-policy/' );
	$cookie  = home_url( '/cookie-policy/' );

	$rows = array(
		array( 'wordpress_test_cookie', 'This website', 'Checks that your browser accepts cookies.', 'Session' ),
		array( 'wl_cookie_consent', 'This website', 'Remembers the choice you made on the cookie banner.', '1 year' ),
		array( '_ga', 'Google Analytics', 'Tells us, without naming you, how many people visit and which pages they read.', '2 years' ),
		array( '_gid', 'Google Analytics', 'Helps us count visits in a single day.', '1 day' ),
	);
	$table  = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><thead><tr><th>Name</th><th>Provider</th><th>What it does</th><th>How long it stays</th></tr></thead><tbody>";
	foreach ( $rows as $r ) {
		$table .= '<tr><td>' . implode( '</td><td>', $r ) . '</td></tr>';
	}
	$table .= "</tbody></table></figure>\n<!-- /wp:table -->\n\n";

	$c  = wlm_note(
		'Legal page',
		'Legal pages are a Hero (Simple) with a "last updated" line, followed by ordinary text written with the standard WordPress blocks. The Cookie Policy and Privacy Policy pages use this style. There are no special blocks to learn: headings, paragraphs, lists, tables, quotes and separators are all styled by the theme.',
		array(
			'Hero (Simple): switch on "Show last updated" to add "Last updated on [date]" from the date the page was last edited. Use the Meta field to write your own line instead.',
			'Heading 2 starts each main section. Heading 3 breaks a section into smaller parts.',
			'Paragraphs can contain links. Select the words, then use the link button in the toolbar.',
			'Bulleted lists for things that are in no particular order, numbered lists for steps.',
			'Table for things like a list of cookies. Use the first row for the column headings.',
			'Quote for a short call-out, and a Separator to close a section.',
			'The wording below is plain-English example text. Have your own policy checked before publishing it.',
		),
		'Add page, add Hero (Simple), then use the + button for Heading, Paragraph, List, Table, Quote and Separator.',
		'page',
		'top'
	);
	$c .= wlm_blk(
		'hero-simple',
		array(
			'title'             => 'Cookie and Privacy Policy',
			'background'        => 'secondary',
			'show_last_updated' => 1,
		)
	);
	$c .= $pp( 'This page explains, in plain English, how this website uses cookies and looks after the information you give us. If anything is unclear, <a href="' . esc_url( $contact_url ) . '">get in touch</a> and we will happily explain.' );
	$c .= $h2( 'What cookies are' );
	$c .= $pp( 'Cookies are small text files that a website saves on your device when you visit. They help a site work properly, remember your choices and tell its owners how it is being used.' );
	$c .= $pp( 'We only set the cookies that are strictly necessary without asking you first. For everything else we ask for your permission, and you can change your mind at any time.' );
	$c .= $h3( 'Cookies we use' );
	$c .= $pp( 'The table below lists the main cookies on this site. It is an example: the full, current list comes from the cookie settings.' );
	$c .= $table;
	$c .= $h3( 'How to switch cookies off' );
	$c .= $ls(
		array(
			'Use the cookie settings link in the footer of any page to change your choices.',
			'Change the settings in your browser to block or delete cookies.',
			'Use a private or incognito window, which clears cookies when you close it.',
		)
	);
	$c .= $pp( 'Blocking necessary cookies may stop parts of the site, such as the contact form, from working.' );
	$c .= "<!-- wp:separator -->\n<hr class=\"wp-block-separator has-alpha-channel-opacity\"/>\n<!-- /wp:separator -->\n\n";
	$c .= $h2( 'The information we collect' );
	$c .= $pp( 'When you fill in a form we collect what you type, such as your name, email address, phone number, company and message. If you apply for a role we also receive your CV.' );
	$c .= $h3( 'What we do with it' );
	$c .= $ls(
		array(
			'Reply to your enquiry or application.',
			'Send news and updates, but only if you have asked for them.',
			'Keep the website secure and understand how it is used.',
		)
	);
	$c .= "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>We will never sell your details, and we will never share them with anyone who has no reason to see them.</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->\n\n";
	$c .= $h2( 'Your rights' );
	$c .= $pp( 'You are in control of your information. You can ask us to:' );
	$c .= $ls(
		array(
			'Show you what we hold about you.',
			'Correct anything that is wrong.',
			'Delete your information, unless the law says we must keep it.',
			'Stop using your information for marketing.',
		),
		true
	);
	$c .= $pp( 'To do any of these, email us using the details on our <a href="' . esc_url( $contact_url ) . '">Contact page</a>. You can also read our <a href="' . esc_url( $privacy ) . '">Privacy Policy</a> and <a href="' . esc_url( $cookie ) . '">Cookie Policy</a> pages, which use this same layout.' );
	$p4 = wlm_page( 'legal-page', 'Master: Legal page', $c );

	return 'people-forms-and-contact ' . $p1 . ', contact-page ' . $p2 . ', careers-page ' . $p3 . ', legal-page ' . $p4;
};
