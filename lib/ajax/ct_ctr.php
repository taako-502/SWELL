<?php
namespace SWELL_Theme\Ajax;

if ( ! defined( 'ABSPATH' ) ) exit;

// 広告のリセット
add_action( 'wp_ajax_swell_reset_ad_data', __NAMESPACE__ . '\reset_ad_data' );

/**
 * 広告計測をリセット 全て 0 に。
 */
function reset_ad_data() {
	if ( ! \SWELL_Theme::check_ajax_nonce() ) wp_die( 'リセットに失敗しました' );
	if ( ! isset( $_POST['id'] ) ) wp_die( 'リセットに失敗しました' );

	$ad_id = $_POST['id'];

	$keys = [
		'imp_count',
		'pv_count',
		'tag_clicked_ct',
		'btn1_clicked_ct',
		'btn2_clicked_ct',
	];
	foreach ( $keys as $key ) {
		$update_meta = update_post_meta( $ad_id, $key, 0 );
	}
	wp_die( 'リセットに成功しました' );
}
