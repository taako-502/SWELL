<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 関連記事ブロック
 */
$block_name = 'post-link';
$handle     = 'swell-block/' . $block_name;
wp_register_script(
	$handle,
	T_DIRE_URI . '/build/blocks/' . $block_name . '/index.js',
	['swell_blocks' ],
	SWELL_VERSION,
	true
);

register_block_type(
	'loos/post-link',
	[
		'editor_script'   => $handle,
		'attributes'      => [
			'className' => [
				'type'    => 'string',
				'default' => '',
			],
			'postTitle' => [
				'type'    => 'string',
				'default' => '',
			],
			'postId' => [
				'type'    => 'string',
				'default' => '',
			],
			'cardCaption' => [
				'type'    => 'string',
				'default' => '',
			],
			'isNewTab' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'externalUrl' => [
				'type'    => 'string',
				'default' => '',
			],
			'isPreview' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'rel' => [
				'type'    => 'string',
				'default' => '',
			],
			'hideImage' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'hideExcerpt' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'isText' => [
				'type'    => 'boolean',
				'default' => false,
			],

		],
		'render_callback' => 'SWELL_THEME\Block\cb_post_link',
	]
);

function cb_post_link( $attrs ) {

	$className   = $attrs['className'];
	$postId      = $attrs['postId'];
	$cardCaption = $attrs['cardCaption'];
	$isNewTab    = $attrs['isNewTab'];
	$externalUrl = $attrs['externalUrl'];
	$rel         = $attrs['rel'];
	$hideImage   = $attrs['hideImage'];
	$hideExcerpt = $attrs['hideExcerpt'];
	$isText      = $attrs['isText'];

	$card_args = [
		'caption'   => $cardCaption,
		'is_blank'  => $isNewTab,
		'rel'       => $rel,
		'noimg'     => $hideImage,
		'nodesc'    => $hideExcerpt,
	];
	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

	$link_style  = $isText ? 'text' : 'card';
	$block_class = 'swell-block-postLink';
	if ( $className ) {
		$block_class .= ' ' . $className;
	}

	ob_start();
	echo '<div class="' . esc_attr( $block_class ) . '" data-style="' . esc_attr( $link_style ) . '">';
	if ( $externalUrl ) {
		echo \SWELL_FUNC::get_external_blog_card( $externalUrl, $card_args, $isText );
	} elseif ( $postId ) {
		echo \SWELL_FUNC::get_internal_blog_card( $postId, $card_args, $isText );
	}
	echo '</div>';
	return ob_get_clean();
}
