<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'render_block_core/list', __NAMESPACE__ . '\render_list', 10, 2 );
function render_list( $block_content, $block ) {
	if ( isset( $block['attrs']['start'] ) ) {
		\SWELL_Theme::$use['ol_start'] = true;
		remove_filter( 'render_block_core/list', __NAMESPACE__ . '\render_list', 10 );
	}
	return $block_content;
}
