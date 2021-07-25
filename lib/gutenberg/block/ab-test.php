<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ABテストブロック用の処理を登録
 */
add_filter( 'render_block_data', 'SWELL_THEME\Block\hook_render_block_data', 10, 2 );
add_filter( 'render_block', 'SWELL_THEME\Block\hook_render_block', 99, 2 );

// A, Bのどちらかランダムに isHide をセット
function hook_render_block_data( $block, $source_block ) {
	if ( 'loos/ab-test' !== $block['blockName'] ) return $block;

	$abBlocks = $block['innerBlocks'];
	$flag     = mt_rand( 1, 2 );

	if ( 1 === $flag ) {
		// Aを削除
		$abBlocks[0]['attrs'] = ['isHide' => true ];
	} else {
		// Bを削除
		$abBlocks[1]['attrs'] = ['isHide' => true ];
	}

	$block['innerBlocks'] = $abBlocks;
	return $block;
}


// isHide がついている方は何も出力しない
function hook_render_block( $block_content, $block ) {
	if ( 'loos/ab-test-a' !== $block['blockName'] && 'loos/ab-test-b' !== $block['blockName'] ) {
		return $block_content;
	}

	$attrs = $block['attrs'];
	if ( isset( $attrs['isHide'] ) && $attrs['isHide'] ) {
		return '';
	}

	return $block_content;
}
