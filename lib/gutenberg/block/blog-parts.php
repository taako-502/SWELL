<?php
namespace SWELL_THEME\Block;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ブログパーツブロック
 */
$asset = include T_DIRE . '/build/blocks/blog-parts/index.asset.php';
wp_register_script(
	'swell-block/blog-parts',
	T_DIRE_URI . '/build/blocks/blog-parts/index.js',
	array_merge( $asset['dependencies'], [ 'swell_blocks' ] ),
	SWELL_VERSION,
	true
);

register_block_type_from_metadata( T_DIRE . '/src/gutenberg/blocks/blog-parts', [
	'editor_script'   => 'swell-block/blog-parts',
	'render_callback' => 'SWELL_THEME\Block\cb_blog_parts',
]);

function cb_blog_parts( $attrs ) {

	$parts_id = $attrs['partsID'] ?: 0;
	$content  = \SWELL_Theme::get_blog_parts_content( [ 'id' => $parts_id ] );

	$bp_class = 'p-blogParts post_content';

	if ( \SWELL_Theme::is_rest() ) {
		// エディター上のプレビュー表示
		$content = apply_filters( 'the_content', $content );
	} else {
		$content = do_blocks( do_shortcode( $content ) );
	}

	return '<div class="' . esc_attr( $bp_class ) . '" data-partsID="' . esc_attr( $parts_id ) . '">' . $content . '</div>';
}
