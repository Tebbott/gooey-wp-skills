<?php
/**
 * Master page note block: a label on the example (master) pages. Names a block or page style, says what it is for and
 * lists the options shown beneath it. Not for live pages.
 *
 * @var array $block      The block settings and attributes.
 * @var bool  $is_preview True in the editor.
 *
 * @package WL
 */

$wl_title = (string) get_field( 'title' );

if ( '' === $wl_title ) {
	wl_block_placeholder( __( 'Master page note: name the block or page style.', 'wl' ) );
	return;
}

$wl_kinds   = array(
	'block' => __( 'Block', 'wl' ),
	'page'  => __( 'Page style', 'wl' ),
	'tip'   => __( 'Tip', 'wl' ),
);
$wl_kind    = get_field( 'kind' ) ? get_field( 'kind' ) : 'block';
$wl_options = array_filter( array_map( 'trim', preg_split( '/\r?\n/', (string) get_field( 'options' ) ) ) );

?>
<section <?php echo wl_block_attrs( $block, 'master-note', array( 'mnote', 'mnote--' . $wl_kind ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper. ?>>
	<div class="container">
		<div class="mnote__box">
			<p class="mnote__kind"><?php echo esc_html( isset( $wl_kinds[ $wl_kind ] ) ? $wl_kinds[ $wl_kind ] : $wl_kinds['block'] ); ?></p>
			<h2 class="mnote__title"><?php echo esc_html( $wl_title ); ?></h2>
			<?php if ( get_field( 'purpose' ) ) { ?>
				<p class="mnote__purpose"><?php echo esc_html( get_field( 'purpose' ) ); ?></p>
			<?php } ?>
			<?php if ( $wl_options ) { ?>
				<div class="mnote__options">
					<p class="mnote__label"><?php esc_html_e( 'Options shown below', 'wl' ); ?></p>
					<ul>
						<?php foreach ( $wl_options as $wl_option ) { ?>
							<li><?php echo esc_html( $wl_option ); ?></li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
			<?php if ( get_field( 'where' ) ) { ?>
				<p class="mnote__where"><?php echo wl_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?><span><?php echo esc_html( get_field( 'where' ) ); ?></span></p>
			<?php } ?>
		</div>
	</div>
</section>
