<?php
namespace SWELL_Theme\Ajax;

if ( ! defined( 'ABSPATH' ) ) exit;

// 広告の計測
add_action( 'wp_ajax_swell_ct_ad_pv', __NAMESPACE__ . '\ct_ad_pv' );
add_action( 'wp_ajax_nopriv_swell_ct_ad_pv', __NAMESPACE__ . '\ct_ad_pv' );

add_action( 'wp_ajax_swell_ct_ad_imp', __NAMESPACE__ . '\ct_ad_imp' );
add_action( 'wp_ajax_nopriv_swell_ct_ad_imp', __NAMESPACE__ . '\ct_ad_imp' );

add_action( 'wp_ajax_swell_clicked_ad', __NAMESPACE__ . '\clicked_ad' );
add_action( 'wp_ajax_nopriv_swell_clicked_ad', __NAMESPACE__ . '\clicked_ad' );

add_action( 'wp_ajax_swell_reset_ad_data', __NAMESPACE__ . '\reset_ad_data' );

// ボタンの計測
add_action( 'wp_ajax_swell_ct_btn_data', __NAMESPACE__ . '\ct_btn_data' );
add_action( 'wp_ajax_nopriv_swell_ct_btn_data', __NAMESPACE__ . '\ct_btn_data' );


/**
 * 広告があるページのPV数をカウント
 */
function ct_ad_pv() {
	if ( ! \SWELL_Theme::check_ajax_nonce() ) wp_die( json_encode( [] ) );
	if ( ! isset( $_POST['id'] ) ) wp_die( json_encode( [] ) );

	$return = [];

	$ad_id  = $_POST['id'];
	$ad_ids = explode( ',', $ad_id );
	foreach ( $ad_ids as $ad_id ) {
		$count       = (int) get_post_meta( $ad_id, 'pv_count', true );
		$update_meta = update_post_meta( $ad_id, 'pv_count', $count + 1 );

		$return['id'] = $ad_id;
		$return['ct'] = $count;
	}

	wp_die( json_encode( $return ) );
}


/**
 * 広告表示回数を記録
 */
function ct_ad_imp() {
	if ( ! \SWELL_Theme::check_ajax_nonce() ) wp_die( json_encode( [] ) );
	if ( ! isset( $_POST['id'] ) ) wp_die( json_encode( [] ) );

	$ad_id = $_POST['id'];

	$count       = (int) get_post_meta( $ad_id, 'imp_count', true );
	$update_meta = update_post_meta( $ad_id, 'imp_count', $count + 1 );

	$return = [
		'ct' => $count,
	];
	wp_die( json_encode( $return ) );
}


/**
 * 広告クリック数を記録
 */
function clicked_ad() {
	if ( ! \SWELL_Theme::check_ajax_nonce() ) wp_die( json_encode( [] ) );
	if ( ! isset( $_POST['id'] ) ) wp_die( json_encode( [] ) );

	$ad_id = $_POST['id'];

	// どこがクリックされたか
	$ad_target = ( isset( $_POST['target'] ) ) ? $_POST['target'] : '';
	$meta_key  = $ad_target . '_clicked_ct'; // tag / btn1 / btn2 / button?

	$count       = (int) get_post_meta( $ad_id, $meta_key, true );
	$update_meta = update_post_meta( $ad_id, $meta_key, $count + 1 );

	$return = [
		'id'     => $ad_id,
		'target' => $ad_target,
		'ct'     => $count,
	];
	wp_die( json_encode( $return ) );
}


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


/**
 * ボタンクリック数を記録
 */
function ct_btn_data() {
	if ( ! \SWELL_Theme::check_ajax_nonce() ) wp_die( json_encode( [] ) );
	if ( ! isset( $_POST['btnid'] ) || ! isset( $_POST['postid'] ) || ! isset( $_POST['ct_name'] ) ) wp_die( json_encode( [] ) );

	$btnid   = $_POST['btnid'];
	$postid  = $_POST['postid'];
	$ct_name = $_POST['ct_name']; // 何をカウントするか

	$btn_cv_metas = get_post_meta( $postid, 'swell_btn_cv_data', true ) ?: [];

	if ( $btn_cv_metas ) $btn_cv_metas = json_decode( $btn_cv_metas, true );

	// ここで配列になっていなければ何かがおかしいので return
	if ( ! is_array( $btn_cv_metas ) ) wp_die( json_encode( [] ) );

	if ( 'pv' === $ct_name ) {
		$btnids = explode( ',', $btnid );
		foreach ( $btnids as $the_id ) {
			$btn_cv_metas = ct_up_btn_data( $btn_cv_metas, $the_id, $ct_name );
		}
	} else {
		$btn_cv_metas = ct_up_btn_data( $btn_cv_metas, $btnid, $ct_name );
	}

	$btn_cv_metas = json_encode( $btn_cv_metas );
	$update_meta  = update_post_meta( $postid, 'swell_btn_cv_data', $btn_cv_metas );

	$return = [
		'btnid'   => $btnid,
		'cvdata'  => $btn_cv_metas,
		'ct_name' => $ct_name,
	];
	wp_die( json_encode( $return ) );
}


function ct_up_btn_data( $cv_data, $btnid, $ct_name ) {
	if ( ! isset( $cv_data[ $btnid ] ) ) {
		$cv_data[ $btnid ]             = [];
		$cv_data[ $btnid ][ $ct_name ] = 1;
	} else {
		$count                         = (int) $cv_data[ $btnid ][ $ct_name ];
		$cv_data[ $btnid ][ $ct_name ] = $count + 1;
	}

	return $cv_data;
};
