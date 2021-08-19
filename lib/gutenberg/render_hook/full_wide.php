<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'render_block_loos/full-wide', __NAMESPACE__ . '\render_full_wide', 10, 2 );
function render_full_wide( $block_content, $block ) {
	if ( ! \SWELL_Theme::is_use( 'rellax' ) ) {
		if ( isset( $block['attrs']['isParallax'] ) && $block['attrs']['isParallax'] ) {
			\SWELL_Theme::set_use( 'rellax', true );
			// remove_filter( 'render_block_loos/full-wide', __NAMESPACE__ . '\render_full_wide', 10 );
		}
	}

	if ( ! \SWELL_Theme::is_use( 'lazysizes' ) ) {
		if ( isset( $block['attrs']['bgImageUrl'] ) && $block['attrs']['bgImageUrl'] ) {
			\SWELL_Theme::set_use( 'lazysizes', true );
			// remove_filter( 'render_block_loos/full-wide', __NAMESPACE__ . '\render_full_wide', 10 );
		}
	}

	return $block_content;
}
