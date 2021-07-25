<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 投稿リストブロック
 */
$asset = include T_DIRE . '/build/blocks/post-list/index.asset.php';
wp_register_script(
	'swell-block/post-list',
	T_DIRE_URI . '/build/blocks/post-list/index.js',
	array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
	SWELL_VERSION,
	true
);

register_block_type_from_metadata( T_DIRE . '/src/gutenberg/blocks/post-list', [
	// 'style' => "swell-block/post-list",
	'editor_script'   => 'swell-block/post-list',
	'render_callback' => __NAMESPACE__ . '\cb_post_list',
] );


function cb_post_list( $attrs, $content ) {
	$args = [
		'post_type'      => $attrs['postType'],
		'type'           => $attrs['listType'],
		'count'          => $attrs['listCount'],
		'ignore_sticky'  => ! $attrs['addSticky'],
		'order'          => $attrs['order'],
		'orderby'        => $attrs['orderby'],
		'max_col'        => $attrs['pcCol'],
		'max_col_sp'     => $attrs['spCol'],
		'more'           => $attrs['moreText'],
		'more_url'       => $attrs['moreUrl'],
		'excerpt_length' => 0,
		'cat_pos'        => $attrs['catPos'],
		'show_title'     => $attrs['showTitle'],
		'show_date'      => $attrs['showDate'],
		'show_modified'  => $attrs['showModified'],
		'show_author'    => $attrs['showAuthor'],
		'show_pv'        => $attrs['showPV'],
		'h_tag'          => $attrs['hTag'],
	];

	// 取得条件
	$args['post_id']        = $attrs['postID'];
	$args['cat_id']         = $attrs['catID'];
	$args['cat_relation']   = $attrs['catRelation'];
	$args['tag_id']         = $attrs['tagID'];
	$args['tag_relation']   = $attrs['tagRelation'];
	$args['taxonomy']       = $attrs['taxName'];
	$args['term_id']        = $attrs['termID'];
	$args['term_relation']  = $attrs['termRelation'];
	$args['query_relation'] = $attrs['queryRelation'];
	$args['inc_children']   = ! $attrs['exCatChildren'];
	$args['author_id']      = $attrs['authorID'];

	// 除外指定
	$args['exc_id'] = $attrs['excID'];

	// 抜粋文の文字数
	$args['excerpt_length'] = ( IS_MOBILE ) ? $attrs['spExcerptLength'] : $attrs['pcExcerptLength'];

	// 最後の投稿を非表示にする設定
	if ( $attrs['pcHideLast'] ) {
		$args['pc_hide_last'] = $attrs['pcHideLast'];
		}
	if ( $attrs['spHideLast'] ) {
		$args['sp_hide_last'] = $attrs['spHideLast'];
		}
	$class_name = $attrs['className'] ?: '';

	ob_start();
	echo '<div class="' . esc_attr( trim( "p-postListWrap $class_name" ) ) . '">';
	\SWELL_THEME\Parts\Post_List::list_on_block( $args );
	echo '</div>';
	return ob_get_clean();
}
