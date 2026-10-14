<?php
/**
 * Helpers for the master (example) page builders in master/builders/. Loaded on demand by wl_master_load().
 *
 * Builders are small files that return a function. Inside it, use:
 *   wlm_blk( 'icon-cards', array( 'heading' => '...' ), 'anchor' )   one ACF block, built from the block's own field group
 *   wlm_note( 'Title', 'What it is for', array( 'option', ... ), 'Where to find it', 'block', 'anchor' )   the dashed label above an example
 *   wlm_lnk( 'Text', 'https://...' )                              a link value
 *   wlm_img( 'hero-poster' ) / wlm_imgs( 'team' )                 sample photographs (attachment IDs)
 *   wlm_page( 'slug', 'Master: Title', $content )                 a page under /master-pages/
 *   wlm_entry( 'case_study', 'master-slug', 'Master: Title', $args )  an entry of any content type
 *
 * Everything is idempotent by slug: running a builder again updates the page, it never duplicates it.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

/**
 * The ACF field group for a block, found by its location rule (so file names do not matter).
 *
 * @param string $slug Block slug, e.g. 'icon-cards'.
 * @return array|null
 */
function wlm_block_group( $slug ) {
	static $groups = null;

	if ( null === $groups ) {
		$groups = array();
		foreach ( (array) glob( get_theme_file_path( 'acf-json/group_*.json' ) ) as $file ) {
			$group = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			foreach ( (array) ( isset( $group['location'] ) ? $group['location'] : array() ) as $or ) {
				foreach ( $or as $rule ) {
					if ( isset( $rule['param'], $rule['value'] ) && 'block' === $rule['param'] && 0 === strpos( $rule['value'], 'acf/' ) ) {
						$groups[ substr( $rule['value'], 4 ) ] = $group;
					}
				}
			}
		}
	}

	return isset( $groups[ $slug ] ) ? $groups[ $slug ] : null;
}

/**
 * Flatten values into ACF block data: name => value, _name => field key, repeaters as name_0_sub, groups as name_sub.
 * Nested fields left out are stored as empty strings, like the block editor does.
 *
 * @param array  $fields Field definitions.
 * @param array  $values Values keyed by field name.
 * @param string $prefix Name prefix for nested fields.
 * @return array
 */
function wlm_flatten( array $fields, array $values, $prefix = '' ) {
	$out = array();

	foreach ( $fields as $field ) {
		$name = isset( $field['name'] ) ? $field['name'] : '';

		if ( '' === $name || 'tab' === $field['type'] ) {
			continue;
		}

		$key = $prefix . $name;

		if ( ! array_key_exists( $name, $values ) ) {
			if ( '' !== $prefix && ! in_array( $field['type'], array( 'repeater', 'group' ), true ) ) {
				$out[ $key ]       = '';
				$out[ '_' . $key ] = $field['key'];
			}
			continue;
		}

		$value = $values[ $name ];

		if ( 'repeater' === $field['type'] ) {
			$out[ $key ]       = count( $value );
			$out[ '_' . $key ] = $field['key'];
			foreach ( array_values( $value ) as $i => $row ) {
				$out = array_merge( $out, wlm_flatten( $field['sub_fields'], $row, $key . '_' . $i . '_' ) );
			}
		} elseif ( 'group' === $field['type'] ) {
			$out[ $key ]       = '';
			$out[ '_' . $key ] = $field['key'];
			$out               = array_merge( $out, wlm_flatten( $field['sub_fields'], (array) $value, $key . '_' ) );
		} else {
			$out[ $key ]       = $value;
			$out[ '_' . $key ] = $field['key'];
		}
	}

	return $out;
}

/**
 * The serialised block comment for one ACF block.
 *
 * @param string $slug   Block slug (folder name in /blocks).
 * @param array  $values Field values keyed by field name.
 * @param string $anchor Optional HTML anchor.
 * @return string
 */
function wlm_blk( $slug, $values = array(), $anchor = '' ) {
	$group = wlm_block_group( $slug );

	if ( ! $group ) {
		return '<!-- no ACF field group found for acf/' . esc_html( $slug ) . " -->\n\n";
	}

	$attrs = array(
		'name' => 'acf/' . $slug,
		'data' => wlm_flatten( $group['fields'], $values ),
		'mode' => 'preview',
	);

	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
	}

	return '<!-- wp:acf/' . $slug . ' ' . serialize_block_attributes( $attrs ) . " /-->\n\n";
}

/**
 * A master page note: the dashed label above each example. Kind is block, page or tip. Give it an anchor (a short slug) so
 * the long pages can have jump links.
 *
 * @param string          $title   Name of the block or page style.
 * @param string          $purpose What it is for.
 * @param string|string[] $options The options shown beneath (one per line).
 * @param string          $where   Where to find it in the editor.
 * @param string          $kind    block | page | tip.
 * @param string          $anchor  HTML anchor.
 * @return string
 */
