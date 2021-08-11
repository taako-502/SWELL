<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'render_block_loos/full-wide', __NAMESPACE__ . '\render_full_wide', 10, 2 );
function render_full_wide( $block_content, $block ) {
	if ( isset( $block['attrs']['isParallax'] ) && $block['attrs']['isParallax'] ) {
		\SWELL_Theme::$use['rellax'] = true;
		remove_filter( 'render_block_loos/full-wide', __NAMESPACE__ . '\render_full_wide', 10 );
	}
	return $block_content;
}
