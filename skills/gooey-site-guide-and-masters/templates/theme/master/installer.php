<?php
/**
 * The master-page installer. Runs from Tools > Master pages, from WP-CLI (wp {{THEME_SLUG}} masters ...) or from a script.
 * Everything here is idempotent: run it again any time to refresh the masters.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

/**
 * Import the sample photographs from master/media into the media library (once each) and remember their IDs.
 *
 * @return array Map of key => attachment ID.
 */
function wlm_media_import() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$map = wlm_media_map();

	foreach ( wlm_media_manifest() as $m ) {
		$key = $m['key'];

		if ( ! empty( $map[ $key ] ) && get_post( (int) $map[ $key ] ) ) {
			continue;
		}

		$found = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'meta_key'       => '_wlm_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $m['file'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		if ( $found ) {
			$map[ $key ] = (int) $found[0];
			continue;
		}

		$path = get_theme_file_path( 'master/media/' . $m['file'] );
		$tmp  = wp_tempnam( $m['file'] );

		if ( ! is_readable( $path ) || ! copy( $path, $tmp ) ) {
			continue;
		}

		$id = media_handle_sideload(
			array(
				'name'     => $m['file'],
				'tmp_name' => $tmp,
			),
			0,
			$m['alt']
		);

		if ( is_wp_error( $id ) ) {
			continue;
		}

		update_post_meta( $id, '_wlm_source', $m['file'] );
		update_post_meta( $id, '_wp_attachment_image_alt', $m['alt'] );
		$map[ $key ] = (int) $id;
	}

	update_option( 'wl_master_media', $map, false );

	return $map;
}

/**
 * Create the sample content (services, testimonials, team, case studies, careers, FAQs, insights) from master/seed/content.json,
 * but only the entries that do not already exist (matched by type and slug). Entries it creates are flagged _wlm_seed so they
 * can be removed again.
 *
 * @return array Counts created, by type.
 */
