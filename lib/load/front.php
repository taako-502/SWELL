<?php
namespace SWELL_Theme\Load_Files\Front;

if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL, SWELL_Theme\Style;


/**
 * フロントで読み込むファイル
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts', 8 );
function wp_enqueue_scripts() {
	load_plugins();
	load_front_styles();
	load_front_scripts();

	if ( SWELL::get_option( 'load_style_async' ) ) {
		add_filter( 'style_loader_tag', __NAMESPACE__ . '\load_css_async', 10, 4 );
	}
}

// CSSを非同期で読み込む
function load_css_async( $html, $handle, $href, $media ) {

	$target_handles = [
		'swell_luminous',
		'swell-parts/footer',
		'swell-parts/comments',
		// 'swell-parts/pn-links--normal',
		// 'swell-parts/pn-links--simple',
		// 'swell-parts/sns-cta',
		// 'swell-parts/fix-header',
		// 'swell-parts/fix_bottom_menu',
	];

	if ( in_array( $handle, $target_handles, true ) ) {
		// 元の link 要素の HTML（改行が含まれているようなので前後の空白文字を削除）
		$default_html = trim( $html );

		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
		$html = '<link rel="stylesheet" id="' . $handle . '-css" href="' . $href . '" media="print" onload="this.media=\'all\'">' .
		'<noscript> ' . $default_html . '</noscript>' . PHP_EOL;
		}

	return $html;
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
		wp_localize_script( 'swell_admin_script', 'wpApiSettings', [
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		] );
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
	if ( SWELL::is_load_css_inline() ) {

		// インライン読み込み時
		$main_style = SWELL::get_file_contents( T_DIRE . '/assets/css/main.css' );
		$main_style = str_replace( '../', T_DIRE_URI . '/assets/', $main_style );
		$main_style = str_replace( '@charset "UTF-8";', '', $main_style );

		// 空で登録しておく
		wp_register_style( 'main_style', false ); // phpcs:ignore
		wp_enqueue_style( 'main_style' );
		wp_add_inline_style( 'main_style', $main_style );

	} else {
		wp_enqueue_style( 'main_style', $assets . '/css/main.css', [], SWELL_VERSION );
	}

	// 切り分けたCSSの読み込み
	load_separated_styles();

	// 動的CSS（カスマイザーの設定値で変わるCSS）
	wp_register_style( 'swell_custom', false ); // phpcs:ignore
	wp_enqueue_style( 'swell_custom' );
	wp_add_inline_style( 'swell_custom', get_swl_front_css() );

	// カスタムフォーマット用CSS
	$custom_format_css = SWELL::get_editor( 'custom_format_css' );
	if ( $custom_format_css ) {
		wp_add_inline_style( 'main_style', $custom_format_css );
	}
}


function load_separated_styles() {

	$separated_blocks = [
		'core/calendar'     => '/assets/css/modules/blocks/calendar.css',
		'core/categories'   => '/assets/css/modules/blocks/categories.css',
		'core/columns'      => '/assets/css/modules/blocks/columns.css',
		'core/embed'        => '/assets/css/modules/blocks/embed.css',
		'core/file'         => '/assets/css/modules/blocks/file.css',
		'core/gallery'      => '/assets/css/modules/blocks/gallery.css',
		'core/media-text'   => '/assets/css/modules/blocks/media-text.css',
		'core/latest-posts' => '/assets/css/modules/blocks/latest-posts.css',
		'core/pullquote'    => '/assets/css/modules/blocks/pullquote.css',
		'core/search'       => '/assets/css/modules/blocks/search.css',
		'core/separator'    => '/assets/css/modules/blocks/separator.css',
		'core/social-links' => '/assets/css/modules/blocks/social-links.css',
		'core/table'        => '/assets/css/modules/blocks/table.css',
		'core/tag-cloud'    => '/assets/css/modules/blocks/tag-cloud.css',
		'widget/dropdown'   => '/assets/css/modules/blocks/widget-dropdown.css',
		'widget/rss'        => '/assets/css/modules/blocks/widget-rss.css',
		// 'widget/list'       => '/assets/css/modules/blocks/widget-list.css',

		// swell
		// 'loos/post-list'    => '/assets/css/modules/blocks/post-list.css',
		'loos/profile-box'  => '/assets/css/modules/blocks/profile-box.css',
		'loos/accordion'    => '/build/blocks/accordion/index.css',
		'loos/ad-tag'       => '/build/blocks/ad-tag/index.css',
		'loos/balloon'      => '/build/blocks/balloon/index.css',
		'loos/banner-link'  => '/build/blocks/banner-link/index.css',
		'loos/cap-block'    => '/build/blocks/cap-block/index.css',
		'loos/columns'      => '/build/blocks/columns/index.css',
		'loos/dl'           => '/build/blocks/dl/index.css',
		'loos/faq'          => '/build/blocks/faq/index.css',
		'loos/full-wide'    => '/build/blocks/full-wide/index.css',
		'loos/step'         => '/build/blocks/step/index.css',
		'loos/tab'          => '/build/blocks/tab/index.css',
	];

	// 使われたブロックだけ読み込むかどうか
	if ( SWELL::is_separate_css() ) {
		if ( SWELL::is_widget_iframe() ) SWELL::$used_blocks = [];
		foreach ( $separated_blocks as $name => $path ) {

			// if ( false !== stripos( $name, 'loos/' ) ) {}
			if ( ! isset( SWELL::$used_blocks[ $name ] ) ) {
				continue;
			}
			load_separated_css( $path, $name );
		}
	} else {

		// インライン出力するかどうか
		if ( SWELL::is_load_css_inline() ) {

			$block_style = SWELL::get_file_contents( T_DIRE . '/assets/css/blocks.css' );
			$block_style = str_replace( '@charset "UTF-8";', '', $block_style );

			wp_register_style( 'swell_blocks', false ); // phpcs:ignore
			wp_enqueue_style( 'swell_blocks' );
			wp_add_inline_style( 'swell_blocks', $block_style );
		} else {
			wp_enqueue_style( 'swell_blocks', T_DIRE_URI . '/assets/css/blocks.css', [], SWELL_VERSION );
		}
}

}

function load_separated_css( $css_path, $name ) {
	// インライン出力するかどうか
	if ( SWELL::is_load_css_inline() ) {
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
	wp_register_style( 'swell_swiper', T_DIRE_URI . '/assets/css/plugins/swiper.css', [], SWELL_VERSION );
	wp_register_script( 'swell_luminous', T_DIRE_URI . '/assets/js/plugins/luminous.min.js', [], SWELL_VERSION, true );
	wp_register_script( 'swell_swiper', T_DIRE_URI . '/assets/js/plugins/swiper.min.js', [], SWELL_VERSION, true );
	wp_register_script( 'swell_rellax', T_DIRE_URI . '/assets/js/plugins/rellax.min.js', [], SWELL_VERSION, true );

	// スマホヘッダーナビ
	if ( SWELL::is_use( 'sp_head_nav' ) ) {
		if ( SWELL::get_setting( 'sp_head_nav_loop' ) ) {
			wp_enqueue_style( 'swell_swiper' );
			wp_enqueue_script( 'swell_set_sp_headnav_loop', T_DIRE_URI . '/build/js/front/set_sp_headnav_loop.min.js', [ 'swell_swiper' ], SWELL_VERSION, true );
		} else {
			wp_enqueue_script( 'swell_set_sp_headnav', T_DIRE_URI . '/build/js/front/set_sp_headnav.min.js', [], SWELL_VERSION, true );
		}
	}

	// pjax使うかどうか
	$pjax = SWELL::is_use( 'pjax' );

	// mv
	if ( $pjax || SWELL::is_use( 'mv' ) ) {
		if ( 'slider' === SWELL::site_data( 'mv' ) ) {
			wp_enqueue_style( 'swell_swiper' );
			wp_enqueue_script( 'swell_set_mv', T_DIRE_URI . '/build/js/front/set_mv.min.js', [ 'swell_script', 'swell_swiper' ], SWELL_VERSION, true );
		} else {
			wp_enqueue_script( 'swell_set_mv', T_DIRE_URI . '/build/js/front/set_mv.min.js', [ 'swell_script' ], SWELL_VERSION, true );
		}
	}

	// post slider
	if ( $pjax || SWELL::is_use( 'post_slider' ) ) {
		wp_enqueue_style( 'swell_swiper' );
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
		'restUrl'         => rest_url() . 'wp/v2/',
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'ajaxNonce'       => wp_create_nonce( 'swell-ajax-nonce' ),
		'isLoggedIn'      => $is_login ? '1' : '',
		'isAjaxAfterPost' => SWELL::is_use( 'ajax_after_post' ),
		'isAjaxFooter'    => SWELL::is_use( 'ajax_footer' ),
		'isFixHeadSP'     => $SETTING['fix_header_sp'],
		'tocListTag'      => $SETTING['index_list_tag'],
		'tocTarget'       => $SETTING['toc_target'],
		'tocMinnum'       => $SETTING['toc_minnum'],
	];

	// メインビジュアルスライダー
	if ( 'slider' === SWELL::site_data( 'mv' ) ) {
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



/**
 * フロントCSS
 */
function get_swl_front_css() {

	// カスタマイザープレビュー時、変更が反映されるようにキャッシュクリアする
	// if ( is_customize_preview() ) {
	// 	delete_transient( 'swell_' . $cache_key );  // ~2.0.2を考慮
	// 	delete_transient( 'swell_parts_' . $cache_key );
	// }

	// キャッシュ可能naCSS
	$style = Style::get_front_css();

	return $style;
}
