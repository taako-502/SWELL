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
	// ログイン制限有効
	if ( $attrs['isRole'] ) {
		// 非ログインユーザー制限あり、かつログイン中は非表示
		if ( ! $attrs['isLoggedIn'] && is_user_logged_in() ) return '';

		// ログインユーザー制限あり
		if ( $attrs['isLoggedIn'] ) {
			$allowed_roles = array_keys( array_filter( $attrs['roles'], function( $role ) {
				return $role === true;
			}));

			$current_user = wp_get_current_user();

			// 現在のユーザーの権限が制限対象として含まれていない場合は非表示
			if ( empty( array_intersect( $allowed_roles, $current_user->roles ) ) ) return '';
		}
	}

	// 日時範囲制限有効
	if ( $attrs['isDateTime'] ) {
		// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$current_timestamp = current_time( 'timestamp' );
		$start_timestamp   = isset( $attrs['startDateTime'] ) ? strtotime( $attrs['startDateTime'] ) : null;
		$end_timestamp     = isset( $attrs['endDateTime'] ) ? strtotime( $attrs['endDateTime'] ) : null;

		// 現在日時が設定範囲に含まれていない場合は非表示
		if ( $start_timestamp && $current_timestamp < $start_timestamp ) return '';
		if ( $end_timestamp && $end_timestamp < $current_timestamp ) return '';
	}

	return $content;
}