function wlm_note( $title, $purpose, $options = array(), $where = '', $kind = 'block', $anchor = '' ) {
	return wlm_blk(
		'master-note',
		array(
			'kind'    => $kind,
			'title'   => $title,
			'purpose' => $purpose,
			'options' => implode( "\n", (array) $options ),
			'where'   => $where,
		),
		$anchor
	);
}

/**
 * A link value for a link field.
 *
 * @param string $title  Link text.
 * @param string $url    URL.
 * @param string $target Optional target.
 * @return array
 */
function wlm_lnk( $title, $url, $target = '' ) {
	return array(
		'title'  => $title,
		'url'    => $url,
		'target' => $target,
	);
}

/**
 * Attachment IDs of the sample photographs (see master/media/manifest.json), optionally one theme only:
 * hero, service, project, people, sector, feature, insight, team, careers, avatar, logo.
 *
 * @param string $theme Theme, or '' for all.
 * @return int[]
 */
function wlm_imgs( $theme = '' ) {
	$map = wlm_media_map();
	$out = array();

	foreach ( wlm_media_manifest() as $m ) {
		if ( ( '' === $theme || $m['theme'] === $theme ) && ! empty( $map[ $m['key'] ] ) ) {
			$out[] = (int) $map[ $m['key'] ];
		}
	}

	return $out;
}

/**
 * Attachment ID of one sample photograph by key (its file name without the extension), or 0.
 *
 * @param string $key e.g. 'hero-poster'.
 * @return int
 */
function wlm_img( $key ) {
	$map = wlm_media_map();

	return isset( $map[ $key ] ) ? (int) $map[ $key ] : 0;
}

/**
 * The media manifest shipped with the theme.
 *
 * @return array
 */
function wlm_media_manifest() {
	static $manifest = null;

	if ( null === $manifest ) {
		$manifest = (array) json_decode( (string) file_get_contents( get_theme_file_path( 'master/media/manifest.json' ) ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return $manifest;
}

/**
 * The map of media key => attachment ID, kept in an option by the installer.
 *
 * @return array
 */
function wlm_media_map() {
	return (array) get_option( 'wl_master_media', array() );
}

/**
 * The parent page that holds every master page: /master-pages/.
 *
 * @return int
 */
function wlm_parent() {
	$page = get_page_by_path( 'master-pages' );

	if ( $page ) {
		return (int) $page->ID;
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Master pages',
			'post_name'   => 'master-pages',
		)
	);
	wlm_mark( $id, 'Master pages' );

	return (int) $id;
}

/**
 * Create or update a master page under /master-pages/. Idempotent by slug.
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title, e.g. "Master: Content blocks".
 * @param string $content Block markup (concatenate wlm_blk() and wlm_note() calls).
 * @param array  $extra   Other wp_insert_post arguments, and 'meta' => array of post meta.
 * @return int
 */
function wlm_page( $slug, $title, $content, $extra = array() ) {
	$existing = get_page_by_path( 'master-pages/' . $slug );
	$meta     = isset( $extra['meta'] ) ? $extra['meta'] : array();
	unset( $extra['meta'] );

	$args = array_merge(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_parent'  => wlm_parent(),
			'post_content' => $content,
		),
		$extra
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		wp_update_post( wp_slash( $args ) );
		$id = (int) $existing->ID;
	} else {
		$id = (int) wp_insert_post( wp_slash( $args ) );
	}

	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}

	wlm_mark( $id, $title );

	return $id;
}

/**
 * Create or update a master entry of any content type (case study, service, career, insight...), by type and slug.
 *
 * @param string $type    Post type.
 * @param string $slug    Slug (use a "master-" prefix).
 * @param string $title   Title ("Master: ...").
 * @param array  $args    wp_insert_post arguments, plus 'fields' => ACF fields, 'terms' => array( taxonomy => names ),
 *                        'thumbnail' => attachment ID, 'meta' => post meta.
 * @return int
 */
function wlm_entry( $type, $slug, $title, $args = array() ) {
	$fields    = isset( $args['fields'] ) ? $args['fields'] : array();
	$terms     = isset( $args['terms'] ) ? $args['terms'] : array();
	$thumbnail = isset( $args['thumbnail'] ) ? (int) $args['thumbnail'] : 0;
	$meta      = isset( $args['meta'] ) ? $args['meta'] : array();
	unset( $args['fields'], $args['terms'], $args['thumbnail'], $args['meta'] );

	$existing = get_page_by_path( $slug, OBJECT, $type );
	$post     = array_merge(
		array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => '',
		),
		$args
	);

	if ( $existing ) {
		$post['ID'] = $existing->ID;
		wp_update_post( wp_slash( $post ) );
		$id = (int) $existing->ID;
	} else {
		$id = (int) wp_insert_post( wp_slash( $post ) );
	}

	foreach ( $fields as $name => $value ) {
		update_field( $name, $value, $id );
	}

	foreach ( $terms as $taxonomy => $names ) {
		wp_set_object_terms( $id, (array) $names, $taxonomy );
	}

	if ( $thumbnail ) {
		set_post_thumbnail( $id, $thumbnail );
	}

	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}

	wlm_mark( $id, $title );

	return $id;
}

