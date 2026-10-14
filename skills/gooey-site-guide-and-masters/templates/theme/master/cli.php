<?php
/**
 * WP-CLI: wp {{THEME_SLUG}} masters <build|private|publish|remove|status>
 *
 *   wp {{THEME_SLUG}} masters build [--seed] [--only=<text>] [--publish]
 *   wp {{THEME_SLUG}} masters private
 *   wp {{THEME_SLUG}} masters publish
 *   wp {{THEME_SLUG}} masters remove [--with-sample]
 *   wp {{THEME_SLUG}} masters status
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WL_Master_CLI' ) ) {
	/**
	 * Master pages commands.
	 */
	class WL_Master_CLI {

		/**
		 * Build, refresh, hide, publish, remove or list the master pages.
		 *
		 * ## OPTIONS
		 *
		 * <action>
		 * : build, private, publish, remove or status.
		 *
		 * [--seed]
		 * : With build: also create the sample content (only entries that do not exist).
		 *
		 * [--only=<text>]
		 * : With build: only run builder files whose name contains this text.
		 *
		 * [--publish]
		 * : With build: leave the masters published (for previewing) instead of private.
		 *
		 * [--with-sample]
		 * : With remove: also delete the sample content and photographs.
		 *
		 * @param array $args       Positional arguments.
		 * @param array $assoc_args Flags.
		 */
		public function masters( $args, $assoc_args ) {
			wl_master_load();

			$action = isset( $args[0] ) ? $args[0] : 'status';

			if ( 'build' === $action ) {
				if ( ! empty( $assoc_args['seed'] ) ) {
					WP_CLI::log( 'Sample content created: ' . wp_json_encode( wlm_seed_content() ) );
				}
				foreach ( wlm_build( isset( $assoc_args['only'] ) ? $assoc_args['only'] : '', empty( $assoc_args['publish'] ) ) as $line ) {
					WP_CLI::log( $line );
				}
				WP_CLI::success( 'Master pages built.' );
			} elseif ( 'private' === $action || 'publish' === $action ) {
				WP_CLI::success( wlm_set_visibility( $action ) . ' set to ' . $action );
			} elseif ( 'remove' === $action ) {
				WP_CLI::success( wlm_remove( ! empty( $assoc_args['with-sample'] ) ) . ' removed' );
			} else {
				foreach ( wlm_status() as $row ) {
					WP_CLI::log( $row['status'] . '  ' . $row['type'] . '  ' . $row['title'] . '  ' . $row['url'] );
				}
			}
		}
	}

	WP_CLI::add_command( '{{THEME_SLUG}}', 'WL_Master_CLI' );
}
