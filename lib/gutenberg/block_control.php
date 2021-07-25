<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セパレート出力をオン
 */
// add_filter( 'separate_core_block_assets', '__return_true' );

/**
 * 選択できないレガシーウィジェット選択可能にする
 */
add_filter( 'widget_types_to_hide_from_legacy_widget_block', function( $widget_types ) {

	// "pages" , "calendar" , "archives" , "media_audio" , "media_image" , "media_gallery" , "media_video" , "search" , "text" , "categories" , "recent-posts" , "recent-comments" , "rss" , "tag_cloud" , "custom_html" , "block"

	$widget_types = array_diff( $widget_types, ['search', 'custom_html', 'text' ] );
	$widget_types = array_values( $widget_types );
	return $widget_types;
});


/**
 * 使用可能なブロックの制御
 */
add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\limit_block_types', 99, 2 );
function limit_block_types( $allowed_block_types, $block_editor_context ) {

	// 全ブロック取得
	$all_blocks  = [];
	$block_types = \WP_Block_Type_Registry::get_instance()->get_all_registered();
	foreach ( $block_types as $block_type ) {
		$all_blocks[] = $block_type->name;
	}

	// return ['core/legacy-widget', 'core/group' ];
	// exit;

	$FSE_blocks = [
		'core/loginout',
		'core/page-list',
		'core/post-content',
		'core/post-date',
		'core/post-excerpt',
		'core/post-featured-image',
		'core/post-terms',
		'core/post-title',
		'core/post-template',
		'core/query-loop',
		'core/query',
		'core/query-pagination',
		'core/query-pagination-next',
		'core/query-pagination-numbers',
		'core/query-pagination-previous',
		'core/query-title',
		'core/site-logo',
		'core/site-title',
		'core/site-tagline',
	];

	$disallowed_blocks = [];

	global $hook_suffix;
	if ( 'widgets.php' === $hook_suffix || 'customize.php' === $hook_suffix ) {
		// ウィジェットではFSE & more / nextpage も オフ。
		$disallowed_blocks = array_merge( $FSE_blocks, [
			'core/more',
			'core/nextpage',
		] );
	} else {
		// その他、FSEオフ
		$disallowed_blocks = $FSE_blocks;
	}

	$allowed_blocks = array_diff( $all_blocks, $disallowed_blocks );
	$allowed_blocks = array_values( $allowed_blocks ); // array_valuesちゃんとしないと効かない
	return $allowed_blocks;
}
