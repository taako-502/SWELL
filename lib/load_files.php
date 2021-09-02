<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once T_DIRE . '/lib/load/jquery.php';
require_once T_DIRE . '/lib/load/front.php';
require_once T_DIRE . '/lib/load/admin.php';
require_once T_DIRE . '/lib/load/block_assets.php';


function check_blocks( $parsed_content ) {
	foreach ( $parsed_content as $block ) {
		$block_name = $block['blockName'];
		if ( ! $block_name ) continue;

		\SWELL_Theme::$used_blocks[ $block_name ] = true;
		// \SWELL_Theme::$used_blocks[] = $block_name;

		// インナーブロックにも同じ処理を。
		if ( ! empty( $block['innerBlocks'] ) ) {
			check_blocks( $block['innerBlocks'] );
		}

		// 追加で個別ブロックごとになにか処理があれば
		// if ( $block['blockName'] === 'loos/button' ) {}
	}
}



add_action( 'wp_head', __NAMESPACE__ . '\pre_parse_blocks', 0 );
function pre_parse_blocks() {

	if ( ! ( is_singular( 'post' ) || is_page() ) ) return;
	$post = get_post( get_queried_object_id() );
	if ( ! $post) return;

	// コンテンツをパースしてSWELLボタンを抽出
	$parsed_content = parse_blocks( $post->post_content );
	check_blocks( $parsed_content );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts__ttt', 8 );
function wp_enqueue_scripts__ttt() {
	echo '<pre style="margin-left: 100px;">';
	var_dump( \SWELL_Theme::$used_blocks );
	echo '</pre>';
}
