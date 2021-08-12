<?php
namespace SWELL_Theme\Consts;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

// IS_MOBILE （カスタマイザーでも有効になるようにしている）
define( 'IS_MOBILE', SWELL::get_is_mobile() );

// 後方互換用
define( 'HOME', SWELL::site_data( 'home' ) );
define( 'TITLE', SWELL::site_data( 'title' ) );
define( 'IS_ADMIN', is_admin() );
define( 'IS_LOGIN', is_user_logged_in() );


/**
 * 子テーマから上書き可能
 */
add_action( 'after_setup_theme', function() {
	// SWELLバージョンを定数化 : 3.0で廃止
	if ( ! defined( 'SWELL_VERSION' ) ) {
		define( 'SWELL_VERSION', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? wp_date( 'mdGis' ) : SWELL::$swell_version );
	}

	// ポストビューの保存キー
	if ( ! defined( 'SWELL_CT_KEY' ) ) {
		define( 'SWELL_CT_KEY', 'ct_post_views_byloos' );
	}
}, 10 );


/**
 * カスタマイザーのデータを受け取ってから定義する定数群
 *    プレビュー画面の即時反映データも受け取れる
 *    AJAXでもギリギリ呼び出される
 *    Gutenbergの<ServerSideRender />でも実行されてるっぽい?
 *      （ after_setup_theme で定義した定数は使えなかった ）
 */
add_action( 'wp_loaded', __NAMESPACE__ . '\hook_wp_loaded', 11 );
function hook_wp_loaded() {

	$SETTING      = \SWELL_Theme::get_setting();
	$is_customize = is_customize_preview();

	SWELL::$use['pjax']            = ( 'pjax' === $SETTING['use_pjax'] && ! $is_customize );
	SWELL::$use['prefetch']        = ( 'prefetch' === $SETTING['use_pjax'] && ! $is_customize );
	SWELL::$use['ajax_footer']     = ( $SETTING['ajax_footer'] && ! $is_customize );
	SWELL::$use['ajax_after_post'] = ( $SETTING['ajax_after_post'] && ! $is_customize );
	SWELL::$use['card_cache__in']  = $SETTING['cache_blogcard_in'];
	SWELL::$use['card_cache__ex']  = $SETTING['cache_blogcard_ex'];
	SWELL::$use['acc_submenu']     = $SETTING['acc_submenu'];
	SWELL::$use['lazysizes']       = 1; // $SETTING['lazysizes'];
	SWELL::$use['sp_head_nav']     = has_nav_menu( 'sp_head_menu' );

	// NO IMAGE画像
	$noimg_url             = $SETTING['no_image'] ?: '';
	$noimg_id              = attachment_url_to_postid( $noimg_url ) ?: 0;
	$noimg_m_url           = $noimg_id ? wp_get_attachment_image_url( $noimg_id, 'medium' ) : $noimg_url;
	SWELL::$noimg['id']    = $noimg_id;
	SWELL::$noimg['url']   = $noimg_url;
	SWELL::$noimg['small'] = $noimg_m_url;

	// ロゴ画像
	SWELL::$site_data['logo']     = $SETTING['logo'];
	SWELL::$site_data['logo_top'] = $SETTING['logo_top'];

	// キャッチフレーズ
	SWELL::$site_data['catchphrase'] = get_option( 'blogdescription' );
	if ( $SETTING['show_title'] ) {
		SWELL::$site_data['catchphrase'] .= ' | ' . SWELL::site_data( 'title' );
	}

	// リストタイプ
	SWELL::$list_type = ( IS_MOBILE ) ? $SETTING['post_list_layout_sp'] : $SETTING['post_list_layout'];

	// 抜粋文の長さ
	SWELL::$excerpt_length = IS_MOBILE ? (int) $SETTING['excerpt_length_sp'] : (int) $SETTING['excerpt_length_pc'];

	// 後方互換用
	define( 'PLACEHOLDER', SWELL::$placeholder );
	define( 'USE_AJAX_FOOTER', SWELL::$use['ajax_footer'] );
	define( 'USE_AJAX_AFTER_POST', SWELL::$use['ajax_after_post'] );
	define( 'LOGO', SWELL::$site_data['logo'] );
	define( 'LIST_TYPE', SWELL::$list_type );
	define( 'NOIMG', $noimg_url );
	define( 'NOIMG_S', $noimg_m_url );
	// define( 'NOIMG_ID', $noimg_id );
}


/**
 * 主な条件分岐タグを定数化
 */
add_action( 'wp', __NAMESPACE__ . '\hook_wp', 2 );
function hook_wp() {
	define( 'IS_TOP', SWELL::is_top() );
	define( 'IS_TERM', SWELL::is_term() );
}
