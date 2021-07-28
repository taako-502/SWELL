<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * フロントで読み込むファイル
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts', 8 );
function wp_enqueue_scripts() {
	load_front_fa();
	load_front_styles();
	load_front_scripts();
}


/**
 * フロント用のスクリプト
 */
function load_front_scripts() {

	$assets = T_DIRE_URI . '/assets';
	$build  = T_DIRE_URI . '/build';

	// embed系script除外
	wp_deregister_script( 'wp-embed' );

	if ( \SWELL_Theme::is_widget_iframe() ) return;

	// 3.0で消す
	global $is_IE;
	if ( $is_IE ) {
		wp_enqueue_script( 'object-fit-images', $assets . '/js/polyfill/ofi.min.js', [], SWELL_VERSION, true );
		wp_enqueue_script( 'picturefill', $assets . '/js/polyfill/picturefill.min.js', [], SWELL_VERSION, true );
	}

	// JSプラグイン
	wp_enqueue_script( 'swell_plugins', $assets . '/js/plugins.js', [], SWELL_VERSION, true );

	// mainスクリプト
	$main_script_path = ( \SWELL_Theme::is_use( 'pjax' ) ) ? '/js/main_with_pjax.js' : '/js/main.js';
	wp_enqueue_script( 'swell_script', $build . $main_script_path, [], SWELL_VERSION, true );

	// フロント側に渡すグローバル変数
	wp_localize_script( 'swell_script', 'swellVars', global_vars_on_front() );

	// prefetch使用時の追加スクリプト
	if ( \SWELL_Theme::is_use( 'prefetch' ) ) {
		wp_enqueue_script( 'swell_prefetch_script', $build . '/js/set_prefetch.js', [], SWELL_VERSION, true );
	}

	// 管理画面用をログイン時のみ読み込む（ツールバーのキャッシュクリア処理に使用）
	if ( is_user_logged_in() ) {
		wp_enqueue_script( 'swell_admin_script', $build . '/js/admin/admin_script.js', [], SWELL_VERSION, true );
	}
}


/**
 * フロント用のスクリプト
 */
function load_front_styles() {

	global $is_IE;
	$SETTING = \SWELL_Theme::get_setting();
	$assets  = T_DIRE_URI . '/assets';
	$build   = T_DIRE_URI . '/build';

	// wp-block-libraryを読み込み
	wp_enqueue_style( 'wp-block-library' );

	// main.css
	if ( $is_IE || \SWELL_Theme::get_setting( 'load_style_inline' ) ) {

		// インライン読み込み時
		$main_style = \SWELL_Theme::get_file_contents( T_DIRE . '/assets/css/main.css' );
		$main_style = str_replace( '../', T_DIRE_URI . '/assets/', $main_style );
		$main_style = str_replace( '@charset "UTF-8";', '', $main_style );

		if ( $is_IE ) {
			// IEではカスタムプロパティを置換しておく
			$main_style = \SWELL_Theme::replace_css_var_on_IE( $main_style );
		}

		// 空でmain_styleを登録しておく
		wp_register_style( 'main_style', false ); // phpcs:ignore
		wp_enqueue_style( 'main_style' );

		// インラインで吐き出し
		wp_add_inline_style( 'main_style', $main_style );

	} else {
		wp_enqueue_style( 'main_style', $assets . '/css/main.css', [], SWELL_VERSION );
	}

	// カスタムフォーマット用CSS
	$custom_format_css = \SWELL_Theme::get_editor( 'custom_format_css' );
	if ( $custom_format_css ) {
		wp_add_inline_style( 'main_style', $custom_format_css );
	}
}


/**
 * FontAwesome
 */
function load_front_fa() {
	$fa_type = \SWELL_Theme::get_setting( 'load_font_awesome' );
	if ( 'css' === $fa_type ) {
		wp_enqueue_style( 'font-awesome-5', T_DIRE_URI . '/assets/font-awesome/css/all.min.css', [], SWELL_VERSION );
	} elseif ( 'js' === $fa_type ) {
		wp_enqueue_script( 'font-awesome-5', T_DIRE_URI . '/assets/font-awesome/js/all.min.js', [], SWELL_VERSION, true );
	}
}


/**
 * フロント用のJSグローバル変数 ( swellVars にセットする)
 */
function global_vars_on_front() {

	$SETTING  = \SWELL_FUNC::get_setting();
	$is_bot   = \SWELL_Theme::is_bot() || is_robots();
	$is_login = is_user_logged_in();

	$global_vars = [
		// 'direUri' => T_DIRE_URI,
		// 'apiPath' => rest_url() .'wp/v2/',
		'postID'          => is_singular() ? get_queried_object_id() : 0,
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'ajaxNonce'       => wp_create_nonce( 'swell-ajax-nonce' ),
		'isLoggedIn'      => $is_login ? '1' : '',
		'isAjaxAfterPost' => \SWELL_Theme::is_use( 'ajax_after_post' ),
		'isAjaxFooter'    => \SWELL_Theme::is_use( 'ajax_footer' ),
		'isFixHeadSP'     => $SETTING['fix_header_sp'],
		'tocListTag'      => $SETTING['index_list_tag'],
		'tocTarget'       => $SETTING['toc_target'],
		'tocMinnum'       => $SETTING['toc_minnum'],
		'isCountPV'       => is_singular( \SWELL_Theme::$post_types_for_pvct ) && ! $is_login && ! $is_bot,
	];

	// Luminousのオン / オフ
	$is_luminous_page           = is_single() || is_page() || is_category() || is_tag() || is_tax();
	$global_vars['useLuminous'] = ( $is_luminous_page && ! $SETTING['remove_luminous'] );

	// メインビジュアルスライダー
	if ( 'slider' === $SETTING['main_visual_type'] ) {
		$global_vars += [
			'mvSlideEffect' => $SETTING['mv_slide_effect'],
			'mvSlideSpeed'  => $SETTING['mv_slide_speed'],
			'mvSlideDelay'  => $SETTING['mv_slide_delay'],
			'mvSlideNum'    => $SETTING['mv_slide_num'],
			'mvSlideNumSp'  => $SETTING['mv_slide_num_sp'],
		];
	}

	// 記事スライダー
	if ( 'off' !== $SETTING['show_post_slide'] ) {
		$global_vars += [
			'psNum'   => $SETTING['ps_num'],
			'psNumSp' => $SETTING['ps_num_sp'],
			'psSpeed' => $SETTING['ps_speed'],
			'psDelay' => $SETTING['ps_delay'],
		];
	}

	// pjaxで無視するページ群
	if ( \SWELL_Theme::is_use( 'pjax' ) ) {

		$pjax_prevent_pages              = str_replace( ["\r", "\n" ], '', $SETTING['pjax_prevent_pages'] );
		$global_vars['pjaxPreventPages'] = $pjax_prevent_pages;

	} elseif ( \SWELL_Theme::is_use( 'prefetch' ) ) {

		$prefetch_prevent_keys             = str_replace( ["\r", "\n" ], '', $SETTING['prefetch_prevent_keys'] );
		$global_vars['ignorePrefetchKeys'] = $prefetch_prevent_keys;
	}

	return $global_vars;
}
