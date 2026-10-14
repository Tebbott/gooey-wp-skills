<?php
/**
 * The Site Guide page for this website: how the blocks, example pages and content types work and how they connect.
 *
 * SKELETON. Replace every {{...}} and every "EXAMPLE" row with this site's real content, following
 * reference/guide-writing.md in the gooey-site-guide-and-masters skill. Keep the section ids, numbers and the CSS classes.
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
		<p class="lede"><?php esc_html_e( 'Every page is built from blocks stacked one after another. Each block is one section of the page with its own fields to fill in. You change content, not layout, so the design stays consistent.', 'site-guide' ); ?></p>

		<div class="three">
			<div class="card">
				<b><?php esc_html_e( 'Pages', 'site-guide' ); ?></b>
				<p><?php esc_html_e( '{{List this site\'s pages in a few words, e.g. Home, About, Services, Contact. You build them by adding blocks.}}', 'site-guide' ); ?></p>
			</div>
			<div class="card">
				<b><?php esc_html_e( 'Content types', 'site-guide' ); ?></b>
				<p><?php esc_html_e( '{{Things you add many of (list them). Each has its own page template, so you only fill in the content.}}', 'site-guide' ); ?></p>
			</div>
			<div class="card">
				<b><?php esc_html_e( 'Site-wide settings', 'site-guide' ); ?></b>
				<p><?php esc_html_e( '{{The logo, contact details, social links, footer, menus. Change them once and every page updates.}}', 'site-guide' ); ?></p>
			</div>
		</div>

		<p class="tip"><?php esc_html_e( '{{One real example of a block that fills itself from a content type, e.g. adding a case study updates the "Selected projects" block.}}', 'site-guide' ); ?></p>
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
				<!-- EXAMPLE ROW: one per content type. Link the name to its admin list. -->
				<tr>
					<td><a class="path" href="<?php echo $sg_admin( 'edit.php?post_type={{post_type}}' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?>"><?php esc_html_e( '{{Name}}', 'site-guide' ); ?></a></td>
					<td><?php esc_html_e( '{{What it is and which tags or fields must be set.}}', 'site-guide' ); ?></td>
					<td><?php esc_html_e( '{{Which pages and blocks show it.}}', 'site-guide' ); ?></td>
				</tr>
				<tr class="grp"><td colspan="3"><?php esc_html_e( 'Behind the scenes', 'site-guide' ); ?></td></tr>
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
				<!-- EXAMPLE ROW: one per block, in the order of the inserter. Names and descriptions from block.json. -->
				<tr><td><?php esc_html_e( '{{Block title}}', 'site-guide' ); ?></td><td><?php esc_html_e( '{{One plain sentence.}}', 'site-guide' ); ?></td></tr>
				<tr class="grp"><td colspan="2"><?php esc_html_e( 'Sections', 'site-guide' ); ?></td></tr>
				<tr class="grp"><td colspan="2"><?php esc_html_e( 'Closing blocks', 'site-guide' ); ?></td></tr>
			</tbody>
		</table>
	</section>

	<section id="shared">
		<h2 data-n="04"><?php esc_html_e( 'Shared content', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Some things are edited once and appear in several places.', 'site-guide' ); ?></p>
		<ul>
			<li><?php esc_html_e( '{{Theme Options: where it is in the menu and what it controls (logo, contact details, social links, footer).}}', 'site-guide' ); ?></li>
			<li><?php esc_html_e( '{{Synced patterns / reusable content, e.g. the call-to-action strip: where to edit it.}}', 'site-guide' ); ?></li>
			<li><?php esc_html_e( '{{Menus: Appearance, then Menus.}}', 'site-guide' ); ?></li>
		</ul>
	</section>

	<section id="tasks">
		<h2 data-n="05"><?php esc_html_e( 'Common jobs', 'site-guide' ); ?></h2>
		<h3><?php esc_html_e( '{{Add a ...}}', 'site-guide' ); ?></h3>
		<ol>
			<li><?php esc_html_e( '{{Click ... then Add New.}}', 'site-guide' ); ?></li>
			<li><?php esc_html_e( '{{Fill in the boxes under the editor: ...}}', 'site-guide' ); ?></li>
			<li><?php esc_html_e( '{{Choose ... then Publish.}}', 'site-guide' ); ?></li>
		</ol>
		<h3><?php esc_html_e( 'Change the order of anything', 'site-guide' ); ?></h3>
		<p><?php esc_html_e( '{{How ordering works on this site (Order box, drag in the list, or by date).}}', 'site-guide' ); ?></p>
	</section>

	<section id="listings">
		<h2 data-n="06"><?php esc_html_e( 'Listings and filters', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( '{{How the listing pages fill themselves and what their filters use.}}', 'site-guide' ); ?></p>
		<p class="tip"><?php esc_html_e( '{{Why an item might not show: draft, missing tag, wrong date.}}', 'site-guide' ); ?></p>
	</section>

	<section id="forms">
		<h2 data-n="07"><?php esc_html_e( 'Forms', 'site-guide' ); ?></h2>
		<p class="lede"><?php esc_html_e( '{{Which forms exist, where they are used, where entries go.}}', 'site-guide' ); ?></p>
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
			<li><?php esc_html_e( '{{Anything the theme adds in code, so editors must not type it (e.g. the coral full stop on headings).}}', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Give every image a description (alt text) in the Media Library. It helps people using screen readers and helps search engines.', 'site-guide' ); ?></li>
			<li><?php esc_html_e( 'Upload photos at roughly the size they are shown, not straight from a camera. Use JPG for photos and SVG or PNG for logos.', 'site-guide' ); ?></li>
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