/**
 * Flag a post as a master: noindex, hidden from listings and search, and recorded in the index.
 *
 * @param int    $id    Post ID.
 * @param string $label Title for the hub.
 */
function wlm_mark( $id, $label = '' ) {
	update_post_meta( $id, '_wl_noindex', 1 );
	update_post_meta( $id, '_wl_master', 1 );

	$all = (array) get_option( 'wl_master_index', array() );

	$all[ (string) $id ] = array(
		'id'    => (int) $id,
		'title' => $label ? $label : get_the_title( $id ),
		'type'  => get_post_type( $id ),
	);

	update_option( 'wl_master_index', $all, false );
}

/**
 * A page as the visitor sees it, fetched with the first administrator's login cookie so private pages work.
 *
 * @param string $url URL.
 * @return array array( http_code, html ).
 */
function wlm_fetch( $url ) {
	$admins = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => 'ID',
		)
	);
	$uid    = $admins ? (int) $admins[0] : 1;
	$cookie = LOGGED_IN_COOKIE . '=' . wp_generate_auth_cookie( $uid, time() + 3600, 'logged_in' );
	$resp   = wp_remote_get(
		$url,
		array(
			'timeout'   => 90,
			'sslverify' => false,
			'headers'   => array( 'Cookie' => $cookie ),
		)
	);

	if ( is_wp_error( $resp ) ) {
		return array( 0, '' );
	}

	return array( (int) wp_remote_retrieve_response_code( $resp ), (string) wp_remote_retrieve_body( $resp ) );
}

/**
 * Check a rendered master: HTTP 200, no PHP notices, no junk text. Returns a one-line result.
 *
 * @param int $id Post ID.
 * @return string
 */
function wlm_check( $id ) {
	list( $code, $html ) = wlm_fetch( get_permalink( $id ) );
	$junk                = preg_match( '/(Warning|Notice|Fatal error|Deprecated):|u003c|&lt;p&gt;|Lorem ipsum/', $html );

	return ( 200 === $code && ! $junk ? 'OK   ' : 'FAIL ' ) . $code . ' ' . get_the_title( $id ) . ' ' . get_permalink( $id ) . ( $junk ? '  (notice or junk text in output)' : '' );
}

/*
 * Core block markup helpers: write paragraphs, headings, lists and tables exactly as WordPress saves them, so the editor
 * never reports an invalid block. Text passed in is trusted HTML (use esc_html() on anything that is not).
 */

/**
 * A core heading block.
 *
 * @param string $html  Heading text (HTML).
 * @param int    $level 2 to 4.
 * @param string $id    Optional anchor.
 * @return string
 */
function wlm_h( $html, $level = 2, $id = '' ) {
	$attrs = array( 'level' => $level );
	$idatt = '';

	if ( $id ) {
		$attrs['anchor'] = $id;
		$idatt           = ' id="' . esc_attr( $id ) . '"';
	}

	return '<!-- wp:heading ' . wp_json_encode( $attrs ) . " -->\n<h" . $level . $idatt . ' class="wp-block-heading">' . $html . '</h' . $level . ">\n<!-- /wp:heading -->\n\n";
}

/**
 * A core paragraph block.
 *
 * @param string $html Paragraph text (HTML).
 * @return string
 */
function wlm_p( $html ) {
	return "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->\n\n";
}

/**
 * A core list block.
 *
 * @param string[] $items   List item HTML.
 * @param bool     $ordered Numbered list.
 * @return string
 */
function wlm_list( array $items, $ordered = false ) {
	$tag   = $ordered ? 'ol' : 'ul';
	$open  = $ordered ? '<!-- wp:list {"ordered":true} -->' : '<!-- wp:list -->';
	$inner = '';

	foreach ( $items as $item ) {
		$inner .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
	}

	return $open . "\n<" . $tag . ' class="wp-block-list">' . $inner . '</' . $tag . ">\n<!-- /wp:list -->\n\n";
}

/**
 * A core table block.
 *
 * @param string[]   $head Header cell HTML.
 * @param string[][] $rows Rows of cell HTML.
 * @return string
 */
function wlm_table( array $head, array $rows ) {
	$html = '<figure class="wp-block-table"><table><thead><tr>';

	foreach ( $head as $cell ) {
		$html .= '<th>' . $cell . '</th>';
	}

	$html .= '</tr></thead><tbody>';

	foreach ( $rows as $row ) {
		$html .= '<tr>';
		foreach ( $row as $cell ) {
			$html .= '<td>' . $cell . '</td>';
		}
		$html .= '</tr>';
	}

	return "<!-- wp:table -->\n" . $html . "</tbody></table></figure>\n<!-- /wp:table -->\n\n";
}
