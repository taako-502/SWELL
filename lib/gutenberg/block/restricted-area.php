<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 制限エリアブロック
 */
$asset = include T_DIRE . '/build/blocks/restricted-area/index.asset.php';
wp_register_script(
	'swell-block/restricted-area',
	T_DIRE_URI . '/build/blocks/restricted-area/index.js',
	array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
	SWELL_VERSION,
	true
);

register_block_type_from_metadata( T_DIRE . '/src/gutenberg/blocks/restricted-area', [
	'editor_script'   => 'swell-block/restricted-area',
	'render_callback' => __NAMESPACE__ . '\cb_restricted_area',
] );

function cb_restricted_area( $attrs, $content ) {

	if ( $attrs['nonLoggedin'] && is_user_logged_in() ) return '';

	if ( ! is_user_logged_in() ) return '';

	foreach ( [
		'administrator',
		'editor',
		'author',
		'contributor',
		'subscriber',
	] as $role ) {
		if ( $attrs[ $role ] && current_user_can( $role ) ) {
			return $content;
		}
	}

	return '';
}
