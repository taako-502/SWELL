<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ブログパーツブロック
 */
$block_name = 'balloon';
$handle     = 'swell-block/' . $block_name;
wp_register_script( $handle, T_DIRE_URI . '/build/blocks/' . $block_name . '/index.js', [ 'swell_blocks' ], SWELL_VERSION, true );

// block.json読み込み
$block_json = T_DIRE . '/src/gutenberg/blocks/' . $block_name . '/block.json';
$metadata   = json_decode( file_get_contents( $block_json ), true );
if ( ! is_array( $metadata ) ) return;

register_block_type(
	$metadata['name'],
	[
		'editor_script'   => $handle,
		'attributes'      => $metadata['attributes'],
		'render_callback' => 'SWELL_THEME\Block\cb_balloon',
	]
);

function cb_balloon( $attrs, $content = '' ) {

	// $balloonTitle  = $attrs['balloonTitle'];
	$balloonID     = $attrs['balloonID'];
	$balloonIcon   = $attrs['balloonIcon'];
	$balloonName   = $attrs['balloonName'];
	$balloonCol    = $attrs['balloonCol'];
	$balloonType   = $attrs['balloonType'];
	$balloonAlign  = $attrs['balloonAlign'];
	$balloonBorder = $attrs['balloonBorder'];
	$balloonShape  = $attrs['balloonShape'];
	$spVertical    = $attrs['spVertical'];

	$props = '';

	// echo '<pre style="margin-left: 100px;">';
	// var_dump(  );
	// echo '</pre>';
	// $attrs['className']

	// if ($balloonTitle) $props  .= ' set="' . $balloonTitle . '"';
	if ($balloonID) $props     .= ' id="' . $balloonID . '"';
	if ($balloonIcon) $props   .= ' icon="' . $balloonIcon . '"';
	if ($balloonAlign) $props  .= ' align="' . $balloonAlign . '"';
	if ($balloonName) $props   .= ' name="' . $balloonName . '"';
	if ($balloonCol) $props    .= ' col="' . $balloonCol . '"';
	if ($balloonType) $props   .= ' type="' . $balloonType . '"';
	if ($balloonBorder) $props .= ' border="' . $balloonBorder . '"';
	if ($balloonShape) $props  .= ' icon_shape="' . $balloonShape . '"';

	if ('' !== $spVertical) $props .= ' sp_vertical="1"';

	if ( false !== strpos( $content, '="c-balloon' ) ) {

		// ブログパーツから呼び出された時など、すでに展開済みのもの
		return $content;

	} elseif ( false !== strpos( $content, '[ふきだし' ) ) {

		// 古い状態のブロック
		return do_shortcode( $content );

	} else {

		// 新しい方 : $content には p タグ でテキスト入っていて、クラスもpタグについてしまうことに注意。
		$block_class = 'swell-block-balloon';
		if ( $attrs['className'] ) {
			$block_class .= ' ' . $attrs['className'];
			$content      = str_replace( 'p class="' . $attrs['className'] . '"', 'p', $content );
		}
		$content = '[speech_balloon' . $props . ']' . $content . '[/speech_balloon]';
		return '<div class="' . esc_attr( $block_class ) . '">' . do_shortcode( $content ) . '</div>';
	}

}
