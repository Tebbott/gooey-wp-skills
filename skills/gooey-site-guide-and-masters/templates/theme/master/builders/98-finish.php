<?php
/**
 * Final touches on the long block pages: a "Blocks on this page" jump-link list built from the master notes' anchors.
 * Safe to re-run (it replaces the list it added before).
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

return function () {
	$done = array();

	foreach ( array( 'content-blocks', 'cards-and-proof', 'people-forms-and-contact' ) as $slug ) {
		$page = get_page_by_path( 'master-pages/' . $slug );

		if ( ! $page ) {
			continue;
		}

		$blocks = array_values(
			array_filter(
				parse_blocks( $page->post_content ),
				function ( $b ) {
					return ! ( isset( $b['attrs']['className'] ) && 'wlm-jump' === $b['attrs']['className'] );
				}
			)
		);

		$items = array();

		foreach ( $blocks as $b ) {
			if ( 'acf/master-note' !== $b['blockName'] ) {
				continue;
			}

			$anchor = isset( $b['attrs']['anchor'] ) ? $b['attrs']['anchor'] : '';
			$title  = isset( $b['attrs']['data']['title'] ) ? $b['attrs']['data']['title'] : '';
			$kind   = isset( $b['attrs']['data']['kind'] ) ? $b['attrs']['data']['kind'] : '';

			if ( $anchor && $title && 'block' === $kind ) {
				$items[] = '<a href="#' . esc_attr( $anchor ) . '">' . esc_html( $title ) . '</a>';
			}
		}

		if ( ! $items ) {
			continue;
		}

		$group = "<!-- wp:group {\"className\":\"wlm-jump\"} -->\n<div class=\"wp-block-group wlm-jump\">"
			. trim( wlm_h( 'Blocks on this page' ) ) . "\n\n"
			. trim( wlm_list( $items ) )
			. "</div>\n<!-- /wp:group -->\n\n";

		// Straight after the first block (the note that opens the page).
		$out   = '';
		$added = false;

		foreach ( $blocks as $b ) {
			$out .= serialize_block( $b ) . "\n\n";

			if ( ! $added && $b['blockName'] ) {
				$out  .= $group;
				$added = true;
			}
		}

		wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_content' => $out ) ) );
		$done[] = $slug . ' (' . count( $items ) . ')';
	}

	return 'jump links on ' . ( $done ? implode( ', ', $done ) : 'no pages' );
};
