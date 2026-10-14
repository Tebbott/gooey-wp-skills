<?php
/**
 * Tools > Master pages: build, refresh, hide or remove the master (example) pages from the admin.
 *
 * @package WL
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'wlm_admin_menu' );
add_action( 'admin_post_wlm_action', 'wlm_admin_handle' );

/**
 * Add the screen under Tools.
 */
function wlm_admin_menu() {
	add_management_page(
		__( 'Master pages', 'wl' ),
		__( 'Master pages', 'wl' ),
		'manage_options',
		'wl-master-pages',
		'wlm_admin_render'
	);
}

/**
 * Handle the buttons.
 */
function wlm_admin_handle() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wlm_action' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'wl' ) );
	}

	wl_master_load();

	$do      = isset( $_POST['do'] ) ? sanitize_key( wp_unslash( $_POST['do'] ) ) : '';
	$message = '';

	if ( 'build' === $do ) {
		$seed = ! empty( $_POST['seed'] );
		$bits = array();

		if ( $seed ) {
			$created = wlm_seed_content();
			$bits[]  = $created ? sprintf( /* translators: %s: summary such as "service 5, case_study 10" */ __( 'Sample content added (%s).', 'wl' ), implode( ', ', array_map( function ( $k, $v ) {
				return $k . ' ' . $v;
			}, array_keys( $created ), $created ) ) ) : __( 'Sample content was already there.', 'wl' );
		}

		$log     = wlm_build();
		$bits[]  = sprintf( /* translators: %d: number of builder files run */ _n( '%d builder ran.', '%d builders ran.', count( $log ), 'wl' ), count( $log ) );
		$bits[]  = __( 'The master pages are private: only signed-in editors can see them.', 'wl' );
		$message = implode( ' ', $bits );
	} elseif ( 'private' === $do || 'publish' === $do ) {
		$n       = wlm_set_visibility( $do );
		$message = sprintf( /* translators: 1: number, 2: status */ __( '%1$d master pages set to %2$s.', 'wl' ), $n, $do );
	} elseif ( 'remove' === $do ) {
		$n       = wlm_remove( ! empty( $_POST['with_sample'] ) );
		$message = sprintf( /* translators: %d: number removed */ __( '%d items removed.', 'wl' ), $n );
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'wl-master-pages', 'wlm_msg' => rawurlencode( $message ) ), admin_url( 'tools.php' ) ) );
	exit;
}

/**
 * The screen.
 */
function wlm_admin_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	wl_master_load();

	$rows = wlm_status();
	$msg  = isset( $_GET['wlm_msg'] ) ? sanitize_text_field( wp_unslash( rawurldecode( $_GET['wlm_msg'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$form = function ( $do, $label, $class = 'button', $extra = '' ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="display:inline-block;margin:0 8px 8px 0">';
		echo '<input type="hidden" name="action" value="wlm_action"><input type="hidden" name="do" value="' . esc_attr( $do ) . '">';
		wp_nonce_field( 'wlm_action' );
		echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static markup below.
		echo '<button class="' . esc_attr( $class ) . '" type="submit">' . esc_html( $label ) . '</button></form>';
	};
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Master pages', 'wl' ); ?></h1>
		<?php if ( $msg ) { ?>
			<div class="notice notice-success"><p><?php echo esc_html( $msg ); ?></p></div>
		<?php } ?>
		<p><?php esc_html_e( 'Example pages that show every block and every content template with every option filled in. They are private (only signed-in editors see them), kept out of search and listings, and built entirely from code, so you can rebuild them on any copy of this site without importing a database.', 'wl' ); ?></p>
		<p><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'master-pages' ) ) ); ?>"><?php esc_html_e( 'Open the master pages hub', 'wl' ); ?></a></p>

		<h2><?php esc_html_e( 'Build or refresh', 'wl' ); ?></h2>
		<?php
		$form(
			'build',
			__( 'Build / refresh the master pages', 'wl' ),
			'button button-primary',
			'<p><label><input type="checkbox" name="seed" value="1"> ' . esc_html__( 'Also add the sample content (services, case studies, insights, team, careers, testimonials and their photographs). Only needed on an empty site; existing entries are never changed.', 'wl' ) . '</label></p>'
		);
		?>
		<p class="description"><?php esc_html_e( 'The build can take a minute. Running it again updates the pages and never duplicates them.', 'wl' ); ?></p>

		<h2><?php esc_html_e( 'Visibility', 'wl' ); ?></h2>
		<?php
		$form( 'private', __( 'Make all private', 'wl' ) );
		$form( 'publish', __( 'Publish all (to preview them)', 'wl' ) );
		?>

		<h2><?php esc_html_e( 'Remove', 'wl' ); ?></h2>
		<?php
		$form(
			'remove',
			__( 'Remove the master pages', 'wl' ),
			'button',
			'<p><label><input type="checkbox" name="with_sample" value="1"> ' . esc_html__( 'Also remove the sample content and photographs the installer added', 'wl' ) . '</label></p>'
		);
		?>

		<h2><?php echo esc_html( sprintf( /* translators: %d: number of master pages */ __( 'Current masters (%d)', 'wl' ), count( $rows ) ) ); ?></h2>
		<?php if ( $rows ) { ?>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Title', 'wl' ); ?></th><th><?php esc_html_e( 'Type', 'wl' ); ?></th><th><?php esc_html_e( 'Status', 'wl' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $rows as $row ) { ?>
						<tr>
							<td><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a></td>
							<td><?php echo esc_html( $row['type'] ); ?></td>
							<td><?php echo esc_html( $row['status'] ); ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<p><?php esc_html_e( 'No master pages yet. Press the build button above.', 'wl' ); ?></p>
		<?php } ?>
	</div>
	<?php
}
