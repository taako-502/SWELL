<?php
namespace SWELL_Theme\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * タグクラウドにクラスを追加する
 */
add_filter( 'wp_generate_tag_cloud', __NAMESPACE__ . '\add_class_tag_cloud_link' );  // 'wp_tag_cloud'フックでもOK ?
function add_class_tag_cloud_link( $links ) {
	$links = str_replace( 'class="tag-cloud-link', 'class="tag-cloud-link hov-flash-up', $links );
	return $links;
}


/**
 * カテゴリーリストの件数を</a>の中に移動 & spanで囲む
 */
add_action( 'wp_list_categories', __NAMESPACE__ . '\hook_wp_list_categories', 10, 2 );
function hook_wp_list_categories( $output, $args ) {
	$output = str_replace( '</a> (', '<span class="cat-post-count">(', $output );
	$output = str_replace( ')', ')</span></a>', $output );

	if ( \SWELL_Theme::is_use( 'acc_submenu' ) ) {
		$span   = '<span class="c-submenuToggleBtn" data-onclick="toggleSubmenu"></span>';
		$output = preg_replace( '/<\/a>([^<]*)<ul/', $span . '</a><ul', $output );
	}

	return $output;
}


/**
 * 固定ページリストへのフック
 */
add_action( 'wp_list_pages', __NAMESPACE__ . '\hook_wp_list_pages', 10, 3 );
function hook_wp_list_pages( $output, $parsed_args, $pages ) {

	if ( \SWELL_Theme::is_use( 'acc_submenu' ) ) {
		$span   = '<span class="c-submenuToggleBtn" data-onclick="toggleSubmenu"></span>';
		$output = preg_replace( '/<\/a>([^<]*)<ul/', $span . '</a><ul', $output );
	}

	return $output;
}


/**
 * 年別アーカイブリストの投稿件数 を</a>の中に置換
 */
add_action( 'get_archives_link', __NAMESPACE__ . '\hook_get_archives_link', 10, 6 );
function hook_get_archives_link( $link_html, $url, $text, $format, $before, $after ) {
	if ( 'html' === $format ) {
		$link_html = '<li>' . $before . '<a href="' . $url . '">' . $text . '<span class="post_count">' . $after . '</span></a></li>';
	}
	return $link_html;
}
