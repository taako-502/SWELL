<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * テーブルブロック
 */
add_filter( 'render_block_core/table', __NAMESPACE__ . '\render_table', 10, 2 );
function render_table( $block_content, $block ) {
	$atts      = $block['attrs'] ?? [];
	$className = $atts['className'] ?? '';

	$props = '';

	// 横スクロール
	$scrollable = '';
	if ( isset( $atts['swlScrollable'] ) ) {
		$scrollable = $atts['swlScrollable'];
	} elseif ( false !== strpos( $className, 'sp_scroll_' ) ) {
		$scrollable = 'sp';
	}

	if ( $scrollable ) {
		$props .= ' data-table-scrollable="' . esc_attr( $scrollable ) . '"';

		// 一列目の固定表示
		$swlIsFixedLeft = $atts['swlIsFixedLeft'] ?? false;
		if ( $swlIsFixedLeft ) {
			$props .= ' data-cell1-fixed="' . esc_attr( $scrollable ) . '"';
		}

		// スクロールヒント
		$hint_class    = 'both' !== $scrollable ? " {$scrollable}_" : '';
		$hint_src      = '<div class="c-scrollHint' . esc_attr( $hint_class ) . '"><span>スクロールできます <i class="icon-more_arrow"></i></span></div>';
		$block_content = apply_filters( 'swell_table_scroll_hint', $hint_src ) . $block_content;

		// tableの幅
		$max_width     = $atts['swlMaxWidth'] ?? 800;
		$width_style   = "width:{$max_width}px;max-width:{$max_width}px";
		$block_content = str_replace( '<table', '<table style="' . esc_attr( $width_style ) . '"', $block_content );
	}

	// ヘッダー固定
	$theadfix = '';
	if ( isset( $atts['swlFixedHead'] ) ) {
		$theadfix = $atts['swlFixedHead'];
	} elseif ( false !== strpos( $className, 'sp_fix_thead_' ) ) {
		$theadfix = 'sp';
	}
	if ( $theadfix ) {
		$props .= ' data-theadfix="' . esc_attr( $theadfix ) . '"';
	}

	if ( $props ) {
		// セル内自由になればpreg_replaceで回数指定。
		$block_content = str_replace( '<figure', '<figure' . $props, $block_content );
	}

	return $block_content;
}
