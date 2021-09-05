<?php
namespace SWELL_Theme\Load_Files\Front;

if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;

/**
 * フロントで読み込むファイル
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts', 8 );
function wp_enqueue_scripts() {
	load_front_styles();
	load_front_scripts();
	load_plugins();
}

/**
 * フロント用のスクリプト
 */
function load_front_scripts() {

	$assets = T_DIRE_URI . '/assets';
	$build  = T_DIRE_URI . '/build';

	// embed系script除外
	wp_deregister_script( 'wp-embed' );

	if ( SWELL::is_widget_iframe() ) return;

	// mainスクリプト
	$main_script = ( SWELL::is_use( 'pjax' ) ) ? '/js/main_with_pjax' : '/js/main';
	wp_enqueue_script( 'swell_script', $build . $main_script . '.min.js', [], SWELL_VERSION, true );

	// フロント側に渡すグローバル変数
	wp_localize_script( 'swell_script', 'swellVars', global_vars_on_front() );

	// prefetch使用時の追加スクリプト
	if ( SWELL::is_use( 'prefetch' ) ) {
		wp_enqueue_script( 'swell_prefetch', $build . '/js/prefetch.min.js', [], SWELL_VERSION, true );
	}

	// 管理画面用をログイン時のみ読み込む（ツールバーのキャッシュクリア処理に使用）
	if ( is_user_logged_in() ) {
		wp_enqueue_script( 'swell_admin_script', $build . '/js/admin/admin_script.min.js', [], SWELL_VERSION, true );
	}
}


/**
 * フロント用のスクリプト
 */
function load_front_styles() {

	$SETTING = SWELL::get_setting();
	$assets  = T_DIRE_URI . '/assets';
	$build   = T_DIRE_URI . '/build';

	// wp-block-libraryを読み込み
	wp_enqueue_style( 'wp-block-library' );

	// main.css
	if ( SWELL::get_setting( 'load_style_inline' ) ) {

		// インライン読み込み時
		$main_style = SWELL::get_file_contents( T_DIRE . '/assets/css/main.css' );
		$main_style = str_replace( '../', T_DIRE_URI . '/assets/', $main_style );
		$main_style = str_replace( '@charset "UTF-8";', '', $main_style );

		// 空でmain_styleを登録しておく
		wp_register_style( 'main_style', false ); // phpcs:ignore
		wp_enqueue_style( 'main_style' );

		// インラインで吐き出し
		wp_add_inline_style( 'main_style', $main_style );

	} else {
		wp_enqueue_style( 'main_style', $assets . '/css/main.css', [], SWELL_VERSION );
	}

	// 切り分け済みのブロックCSS
	load_separated_styles();

	// カスタムフォーマット用CSS
	$custom_format_css = SWELL::get_editor( 'custom_format_css' );
	if ( $custom_format_css ) {
		wp_add_inline_style( 'main_style', $custom_format_css );
	}
}

function load_separated_styles() {

	$separated_blocks = [
		'loos/accordion' => '/build/blocks/accordion/index.css',
		'loos/balloon'   => '/build/blocks/balloon/index.css',
		'loos/cap-block' => '/build/blocks/cap-block/index.css',
		'loos/columns'   => '/build/blocks/columns/index.css',
		'loos/dl'        => '/build/blocks/dl/index.css',
		'loos/full-wide' => '/build/blocks/full-wide/index.css',
	];

	// 使われたブロックだけ読み込むかどうか
	if ( 1 ) {
		if ( SWELL::is_widget_iframe() ) SWELL::$used_blocks = [];
		foreach ( $separated_blocks as $name => $path ) {

			// if ( false !== stripos( $name, 'loos/' ) ) {}
			if ( ! isset( SWELL::$used_blocks[ $name ] ) ) {
				continue;
			}

			load_separated_css( $path, $name );
		}
	} else {
		foreach ( $separated_blocks as $name => $path ) {
			load_separated_css( $path, $name );
		}
	}

}

function load_separated_css( $css_path, $name ) {
	// インライン出力するかどうか
	if ( 0 ) {
		$css = '';
		$css = SWELL::get_file_contents( T_DIRE . $css_path );
		// $css = str_replace( '../', T_DIRE_URI . '/assets/', $css );
		$css = str_replace( '@charset "UTF-8";', '', $css );
		wp_add_inline_style( 'main_style', $css );
	} else {
		wp_enqueue_style( "swell_{$name}", T_DIRE_URI . $css_path, [ 'main_style' ], SWELL_VERSION );
	}
}


/**
 * プラグインファイル
 */
