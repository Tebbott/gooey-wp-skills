<?php
/**
 * The Site Guide page for this website: how the blocks, example pages and content types work and how they connect.
 *
 * @var string $logo Address of the client's logo, or an empty string.
 *
 * @package SiteGuide
 */

defined( 'ABSPATH' ) || exit;

$sg_admin = function ( $path ) {
	return esc_url( admin_url( $path ) );
};
?>
<div class="sg">

	<div class="cover">
		<?php if ( $logo ) { ?>
			<div class="cover__logo"><img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></div>
		<?php } ?>
		<div class="cover__body">
			<p class="cover__kicker"><?php esc_html_e( 'Website guide', 'site-guide' ); ?></p>
			<h1><?php esc_html_e( 'How your website works', 'site-guide' ); ?></h1>
			<p><?php esc_html_e( 'Your blocks, example pages and content types, and how the pieces of the site connect to each other.', 'site-guide' ); ?></p>
		</div>
	</div>

	<ul class="toc" aria-label="<?php esc_attr_e( 'Contents', 'site-guide' ); ?>">
		<li><a href="#big-picture"><?php esc_html_e( 'The big picture', 'site-guide' ); ?></a></li>
		<li><a href="#content-types"><?php esc_html_e( 'Content types', 'site-guide' ); ?></a></li>
		<li><a href="#blocks"><?php esc_html_e( 'Page blocks', 'site-guide' ); ?></a></li>
		<li><a href="#shared"><?php esc_html_e( 'Shared content', 'site-guide' ); ?></a></li>
		<li><a href="#tasks"><?php esc_html_e( 'Common jobs', 'site-guide' ); ?></a></li>
		<li><a href="#listings"><?php esc_html_e( 'Listings and filters', 'site-guide' ); ?></a></li>
		<li><a href="#forms"><?php esc_html_e( 'Forms', 'site-guide' ); ?></a></li>
		<li><a href="#masters"><?php esc_html_e( 'Master pages', 'site-guide' ); ?></a></li>
		<li><a href="#tips"><?php esc_html_e( 'Good habits', 'site-guide' ); ?></a></li>
	</ul>

	<section id="big-picture" class="first">
		<h2 data-n="01"><?php esc_html_e( 'The big picture', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Every page is built from blocks stacked one after another. Each block is one section of the page (a hero, a row of services, a testimonial slider) with its own fields to fill in. You change content, not layout, so the design stays consistent.', 'site-guide' ); ?></p>

		<div class="three">
			<div class="card">
				<b><?php esc_html_e( 'Pages', 'site-guide' ); ?></b>
				<p><?php esc_html_e( 'Home, About, Services, Specialism, Careers, Contact and the legal pages. You build them by adding blocks.', 'site-guide' ); ?></p>
			</div>
			<div class="card">
				<b><?php esc_html_e( 'Content types', 'site-guide' ); ?></b>
				<p><?php esc_html_e( 'Things you add many of: case studies, insights, services, roles, team members and testimonials. Each has its own page template, so you only fill in the content.', 'site-guide' ); ?></p>
			</div>
			<div class="card">
				<b><?php esc_html_e( 'Site-wide settings', 'site-guide' ); ?></b>
				<p><?php esc_html_e( 'The logo, contact details, social links, footer, newsletter and menus. Change them once and every page updates.', 'site-guide' ); ?></p>
			</div>
		</div>

		<p class="tip">
			<?php esc_html_e( 'Blocks pull from content types automatically. For example, the "Selected projects" block shows your case studies, and the Services menu lists your services, so adding a case study or a service updates those places without any extra work.', 'site-guide' ); ?>
		</p>
	</section>

	<section id="content-types">
		<h2 data-n="02"><?php esc_html_e( 'Content types', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Each content type lives in its own menu on the left of the admin.', 'site-guide' ); ?></p>
		<table>
			<thead>
				<tr><th><?php esc_html_e( 'Content type', 'site-guide' ); ?></th><th><?php esc_html_e( 'What it is', 'site-guide' ); ?></th><th><?php esc_html_e( 'Where it appears', 'site-guide' ); ?></th></tr>
			</thead>
			<tbody>
				<tr class="grp"><td colspan="3"><?php esc_html_e( 'What visitors read', 'site-guide' ); ?></td></tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Insights', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'Articles, podcast episodes and events. Pick a Format (Article, Event or Podcast) and one or more Topics.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Insights page, topic and podcast pages, the Home page and "Related insights".', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=case_study' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Case studies', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A project write-up with a headline result. Tag it with the Service area and Sector so it can be filtered.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Case studies page, "Selected projects" blocks and the latest case study on each service.', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=service' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Services', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'One page per service. "Type" decides whether it is a main service (card and image) or an additional one (text link).', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Services menu, the Services page, the Home page and its own page.', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=career' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Careers', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A vacancy. Publish it to add it to the Careers page and the application form; set it to Draft to take it down.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Careers page (opens as a side panel) and its own shareable page.', 'site-guide' ); ?></td>
				</tr>
				<tr class="grp"><td colspan="3"><?php esc_html_e( 'Behind the scenes', 'site-guide' ); ?></td></tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=team_member' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Team', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A person: photo (featured image), job title, short bio and LinkedIn link. Drag to reorder with the Order box.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Team grid on About and the author line on podcasts and events.', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=testimonial' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Testimonials', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A quote, who said it and an optional big number ("150+") with a caption.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'Testimonial sliders, the quote in the services block and the sidebar on case studies. Pick which ones each block shows.', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'FAQs', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A question and answer you can reuse on several pages.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The FAQ block, wherever you add it.', 'site-guide' ); ?></td>
				</tr>
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=proposal' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Proposals', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( 'A private document for a client. Only people who are logged in can see it, and search engines are told to ignore it.', 'site-guide' ); ?></td>
					<td><?php esc_html_e( 'The Login area (Proposals list).', 'site-guide' ); ?></td>
				</tr>
			</tbody>
		</table>
	</section>

	<section id="blocks">
		<h2 data-n="03"><?php esc_html_e( 'Page blocks', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'In any page, click the + button and look under "Page Sections". Each block has its own fields in the panel on the right.', 'site-guide' ); ?></p>
		<table>
			<thead>
				<tr><th><?php esc_html_e( 'Block', 'site-guide' ); ?></th><th><?php esc_html_e( 'What it does', 'site-guide' ); ?></th></tr>
			</thead>
			<tbody>
				<tr class="grp"><td colspan="2"><?php esc_html_e( 'Page openers (use one at the top of a page; it provides the page heading)', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Hero (Home)', 'site-guide' ); ?></td><td><?php esc_html_e( 'Big video or image with the headline, two buttons and the latest podcast card.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Hero (Simple)', 'site-guide' ); ?></td><td><?php esc_html_e( 'A plain heading band with optional intro text. Used on most inner pages.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Hero (Service)', 'site-guide' ); ?></td><td><?php esc_html_e( 'Service or Specialism opener with an image and the latest related case study.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Hero (Dark)', 'site-guide' ); ?></td><td><?php esc_html_e( 'A black heading band for Contact and Login style pages.', 'site-guide' ); ?></td></tr>
				<tr class="grp"><td colspan="2"><?php esc_html_e( 'Sections', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Logo bar', 'site-guide' ); ?></td><td><?php esc_html_e( 'A row of client logos (6 to 15). They show in black and scroll automatically when there are too many to fit.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Services grid / Service panel', 'site-guide' ); ?></td><td><?php esc_html_e( 'Your services as cards, or as stacked panels on the Services page. They read from the Services content type.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Selected projects', 'site-guide' ); ?></td><td><?php esc_html_e( 'Two or four case studies, newest first or hand-picked.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Latest insights', 'site-guide' ); ?></td><td><?php esc_html_e( 'Three insights: the newest, hand-picked, or "related" to the article being read.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Stat + testimonial', 'site-guide' ); ?></td><td><?php esc_html_e( 'A slider of testimonials with a big number beside each quote.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Text + image / Intro text', 'site-guide' ); ?></td><td><?php esc_html_e( 'Copy beside a photo (left or right), or a heading with text and buttons.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Card grid', 'site-guide' ); ?></td><td><?php esc_html_e( 'A row of two to four headline numbers, or up to three text cards.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Team grid', 'site-guide' ); ?></td><td><?php esc_html_e( 'Your team photos. Clicking a person opens their bio in a side panel.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Vacancies', 'site-guide' ); ?></td><td><?php esc_html_e( 'Open roles from Careers. Shows a message when there are none.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'FAQ', 'site-guide' ); ?></td><td><?php esc_html_e( 'An accordion of questions, either from your FAQs or typed in. Adds the right search-engine data automatically.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Pricing / Tabbed', 'site-guide' ); ?></td><td><?php esc_html_e( 'Price columns, and a tabbed "what you get month by month" panel.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Featured post', 'site-guide' ); ?></td><td><?php esc_html_e( 'Spotlight one insight (usually the next event or latest episode).', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Form', 'site-guide' ); ?></td><td><?php esc_html_e( 'A contact or careers form beside a testimonial slider.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Social + map', 'site-guide' ); ?></td><td><?php esc_html_e( 'Social links, address and a "Get directions" button for the Contact page.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'CTA band', 'site-guide' ); ?></td><td><?php esc_html_e( 'A dark strip with one line and a button.', 'site-guide' ); ?></td></tr>
				<tr class="grp"><td colspan="2"><?php esc_html_e( 'For articles and case studies', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Image, Gallery, Video', 'site-guide' ); ?></td><td><?php esc_html_e( 'Full-width image, photo mosaic or pair, and a video that only loads when someone presses play.', 'site-guide' ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Stat, Banner', 'site-guide' ); ?></td><td><?php esc_html_e( 'A highlighted number with a source link, and a call-out banner.', 'site-guide' ); ?></td></tr>
			</tbody>
		</table>
		<p class="tip"><?php esc_html_e( 'Ordinary text in an article (paragraphs, headings, lists, quotes, tables) uses the normal WordPress blocks. Use Heading 2 for the main sections: they build the "On this page" list automatically.', 'site-guide' ); ?></p>
	</section>

	<section id="shared">
		<h2 data-n="04"><?php esc_html_e( 'Shared content', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Some content appears on many pages. Change it in one place and it updates everywhere.', 'site-guide' ); ?></p>
		<dl class="links">
			<dt><a class="path" href="<?php echo $sg_admin( 'admin.php?page=theme-options' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Theme Options', 'site-guide' ); ?></a></dt>
			<dd><?php esc_html_e( 'The logo, phone, email and address, social links, the Contact button and the little round photo in the header, the footer call to action, newsletter wording, the "Ask AI" buttons and the footer legal links.', 'site-guide' ); ?></dd>
			<dt><a class="path" href="<?php echo $sg_admin( 'nav-menus.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Menus', 'site-guide' ); ?></a></dt>
			<dd><?php esc_html_e( 'The header and footer links. The Services dropdown fills itself from your Services, so leave the "has-mega" class on that item.', 'site-guide' ); ?></dd>
			<dt><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type=wp_block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Patterns', 'site-guide' ); ?></a></dt>
			<dd><?php esc_html_e( 'The "Interested in working with us?" strip is a shared pattern. Edit it here once and it changes on every page that uses it.', 'site-guide' ); ?></dd>
		</dl>
	</section>

	<section id="tasks">
		<h2 data-n="05"><?php esc_html_e( 'Common jobs', 'site-guide' ); ?></h2>

		<h3><?php esc_html_e( 'Add a case study', 'site-guide' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'Case studies, then Add new. Write the title, for example "87% Increase in Conversions – SEO for Westbury".', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'To colour the headline result in coral, type just that part into "Title highlight" exactly as it appears in the title ("87% Increase").', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Set the featured image, the client name, the Service area and the Sector. Write the story in the main editor and finish with a full-width image if you like.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Publish. It now appears on the Case studies page, in "Selected projects" and on the matching service.', 'site-guide' ); ?></li>
		</ol>

		<h3><?php esc_html_e( 'Add an article, podcast episode or event', 'site-guide' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'Insights, then Add new. Choose the Format on the right (Article, Event or Podcast) and a Topic.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Add a featured image and a short excerpt: the excerpt becomes the intro under the heading.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Events and podcasts show extra fields (date and location, or episode and listen link) once you pick that Format.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Publish. New items appear on the Insights page and the right filter tab automatically.', 'site-guide' ); ?></li>
		</ol>

		<h3><?php esc_html_e( 'Add a vacancy', 'site-guide' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'Careers, then Add new. Fill in the Location and Summary, then describe the role using headings and bullet lists.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Publish. It appears on the Careers page and in the "Role" list on the application form. Set it to Draft when the role is filled.', 'site-guide' ); ?></li>
		</ol>

		<h3><?php esc_html_e( 'Add a service', 'site-guide' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'Services, then Add new. Choose its Type, write a short card summary and set the featured image (it is used on cards and in the menu).', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Build the page with blocks: Hero (Service), Intro text, FAQ and so on. Use the Order box to place it in the menu.', 'site-guide' ); ?></li>
		</ol>

		<h3><?php esc_html_e( 'Change the order of anything', 'site-guide' ); ?></h3>
		<p><?php esc_html_e( 'Team members, services, case studies and testimonials follow the "Order" number in their Page Attributes box, then the date. Lower numbers come first.', 'site-guide' ); ?></p>
	</section>

	<section id="listings">
		<h2 data-n="06"><?php esc_html_e( 'Listings and filters', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'The Insights and Case studies pages have filter tabs and page numbers. They build themselves from your content, so there is nothing to set up.', 'site-guide' ); ?></p>
		<ul>
			<li><?php esc_html_e( 'Insights filters: Format tabs (All, Posts, Events, Podcast) and a Topic dropdown. Add new Topics under Posts, then Topics.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Case study filters: Service area tabs and a Sector dropdown.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'If a brand-new item does not show under a filter straight away, use "Re-index filters" in the black admin bar at the top of the screen.', 'site-guide' ); ?></li>
		</ul>
	</section>

	<section id="forms">
		<h2 data-n="07"><?php esc_html_e( 'Forms', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Contact, Careers application, the project timeline box and the newsletter sign-up are all managed under Forms.', 'site-guide' ); ?></p>
		<ul class="screens">
			<li><a href="<?php echo $sg_admin( 'admin.php?page=gf_entries' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Entries', 'site-guide' ); ?><small><?php esc_html_e( 'Everything people have submitted', 'site-guide' ); ?></small></a></li>
			<li><a href="<?php echo $sg_admin( 'admin.php?page=gf_edit_forms' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Forms', 'site-guide' ); ?><small><?php esc_html_e( 'Fields, emails and confirmations', 'site-guide' ); ?></small></a></li>
		</ul>
		<p class="tip"><?php esc_html_e( 'The Careers form only accepts PDF files up to 5MB. The Role drop-down is filled from your published vacancies.', 'site-guide' ); ?></p>
	</section>

	<section id="masters">
		<h2 data-n="08"><?php esc_html_e( 'Master pages', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'A set of example pages shows every block and every content template with every option filled in. Copy from them to build your own pages.', 'site-guide' ); ?></p>
		<?php $sg_master = get_page_by_path( 'master-pages' ); ?>
		<?php if ( $sg_master ) { ?>
			<ul class="screens">
				<li><a href="<?php echo esc_url( get_permalink( $sg_master ) ); ?>"><?php esc_html_e( 'Open the master pages', 'site-guide' ); ?><small><?php esc_html_e( 'Only you can see them', 'site-guide' ); ?></small></a></li>
				<li><a href="<?php echo $sg_admin( 'tools.php?page=wl-master-pages' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( 'Manage them', 'site-guide' ); ?><small><?php esc_html_e( 'Tools, then Master pages', 'site-guide' ); ?></small></a></li>
			</ul>
		<?php } ?>
		<ol>
			<li><?php esc_html_e( 'Open a master page. A grey dashed label above each block says what it is for and lists the options shown.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'To reuse one block, click it in the editor, copy it (Ctrl+C), and paste it into your own page (Ctrl+V). To reuse a whole page, select every block (Ctrl+A twice), copy and paste into a new page.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Delete the grey dashed labels, then change the words and photographs.', 'site-guide' ); ?></li>
		</ol>
		<p class="tip"><?php esc_html_e( 'The master pages are private and hidden from search engines, so visitors never see them. If they ever go missing or need refreshing, go to Tools, then Master pages, and press the build button.', 'site-guide' ); ?></p>
	</section>

	<section id="tips">
		<h2 data-n="09"><?php esc_html_e( 'Good habits', 'site-guide' ); ?></h2>
		<ul>
			<li><?php esc_html_e( 'Never type the coral full stop at the end of a heading. The site adds it, and a typed one would show twice.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Give every image a description (alt text) in the Media Library. It helps people using screen readers and helps search engines.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Upload photos at roughly the size they are shown, not straight from a camera. Large files slow the page down. Use JPG for photos and SVG or PNG for logos.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Keep headlines short. They are sized to fit about two or three lines.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Use the SEO box under each page to set the title and description that appear in Google.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Use Preview before you publish, and look at the page on a phone as well.', 'site-guide' ); ?></li>
		</ul>
	</section>

	<div class="signoff">
		<p class="signoff__thanks"><?php esc_html_e( 'Need something changed that is not in here?', 'site-guide' ); ?></p>
		<p><?php esc_html_e( 'Tell the person who looks after your website. Page layouts, new block types and anything that needs code are handled by your web team.', 'site-guide' ); ?></p>
	</div>

</div>
