<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'do_shortcode_tag', function ( $output, $tag, $attr, $m ) {
	if ( 'ad_tag' === $tag ) {
		\SWELL_Theme::set_use( 'count_CTR', true );
	} elseif ( 'full_wide_content' === $tag ) {
		if ( isset( $args['bgimg'] ) ) {
			\SWELL_Theme::set_use( 'lazysizes', true );
		}
	}
	return $output;
}, 10, 4 );



/**
 * start付きのolタグがあるかどうか
 */
add_filter( 'render_block_core/list', __NAMESPACE__ . '\render_core_list', 10, 2 );
function render_core_list( $block_content, $block ) {
	if ( isset( $block['attrs']['start'] ) ) {
		\SWELL_Theme::set_use( 'ol_start', true );
		remove_filter( 'render_block_core/list', __NAMESPACE__ . '\render_core_list', 10 );
	}
	return $block_content;
}


/**
 * luminousが有効かどうか
 */
add_action('wp', function () {
	if ( ! \SWELL_Theme::get_setting( 'remove_luminous' ) ) {
		add_filter( 'render_block_core/image', __NAMESPACE__ . '\check_luminous', 10, 2 );
		add_filter( 'render_block_core/gallery', __NAMESPACE__ . '\check_luminous', 10, 2 );
	}
});
function check_luminous( $block_content, $block ) {

	$classname = $block['attrs']['className'] ?? '';

	// u-lb-offがあるときはスキップ
	if ( $classname && false !== strpos( $classname, 'u-lb-off' ) ) {
		return $block_content;
	}

	\SWELL_Theme::set_use( 'luminous', true );
	remove_filter( 'render_block_core/image', __NAMESPACE__ . '\check_luminous', 10 );
	remove_filter( 'render_block_core/gallery', __NAMESPACE__ . '\check_luminous', 10 );

	return $block_content;
}


add_filter( 'render_block_loos/button', __NAMESPACE__ . '\render_swell_button', 10, 2 );
function render_swell_button( $block_content, $block ) {
	if ( isset( $block['attrs']['isCount'] ) && $block['attrs']['isCount'] ) {
		\SWELL_Theme::set_use( 'count_CTR', true );
		remove_filter( 'render_block_loos/button', __NAMESPACE__ . '\render_swell_button', 10 );
	}
	return $block_content;
}


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
