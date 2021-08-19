<?php
namespace SWELL_Theme\Output;

use \SWELL_Theme as SWELL,
	SWELL_Theme\Style;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', __NAMESPACE__ . '\hook_wp_head_9', 9 );
add_action( 'wp_head', __NAMESPACE__ . '\hook_wp_head_999', 999 );
add_action( 'wp_footer', __NAMESPACE__ . '\hook_wp_footer_1', 1 );
add_action( 'wp_footer', __NAMESPACE__ . '\hook_wp_footer_20', 20 );
add_action( 'admin_head', __NAMESPACE__ . '\hook_admin_head', 20 );
add_action( 'wp_body_open', __NAMESPACE__ . '\hook_wp_body_open', 1 );

// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

/**
 * wp_headで出力するコード 優先度：9
 */
function hook_wp_head_9() {

	echo PHP_EOL;

	// 「SWELLERS' ID」の出力
	output_meta_swellers_id();

	// webフォントの出力
	output_google_font();

	// スタイルの出力
	echo '<style id="swell_custom_front_style">' . get_swl_front_css() . '</style>' . PHP_EOL;
}


/**
 * wp_headで出力するコード 優先度：99
 */
function hook_wp_head_999() {

	echo PHP_EOL;

	// 記事ごとのカスタムCSSの出力
	output_meta_custom_css();

	// 「head内コード」の出力
	if ( $head_code = SWELL::get_setting( 'head_code' ) ) echo $head_code . PHP_EOL;

	// 「自動広告用コード」の出力
	output_auto_ad();

}


/**
 * wp_footerで出力するコード 優先度:1
 */