function load_plugins() {

	if ( SWELL::is_widget_iframe() ) return;

	// 登録だけしておく
	wp_register_script( 'swell_luminous', T_DIRE_URI . '/assets/js/plugins/luminous.min.js', [], SWELL_VERSION, true );
	wp_register_script( 'swell_swiper', T_DIRE_URI . '/assets/js/plugins/swiper.min.js', [], SWELL_VERSION, true );
	wp_register_script( 'swell_rellax', T_DIRE_URI . '/assets/js/plugins/rellax.min.js', [], SWELL_VERSION, true );

	// スマホヘッダーナビ
	if ( SWELL::is_use( 'sp_head_nav' ) ) {
		if ( SWELL::get_setting( 'sp_head_nav_loop' ) ) {
			wp_enqueue_script( 'swell_set_sp_headnav_loop', T_DIRE_URI . '/build/js/front/set_sp_headnav_loop.min.js', [ 'swell_swiper' ], SWELL_VERSION, true );
		} else {
			wp_enqueue_script( 'swell_set_sp_headnav', T_DIRE_URI . '/build/js/front/set_sp_headnav.min.js', [], SWELL_VERSION, true );
		}
	}

	// pjax使うかどうか
	$pjax = SWELL::is_use( 'pjax' );

	// Luminous
	$is_luminous_page = is_single() || is_page() || is_category() || is_tag() || is_tax();
	$is_luminous_page = $is_luminous_page && ! SWELL::get_setting( 'remove_luminous' );
	if ( $pjax || $is_luminous_page ) {
		// wp_enqueue_script( 'swell_luminous' );
		wp_enqueue_script( 'swell_set_luminous', T_DIRE_URI . '/build/js/front/set_luminous.min.js', [ 'swell_luminous' ], SWELL_VERSION, true );
		wp_enqueue_style( 'swell_luminous', T_DIRE_URI . '/assets/css/plugins/luminous.css', [], SWELL_VERSION );
	}

	// mv
	$mv_type = SWELL::get_setting( 'main_visual_type' );
	if ( $pjax || SWELL::is_top() && ! is_paged() && 'none' !== $mv_type ) {
		$deps = 'slider' === $mv_type ? ['swell_script', 'swell_swiper' ] : ['swell_script' ];
		wp_enqueue_script( 'swell_set_mv', T_DIRE_URI . '/build/js/front/set_mv.min.js', $deps, SWELL_VERSION, true );
	}

	// post slider
	if ( $pjax || SWELL::is_top() && ! is_paged() && 'on' === SWELL::get_setting( 'show_post_slide' ) ) {
		wp_enqueue_script(
			'swell_set_post_slider',
			T_DIRE_URI . '/build/js/front/set_post_slider.min.js',
			[
				'swell_script',
				'swell_swiper',
			],
			SWELL_VERSION, true
		);
	}

	// Font Awesome
	$fa_type = SWELL::get_setting( 'load_font_awesome' );
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

	$SETTING  = SWELL::get_setting();
	$is_bot   = SWELL::is_bot() || is_robots();
	$is_login = is_user_logged_in();

	$global_vars = [
		// 'direUri' => T_DIRE_URI,
		// 'apiPath' => rest_url() .'wp/v2/',
		'postID'          => is_singular() ? get_queried_object_id() : 0,
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'ajaxNonce'       => wp_create_nonce( 'swell-ajax-nonce' ),
		'isLoggedIn'      => $is_login ? '1' : '',
		'isAjaxAfterPost' => SWELL::is_use( 'ajax_after_post' ),
		'isAjaxFooter'    => SWELL::is_use( 'ajax_footer' ),
		'isFixHeadSP'     => $SETTING['fix_header_sp'],
		'tocListTag'      => $SETTING['index_list_tag'],
		'tocTarget'       => $SETTING['toc_target'],
		'tocMinnum'       => $SETTING['toc_minnum'],
		'isCountPV'       => is_singular( SWELL::$post_types_for_pvct ) && ! $is_login && ! $is_bot,
	];

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
	if ( SWELL::is_use( 'pjax' ) ) {

		$pjax_prevent_pages              = str_replace( ["\r", "\n" ], '', $SETTING['pjax_prevent_pages'] );
		$global_vars['pjaxPreventPages'] = $pjax_prevent_pages;

	} elseif ( SWELL::is_use( 'prefetch' ) ) {

		$prefetch_prevent_keys             = str_replace( ["\r", "\n" ], '', $SETTING['prefetch_prevent_keys'] );
		$global_vars['ignorePrefetchKeys'] = $prefetch_prevent_keys;
	}

	return $global_vars;
}
