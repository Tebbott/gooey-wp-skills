<?php
/**
 * Plugin Name: Site Guide
 * Description: Adds a "Site Guide" link to the WordPress menu: a plain-English guide to how this website's blocks, example pages and content types work, and how they connect.
 * Version:     1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: site-guide
 *
 * @package SiteGuide
 */

defined( 'ABSPATH' ) || exit;

define( 'SITE_GUIDE_VERSION', '1.0.0' );
define( 'SITE_GUIDE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SITE_GUIDE_URL', plugin_dir_url( __FILE__ ) );
define( 'SITE_GUIDE_SLUG', 'site-guide' );

add_action( 'admin_menu', 'site_guide_menu' );
add_action( 'admin_enqueue_scripts', 'site_guide_styles' );
add_action( 'wp_dashboard_setup', 'site_guide_dashboard_widget' );

/**
 * Add the Site Guide to the left-hand menu, just under the Dashboard. Anyone who can edit pages can read it.
 */
function site_guide_menu() {
	add_menu_page(
		__( 'Site Guide', 'site-guide' ),
		__( 'Site Guide', 'site-guide' ),
		'edit_pages',
		SITE_GUIDE_SLUG,
		'site_guide_render',
		'dashicons-welcome-learn-more',
		3
	);
}

/**
 * Load the guide's styles on its own screen and on the Dashboard (for the guide panel).
 *
 * @param string $hook_suffix The current admin screen.
 */
function site_guide_styles( $hook_suffix ) {
	if ( 'toplevel_page_' . SITE_GUIDE_SLUG !== $hook_suffix && 'index.php' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_style( 'site-guide', SITE_GUIDE_URL . 'assets/site-guide.css', array(), SITE_GUIDE_VERSION );
}

/**
 * The client's logo for the guide's cover, as an inline picture (assets/logo.png in this plugin), so it shows wherever the plugin is
 * installed. An empty string when there is no logo file. Print it with esc_attr(), not esc_url().
 *
 * @return string
 */
function site_guide_logo() {
	$file = SITE_GUIDE_DIR . 'assets/logo.png';

	if ( ! is_readable( $file ) ) {
		return '';
	}

	return 'data:image/png;base64,' . base64_encode( (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- a small file shipped inside this plugin.
}

/**
 * Show the guide.
 */
function site_guide_render() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to see this page.', 'site-guide' ) );
	}

	$logo = site_guide_logo();

	echo '<div class="wrap sg-wrap"><h1 class="screen-reader-text">' . esc_html__( 'Site Guide', 'site-guide' ) . '</h1>';
	include SITE_GUIDE_DIR . 'views/guide.php';
	echo '</div>';
}

/**
 * A clickable panel at the top of the Dashboard that opens the guide.
 */
function site_guide_dashboard_widget() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	wp_add_dashboard_widget( 'site_guide_widget', __( 'Website guide', 'site-guide' ), 'site_guide_widget_render' );

	// Put it first in the left-hand column.
	global $wp_meta_boxes;
	$normal = $wp_meta_boxes['dashboard']['normal']['core'];
	$ours   = array( 'site_guide_widget' => $normal['site_guide_widget'] );

	$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, array_diff_key( $normal, $ours ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- the documented way to reorder Dashboard widgets.
}

/**
 * The panel: one big link, drawn like the guide's cover.
 */
function site_guide_widget_render() {
	$url  = admin_url( 'admin.php?page=' . SITE_GUIDE_SLUG );
	$logo = site_guide_logo();

	echo '<a class="sg-banner" href="' . esc_url( $url ) . '">';
	if ( $logo ) {
		echo '<img class="sg-banner__logo" src="' . esc_attr( $logo ) . '" alt="">';
	}
	echo '<span class="sg-banner__kicker">' . esc_html__( 'Website guide', 'site-guide' ) . '</span>';
	echo '<span class="sg-banner__title">' . esc_html__( 'How your website works', 'site-guide' ) . '</span>';
	echo '<span class="sg-banner__text">' . esc_html__( 'Blocks, example pages and content types, and how they connect.', 'site-guide' ) . '</span>';
	echo '<span class="sg-banner__go">' . esc_html__( 'Open the guide', 'site-guide' ) . '<span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span></span>';
	echo '</a>';
}
