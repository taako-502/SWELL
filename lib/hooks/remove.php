<?php
namespace SWELL_Theme\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * oEmbed
 */
add_action( 'init', __NAMESPACE__ . '\remove_oembed' );
function remove_oembed() {
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // Remove oEmbed-specific JavaScript
	// remove_action('template_redirect', 'rest_output_link_header', 11 ); // ?
	// wp_oembed_add_provider('https://*', 'https://hatenablog.com/oembed'); // はてな

	// oembed無効 : Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Embeds
	remove_action( 'parse_query', 'wp_oembed_parse_query' );
	remove_action( 'wp_head', 'wp_oembed_remove_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_remove_host_js' );

	// 本文中のURLが内部リンクの場合にWordPressがoembedをしてしまうのを解除(WP4.5.3向けの対策)
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result' );
}


/**
 * 設定に合わせて不要な機能・出力を削除
 */
add_action( 'init', __NAMESPACE__ . '\remove_wp_func' );
function remove_wp_func() {

	$OPTION = \SWELL_Theme::get_option();

	// HTMLチェックで「Bad value」になるやつ https://api.w.org/ を消す
	if ( $OPTION['remove_rest_link'] ) {
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
	}

	// WordPressのバージョン情報
	if ( $OPTION['remove_wpver'] ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	// コアのサイトマップ機能
	if ( $OPTION['remove_sitemap'] ) {
		add_filter( 'wp_sitemaps_enabled', '__return_false' );
		// add_filter( 'wp_sitemaps_users_pre_url_list', '__return_false' );
	}

	// srcset
	if ( $OPTION['remove_img_srcset'] ) {
		add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	}

	// 絵文字
	if ( $OPTION['remove_emoji'] ) {
		add_filter( 'emoji_svg_url', '__return_false' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	// rel="prev"とrel="next"のlinkタグを自動で書き出さない
	if ( $OPTION['remove_rel_link'] ) {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	}

	// Windows Live Writeの停止
	if ( $OPTION['remove_wlwmanifest'] ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
	// EditURI(RSD Link)の停止
	if ( $OPTION['remove_rsd_link'] ) {
		remove_action( 'wp_head', 'rsd_link' );
	}

	// 記号の自動変換停止(wptexturize無効化)
	if ( $OPTION['remove_wptexturize'] ) {
		add_filter( 'run_wptexturize', '__return_false' );
	}

	// RSSフィード
	if ( $OPTION['remove_feed_link'] ) {
		remove_action( 'wp_head', 'feed_links', 2 ); // 記事フィードリンク停止
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // カテゴリ・コメントフィードリンク停止
	} else {
		add_theme_support( 'automatic-feed-links' );
	}

	// セルフピンバックの停止
	if ( $OPTION['remove_self_pingbacks'] ) {
		add_action( 'pre_ping', function( &$post_links ) {
			$home = home_url();
			foreach ( $post_links as $key => $link ) {
				if ( 0 === strpos( $link, $home ) ) {
					unset( $post_links[ $key ] );
				}
			}
		} );
	}

	if ( $OPTION['remove_robots_image'] ) {
		remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );
	}

	/**
	 * script/styleタグで不要なtype属性を非表示
	 */
	// add_filter('script_loader_tag', function ($tag) {
	//     return str_replace("type='text/javascript' ", "", $tag);
	// });
	// add_filter('style_loader_tag', function ($tag) {
	//     return str_replace("type='text/css' ", "", $tag);
	// });
}


/**
 * 設定に合わせて不要な機能・出力を削除
 */
add_action( 'wp', __NAMESPACE__ . '\remove_swell_func', 11 );
function remove_swell_func() {
	$OPTION = \SWELL_Theme::get_option();

	// 画像Lazyload
	$lazyload_type = $OPTION['use_lazyload'];
	if ( 'swell' !== $lazyload_type || '' === $lazyload_type ) {
		remove_filter( 'the_content', '\SWELL_Theme\Content_Filter\add_lazyload', 12 );
	}
	if ( 'core' !== $lazyload_type ) {
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );
	}

	// 空段落削除機能のオフ
	if ( $OPTION['remove_delete_empp'] ) {
		remove_filter( 'the_content', '\SWELL_Theme\Content_Filter\remove_empty_p', 12 );
	}

	// URLの自動ブログカード化機能 プラグインなどで不具合があるページだけオフにしたりできるように apply_filters
	if ( apply_filters( 'swell_remove_url_to_card', $OPTION['remove_url2card'] ) ) {
		remove_filter( 'the_content', '\SWELL_Theme\Content_Filter\url_to_blog_card', 12 );
	}
}
