<?php
namespace SWELL_Theme\Ajax;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_swell_clear_cache', __NAMESPACE__ . '\clear_cache' );
add_action( 'wp_ajax_swell_clear_card_cache', __NAMESPACE__ . '\clear_card_cache' );
add_action( 'wp_ajax_swell_reset_settings', __NAMESPACE__ . '\reset_settings' );
add_action( 'wp_ajax_swell_reset_pv', __NAMESPACE__ . '\reset_pv' );


/**
 * キャッシュクリア
 */
function clear_cache() {
	if ( \SWELL_FUNC::check_ajax_nonce() ) {
		\SWELL_FUNC::clear_cache();
		\SWELL_FUNC::clear_cache( [], 'swell' ); // 2.0.2以下で使用していたキーで保持しているキャッシュを削除
		$return = 'キャッシュクリアに成功しました。';
	} else {
		$return = 'キャッシュクリアに失敗しました。';
	}
	wp_die( json_encode( $return ) );
}


/**
 * キャッシュクリア（ブログカード）
 */
function clear_card_cache() {
	if ( \SWELL_FUNC::check_ajax_nonce() ) {
		\SWELL_FUNC::clear_card_cache();
		$return = 'キャッシュクリアに成功しました。';
	} else {
		$return = 'キャッシュクリアに失敗しました。';
	}
	wp_die( json_encode( $return ) );
}


/**
 * カスタマイザー リセット
 * $.ajaxから呼ぶので json_encode不要
 */
function reset_settings() {
	if ( \SWELL_FUNC::check_ajax_nonce() ) {
		delete_option( \SWELL_Theme::DB_NAME_CUSTOMIZER );
		\SWELL_FUNC::clear_cache();
		wp_die( 'リセットに成功しました。' );
	}
	wp_die( 'リセットに失敗しました。' );
}


/**
 * PV リセット
 * $.ajaxから呼ぶので json_encode不要
 */
function reset_pv() {
	if ( ! \SWELL_FUNC::check_ajax_nonce() ) wp_die( 'リセットに失敗しました。' );

	$args      = [
		'post_type'      => 'post',
		'fields'         => 'ids',
		'posts_per_page' => -1,
	];
	$the_query = new \WP_Query( $args );
	if ( $the_query->have_posts() ) :
		foreach ( $the_query->posts as $the_id ) :
			delete_post_meta( $the_id, SWELL_CT_KEY );
		endforeach;
	endif;
	wp_reset_postdata();

	// $.ajaxから呼ぶので json_encode不要
	wp_die( 'リセットに成功しました。' );
}
