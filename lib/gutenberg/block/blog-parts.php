<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ブログパーツブロック
 */
$block_name = 'blog-parts';
$handle     = 'swell-block/' . $block_name;
wp_register_script(
	$handle,
	T_DIRE_URI . '/build/blocks/' . $block_name . '/index.js',
	['swell_blocks' ],
	SWELL_VERSION,
	true
);

register_block_type(
	'loos/blog-parts',
	[
		'editor_script'   => $handle,
		'attributes'      => [
			'className' => [
				'type'    => 'string',
				'default' => '',
			],
			'partsTitle' => [
				'type'    => 'string',
				'default' => '',
			],
			'partsID' => [
				'type'    => 'string',
				'default' => '',
			],
		],
		'render_callback' => 'SWELL_THEME\Block\cb_blog_parts',
	]
);

function cb_blog_parts( $attrs ) {

	$parts_id = $attrs['partsID'] ?: 0;
	$content  = \SWELL_FUNC::get_blog_parts_content( [
		'id'    => $parts_id,
	] );

	$bp_class = 'p-blogParts post_content';
	if ( $attrs['className'] ) {
		$bp_class .= ' ' . $attrs['className'];
	}

	if ( \SWELL_Theme::is_rest() ) {
		// エディター上のプレビュー表示
		$content = apply_filters( 'the_content', $content );
	} else {
		$content = do_blocks( do_shortcode( $content ) );
	}

	return '<div class="' . esc_attr( $bp_class ) . '" data-partsID="' . esc_attr( $parts_id ) . '">' . $content . '</div>';

}
