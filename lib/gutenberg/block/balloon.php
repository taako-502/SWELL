<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ふきだしブロック
 */
$asset = include T_DIRE . '/build/blocks/post-link/index.asset.php';
wp_register_script(
	'swell-block/balloon',
	T_DIRE_URI . '/build/blocks/balloon/index.js',
	array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
	SWELL_VERSION,
	true
);

register_block_type_from_metadata( T_DIRE . '/src/gutenberg/blocks/balloon', [
	'editor_script'   => 'swell-block/balloon',
	'render_callback' => 'SWELL_THEME\Block\cb_balloon',
] );

function cb_balloon( $attrs, $content = '' ) {

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

		// 新:  $content には p タグ でテキスト入っている
		$block_class = 'swell-block-balloon';
		if ( $attrs['className'] ) {
			$block_class .= ' ' . $attrs['className'];

			// API v1 時代のものは $content の pタグにクラスがついているので消す
			$content = str_replace( 'p class="' . $attrs['className'] . '"', 'p', $content );
		}
		$content = '[speech_balloon' . $props . ']' . $content . '[/speech_balloon]';
		return '<div class="' . esc_attr( $block_class ) . '">' . do_shortcode( $content ) . '</div>';
	}
}
