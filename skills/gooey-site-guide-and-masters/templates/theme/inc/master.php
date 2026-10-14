<?php
/**
 * Master (example) pages: support code that runs on every request.
 *
 * Master pages are real pages and entries, built by the installer in /master (see master/README.md), that show every
 * block and template with every option filled in. They are private, noindex and flagged with the post meta _wl_master.
 * This file keeps them out of listings and search, and loads the builder and the admin screen only when needed.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta query clause that leaves out the master (example) pages and entries. Put it in any listing or related-content query
 * that could otherwise return them for a signed-in editor.
 *
 * @return array
 */
function wl_not_master() {
	return array(
		'key'     => '_wl_master',
		'compare' => 'NOT EXISTS',
	);
}

/**
 * Statuses to list for a set of parent or related posts: the master ones also show private entries (the masters are
 * private), everywhere else only published ones.
 *
 * @param int|int[] $ids Post IDs.
 * @return string|string[]
 */
function wl_master_status( $ids ) {
	foreach ( (array) $ids as $id ) {
		if ( get_post_meta( (int) $id, '_wl_master', true ) ) {
			return array( 'publish', 'private' );
		}
	}

	return 'publish';
}

/**
 * Repeater rows as a clean array. ACF returns false for an empty repeater and (array) false is one blank row.
 *
 * @param string    $name    Field name.
 * @param int|false $post_id Post ID.
 * @return array
 */
function wl_rows( $name, $post_id = false ) {
	return array_values( array_filter( (array) get_field( $name, $post_id ), 'is_array' ) );
}

add_action( 'pre_get_posts', 'wl_exclude_masters_from_listings' );

/**
 * Listings never include master entries. WordPress adds private posts to every query for a signed-in editor, so without this a
 * block that lists vacancies, case studies or insights would show the masters too. Queries for one specific post, an explicit
 * list of IDs (manual selections), the main query of a single page, attachments, menus and patterns are left alone.
 *
 * @param WP_Query $query The query.
 */
function wl_exclude_masters_from_listings( $query ) {
	if ( is_admin() ) {
		return;
	}

	if ( $query->is_main_query() && $query->is_singular() ) {
		return;
	}

	if ( $query->get( 'p' ) || $query->get( 'page_id' ) || $query->get( 'name' ) || $query->get( 'pagename' ) || $query->get( 'post__in' ) ) {
		return;
	}

	// The installer's own queries look for the master flag on purpose.
	if ( '_wl_master' === $query->get( 'meta_key' ) ) {
		return;
	}

	$types = (array) $query->get( 'post_type' );

	if ( array_intersect( $types, array( 'attachment', 'nav_menu_item', 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation', 'revision' ) ) ) {
		return;
	}

	$meta   = (array) $query->get( 'meta_query' );
	$meta[] = wl_not_master();

	if ( $query->is_search() ) {
		$meta[] = array(
			'key'     => '_wl_noindex',
			'compare' => 'NOT EXISTS',
		);
	}

	$query->set( 'meta_query', $meta );
}

add_filter( 'wp_robots', 'wl_robots_for_masters' );

/**
 * Belt and braces: master pages say noindex, nofollow (they are private as well).
 *
 * @param array $robots Robots directives.
 * @return array
 */
function wl_robots_for_masters( $robots ) {
	if ( is_singular() && get_post_meta( get_the_ID(), '_wl_noindex', true ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		unset( $robots['follow'], $robots['index'] );
	}

	return $robots;
}

/**
 * Load the master-page builder (library and installer). Only the admin screen, WP-CLI and the dev scripts need it.
 */
function wl_master_load() {
	static $loaded = false;

	if ( ! $loaded ) {
		require_once get_theme_file_path( 'master/lib.php' );
		require_once get_theme_file_path( 'master/installer.php' );
		$loaded = true;
	}
}

if ( is_admin() ) {
	require_once get_theme_file_path( 'master/admin.php' );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once get_theme_file_path( 'master/cli.php' );
}

add_filter( 'private_title_format', 'wl_master_title_format', 10, 2 );

/**
 * Master pages are private, but their titles do not need WordPress's "Private:" prefix.
 *
 * @param string  $format Title format.
 * @param WP_Post $post   Post.
 * @return string
 */
function wl_master_title_format( $format, $post ) {
	return ( $post && get_post_meta( $post->ID, '_wl_master', true ) ) ? '%s' : $format;
}