function hook_wp_footer_1() {

	// スクロール監視用
	echo '<div class="l-scrollObserver" aria-hidden="true"></div>';

	$pjax = SWELL::is_use( 'pjax' );

	if ( $pjax || SWELL::is_use( 'ol_start' ) ) {
		wp_enqueue_script( 'swell_set_olstart', T_DIRE_URI . '/build/js/front/set_olstart.min.js', [], SWELL_VERSION, true );
	}

	if ( $pjax || SWELL::is_use( 'rellax' ) ) {
		wp_enqueue_script( 'swell_set_rellax', T_DIRE_URI . '/build/js/front/set_rellax.min.js', [ 'swell_rellax' ], SWELL_VERSION, true );
	}

	if ( ! $pjax && SWELL::is_use( 'fix_thead' ) ) {
		echo "<script>document.documentElement.setAttribute('data-has-theadfix', '1');</script>";
	}

	// clipboard.js
	if ( $pjax || SWELL::is_use( 'clipboard' ) ) {
		wp_enqueue_script( 'swell_set_urlcopy', T_DIRE_URI . '/build/js/front/set_urlcopy.min.js', [ 'clipboard' ], SWELL_VERSION, true );
	}

	// pinit.js
	if ( $pjax || SWELL::is_use( 'pinterest' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		echo '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
	}
}


/**
 * wp_footerで出力するコード 優先度:20
 */
function hook_wp_footer_20() {

	// JSON LD
	if ( SWELL::get_setting( 'use_json_ld' ) ) {
		$json_ld_data = SWELL::get_json_ld_data();
		if ( is_array( $json_ld_data ) && ! empty( $json_ld_data ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( array_values( $json_ld_data ), JSON_UNESCAPED_UNICODE ) . '</script>' . PHP_EOL;
		}
	}

	// Custom JS の出力
	if ( is_single() || is_page() || is_home() ) {
		if ( $meta_js = get_post_meta( get_queried_object_id(), 'swell_meta_js', true ) ) {
			echo '<script id="swell_custom_js">' . $meta_js . '</script>' . PHP_EOL;
		}
	}
}


/**
 * admin_headで出力するコード
 * スタイル用
 */
function hook_admin_head() {

	global $hook_suffix;
	$is_editor     = 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix;
	$is_swell_page = strpos( $hook_suffix, 'swell_settings' ) !== false;

	global $post_type;
	$is_usePreviewPage = 'speech_balloon' === $post_type;

	if ( $is_editor || $is_swell_page || $is_usePreviewPage ) {
		// ブロックエディターなどで読み込ませたい inlineスタイル
		echo '<style id="loos-block-style">' . Style::output( 'editor' ) . '</style>' . PHP_EOL;
	}
}



/**
 * フロントCSS
 */
function get_swl_front_css() {

	$is_cache_style = ( SWELL::get_setting( 'cache_style' ) && ! is_customize_preview() );

	// キャッシュキー (トップ/その他)
	if ( SWELL::is_top() && ! is_paged() ) {
		$cache_key = 'style_top';
	} elseif ( is_single() ) {
		$cache_key = 'style_single';
	} elseif ( is_page() ) {
		$cache_key = 'style_page';
	} else {
		$cache_key = 'style_other';
	}

	if ( $is_cache_style ) {
		// キャッシュを使う場合

		$style = get_transient( 'swell_parts_' . $cache_key ); // キャッシュを取得

		if ( empty( $style ) ) {
			$style = Style::output( 'front' );
			$style = str_replace( '@charset "UTF-8";', '', $style );  // モジュール化したCSSに入っている場合を考慮

			// キャッシュデータの生成(有効：30日)
			set_transient( 'swell_parts_' . $cache_key, $style, 30 * DAY_IN_SECONDS );
		}
	} else {
		// キャッシュオフ時

		$style = Style::output( 'front' );

		// かつカスタマイザープレビュー時
		if ( is_customize_preview() ) {

			// キャッシュクリアしとく
			delete_transient( 'swell_' . $cache_key );  // ~2.0.2を考慮
			delete_transient( 'swell_parts_' . $cache_key );

			// カスタマイザープレビュー中にのみ読み込むCSS
			$customizer_css = T_DIRE . '/assets/css/module/-is-customizer.css';
			if ( file_exists( $customizer_css ) ) {
				$style .= SWELL::get_file_contents( $customizer_css );
			}
		}

		$style = str_replace( '@charset "UTF-8";', '', $style );
	}

	/**
	 * 以下、キャッシュさせないCSS
	 */
	// タブ
	$is_android = SWELL::is_android();
	if ( $is_android ) {
		$style .= '.c-tabBody__item[aria-hidden="false"]{animation:none !important;display:block;}';
	}

	// Androidでは Noto-serif 以外はデフォルトフォントに指定。(游ゴシックでの太字バグがある & 6.0からデフォルトフォントが Noto-sans に。)
	$font = SWELL::get_setting( 'body_font_family' );
	if ( $is_android && 'serif' !== $font ) {
		$style .= 'body{font-weight:400;font-family:sans-serif}';
	}

	// ページごとのカスタムCSS を追加
	if ( is_single() || is_page() || is_home() ) {
		if ( get_post_meta( get_queried_object_id(), 'swell_meta_no_mb', true ) === '1' ) {
			$style .= '#content{margin-bottom:0;}.w-beforeFooter{margin-top:0;}';
		};
	}

	// LP
	if ( is_singular( 'lp' ) ) {
		$lp_css = T_DIRE . '/assets/css/module/-lp.css';

		if ( file_exists( $lp_css ) ) $style .= SWELL::get_file_contents( $lp_css );
	}

	return $style;
}


/**
 * wp_body_open()で出力するコード
 */
function hook_wp_body_open() {

	if ( $body_open_code = SWELL::get_setting( 'body_open_code' ) ) {
		echo $body_open_code . PHP_EOL;
	}
}



/**
 * SWELLERS' ID
 */
function output_meta_swellers_id() {
	$swell_afi_id = get_option( 'swell_afi_id' );

	if ( ! $swell_afi_id ) return;
	echo '<meta name="swellers_id" content="' . esc_attr( $swell_afi_id ) . '">' . PHP_EOL;
}


/**
 * WEBフォント
 */
function output_google_font() {
	$google_font = '';
	$body_font   = SWELL::get_setting( 'body_font_family' );
	$is_android  = \SWELL_Theme::is_android();

	if ( ! $is_android && 'notosans' === $body_font ) {
		$google_font = 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap';
	} elseif ( 'serif' === $body_font ) {
		$google_font = 'https://fonts.googleapis.com/css?family=Noto+Serif+JP:400,700&display=swap';
	}

	if ( ! $google_font ) return;

	// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
	echo '<link href="' . esc_url( $google_font ) . '" rel="stylesheet">' . PHP_EOL;
}


/**
 * 記事ごとのカスタムCSSの出力
 */
function output_meta_custom_css() {
	$meta_css = '';
	if ( is_single() || is_page() || is_home() ) {
		$meta_css = get_post_meta( get_queried_object_id(), 'swell_meta_css', true );
	}

	// pjaxを考慮して、CSSなくても空タグを出力しておく
	echo '<style id="swell_custom_css">' . $meta_css . '</style>' . PHP_EOL;
}


/**
 * 自動広告用コード
 */
function output_auto_ad() {
	if ( is_single() || is_page() || is_home() ) {
		$is_hide_ad = get_post_meta( get_the_ID(), 'swell_meta_hide_autoad', true );
		if ( '1' === $is_hide_ad ) return;
	}

	$auto_ad_code = SWELL::get_setting( 'auto_ad_code' );

	if ( ! $auto_ad_code) return;

	echo $auto_ad_code . PHP_EOL;
}