function wlm_seed_content() {
	wlm_media_import();

	$rows    = (array) json_decode( (string) file_get_contents( get_theme_file_path( 'master/seed/content.json' ) ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$order   = array( 'testimonial', 'team_member', 'service', 'faq', 'career', 'post', 'case_study' );
	$created = array();

	// Insight formats and topics must exist before posts use them.
	if ( function_exists( 'wl_apply_site_settings' ) ) {
		wl_apply_site_settings();
	}

	$ref = function ( $value ) {
		if ( ! is_string( $value ) || false === strpos( $value, ':' ) ) {
			return 0;
		}
		list( $type, $slug ) = explode( ':', $value, 2 );
		$post                = get_page_by_path( $slug, OBJECT, $type );

		return $post ? (int) $post->ID : 0;
	};

	foreach ( $order as $type ) {
		foreach ( $rows as $row ) {
			if ( $row['type'] !== $type || get_page_by_path( $row['slug'], OBJECT, $type ) ) {
				continue;
			}

			$date = gmdate( 'Y-m-d H:i:s', time() - (int) $row['days_ago'] * DAY_IN_SECONDS );
			$id   = wp_insert_post(
				wp_slash(
					array(
						'post_type'     => $type,
						'post_status'   => 'publish',
						'post_name'     => $row['slug'],
						'post_title'    => $row['title'],
						'post_excerpt'  => $row['excerpt'],
						'post_content'  => $row['content'],
						'menu_order'    => (int) $row['menu_order'],
						'post_date'     => get_date_from_gmt( $date ),
						'post_date_gmt' => $date,
					)
				)
			);

			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}

			update_post_meta( $id, '_wlm_seed', 1 );

			if ( ! empty( $row['thumbnail'] ) && wlm_img( pathinfo( $row['thumbnail'], PATHINFO_FILENAME ) ) ) {
				set_post_thumbnail( $id, wlm_img( pathinfo( $row['thumbnail'], PATHINFO_FILENAME ) ) );
			}

			foreach ( (array) $row['terms'] as $taxonomy => $names ) {
				if ( taxonomy_exists( $taxonomy ) ) {
					wp_set_object_terms( $id, $names, $taxonomy );
				}
			}

			foreach ( (array) $row['fields'] as $name => $value ) {
				$field = function_exists( 'acf_get_field' ) ? acf_get_field( $name ) : null;
				$kind  = $field ? $field['type'] : '';

				if ( 'image' === $kind ) {
					$value = wlm_img( pathinfo( (string) $value, PATHINFO_FILENAME ) );
				} elseif ( 'post_object' === $kind ) {
					$value = $ref( $value );
				} elseif ( 'relationship' === $kind ) {
					$value = array_values( array_filter( array_map( $ref, (array) $value ) ) );
				}

				if ( $value ) {
					update_field( $name, $value, $id );
				}
			}

			$created[ $type ] = ( isset( $created[ $type ] ) ? $created[ $type ] : 0 ) + 1;
		}
	}

	// Services create their matching service-area terms when saved; make sure every one exists for the case studies.
	foreach ( get_posts(
		array(
			'post_type'   => 'service',
			'numberposts' => -1,
		)
	) as $service ) {
		if ( function_exists( 'wl_sync_service_term' ) ) {
			wl_sync_service_term( $service->ID, $service );
		}
	}

	if ( function_exists( 'wl_reindex_facets' ) ) {
		wl_reindex_facets();
	}

	return $created;
}

/**
 * The builder files, in order.
 *
 * @return string[] Absolute paths.
 */
function wlm_builder_files() {
	$files = (array) glob( get_theme_file_path( 'master/builders/*.php' ) );
	sort( $files );

	return $files;
}

/**
 * Run the builders (all, or the ones whose file name contains $only), then make everything private.
 *
 * @param string $only    Optional text the builder file name must contain (e.g. "03").
 * @param bool   $private Whether to make the masters private afterwards.
 * @return string[] Messages.
 */
function wlm_build( $only = '', $private = true ) {
	@set_time_limit( 0 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	$log = array();

	wlm_media_import();
	wlm_parent();

	foreach ( wlm_builder_files() as $file ) {
		if ( '' !== $only && false === strpos( basename( $file ), $only ) ) {
			continue;
		}

		$run = include $file;

		if ( is_callable( $run ) ) {
			$out   = call_user_func( $run );
			$log[] = basename( $file ) . ': ' . ( is_string( $out ) ? $out : 'done' );
		}
	}

	if ( $private ) {
		wlm_set_visibility( 'private' );
	}

	return $log;
}

/**
 * Make every master page and entry private (only signed-in editors see them) or published (for previewing).
 *
 * @param string $status private | publish.
 * @return int Number changed.
 */
function wlm_set_visibility( $status ) {
	global $wpdb;

	$status = 'publish' === $status ? 'publish' : 'private';
	$ids    = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wl_master' AND meta_value = '1'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$n      = 0;

	// Children first, so a private parent never hides a child during the change.
	rsort( $ids );

	foreach ( $ids as $id ) {
		$post = get_post( (int) $id );

		if ( ! $post || 'trash' === $post->post_status || $post->post_status === $status ) {
			continue;
		}

		wp_update_post( array( 'ID' => (int) $id, 'post_status' => $status ) );
		$n++;
	}

	return $n;
}

/**
 * Delete every master page and entry, and optionally the sample content the installer created.
 *
 * @param bool $with_sample Also remove the sample content (entries flagged _wlm_seed) and the sample photographs.
 * @return int Number deleted.
 */
function wlm_remove( $with_sample = false ) {
	global $wpdb;

	$keys = $with_sample ? "('_wl_master','_wlm_seed')" : "('_wl_master')";
	$ids  = $wpdb->get_col( "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key IN $keys" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$n    = 0;

	foreach ( $ids as $id ) {
		if ( get_post( (int) $id ) && wp_delete_post( (int) $id, true ) ) {
			$n++;
		}
	}

	delete_option( 'wl_master_index' );

	if ( $with_sample ) {
		foreach ( wlm_media_map() as $att ) {
			wp_delete_attachment( (int) $att, true );
		}
		delete_option( 'wl_master_media' );
	}

	return $n;
}

/**
 * Summary for the admin screen: the masters, by status.
 *
 * @return array
 */
function wlm_status() {
	$ids = get_posts(
		array(
			'post_type'      => array_values( get_post_types() ), // 'any' skips types that are not searchable (proposals).
			'post_status'    => array( 'publish', 'private', 'draft' ),
			'posts_per_page' => -1,
			'meta_key'       => '_wl_master', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);

	$rows = array();

	foreach ( $ids as $id ) {
		$rows[] = array(
			'id'     => (int) $id,
			'title'  => get_the_title( $id ),
			'type'   => get_post_type( $id ),
			'status' => get_post_status( $id ),
			'url'    => get_permalink( $id ),
		);
	}

	return $rows;
}
