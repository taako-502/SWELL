<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 関連記事ブロック
 */
$block_name = 'ad-tag';
$handle     = 'swell-block/' . $block_name;
wp_register_script(
	$handle,
	T_DIRE_URI . '/build/blocks/' . $block_name . '/index.js',
	['swell_blocks' ],
	SWELL_VERSION,
	true
);

register_block_type(
	'loos/ad-tag',
	[
		'editor_script'   => $handle,
		'attributes'      => [
			// JS側と合わせないと「ブロック読み込みエラー: 無効なパラメーター: attributes」が出たりする
			'className' => [
				'type'    => 'string',
				'default' => '',
			],
			'adTitle' => [
				'type'    => 'string',
				'default' => '',
			],
			'adID' => [
				'type'    => 'string',
				'default' => '',
			],
		],
		'render_callback' => 'SWELL_THEME\Block\cb_ad_tag',
	]
);

function cb_ad_tag( $attributes ) {
	ob_start();
	echo do_shortcode( '[ad_tag id="' . $attributes['adID'] . '" class="' . $attributes['className'] . '"]' );
	return ob_get_clean();
}
