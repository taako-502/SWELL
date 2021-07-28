<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 関連記事ブロック
 */
$asset = include T_DIRE . '/build/blocks/post-link/index.asset.php';
wp_register_script(
	'swell-block/post-link',
	T_DIRE_URI . '/build/blocks/post-link/index.js',
	array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
	SWELL_VERSION,
	true
);

register_block_type_from_metadata( T_DIRE . '/src/gutenberg/blocks/post-link', [
	// 'style' => "swell-block/post-link",
	'editor_script'   => 'swell-block/post-link',
	'render_callback' => __NAMESPACE__ . '\cb_post_link',
] );

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

	$link_style  = $isText ? 'text' : 'card';
	$block_class = 'swell-block-postLink';
	if ( $className ) {
		$block_class .= ' ' . $className;
	}

	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	ob_start();
	echo '<div class="' . esc_attr( $block_class ) . '" data-style="' . esc_attr( $link_style ) . '">';
	if ( $externalUrl ) {
		echo \SWELL_Theme::get_external_blog_card( $externalUrl, $card_args, $isText );
	} elseif ( $postId ) {
		echo \SWELL_Theme::get_internal_blog_card( $postId, $card_args, $isText );
	}
	echo '</div>';
	return ob_get_clean();
}
