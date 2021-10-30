<?php
namespace SWELL_Theme\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * テーブルブロック
 */
add_filter( 'render_block_core/table', __NAMESPACE__ . '\render_table', 10, 2 );
function render_table( $block_content, $block ) {
	$attrs     = $block['attrs'] ?? [];
	$innerHTML = $block['innerHTML'] ?? '';
	$className = $attrs['className'] ?? '';

	$props = '';

	// 横スクロール
	$scrollable = '';
	if ( isset( $attrs['swlScrollable'] ) ) {
		$scrollable = $attrs['swlScrollable'];
	} elseif ( false !== strpos( $className, 'sp_scroll_' ) ) {
		$scrollable = 'sp';
	}

	if ( $scrollable ) {
		$props .= ' data-table-scrollable="' . esc_attr( $scrollable ) . '"';

		// 一列目の固定表示
		$swlIsFixedLeft = $attrs['swlIsFixedLeft'] ?? false;
		if ( $swlIsFixedLeft ) {
			$props .= ' data-cell1-fixed="' . esc_attr( $scrollable ) . '"';
		}

		// スクロールヒント
		$hint_class    = 'both' !== $scrollable ? " {$scrollable}_" : '';
		$hint_src      = '<div class="c-scrollHint' . esc_attr( $hint_class ) . '"><span>スクロールできます <i class="icon-more_arrow"></i></span></div>';
		$block_content = apply_filters( 'swell_table_scroll_hint', $hint_src ) . $block_content;

		// tableの幅
		$max_width = $attrs['swlMaxWidth'] ?? 800;
		$ex_style  = "width:{$max_width}px;max-width:{$max_width}px";

		$table_has_style = preg_match( '/<table[^>]*style="([^"]*)"[^>]*>/', $innerHTML, $style_matches );
		if ( $table_has_style ) {
			$ex_style = $ex_style . ';' . $style_matches[1];
		}
		$block_content = str_replace( '<table', '<table style="' . esc_attr( $ex_style ) . '"', $block_content );
	}

	// ヘッダー固定
	$theadfix = '';
	if ( isset( $attrs['swlFixedHead'] ) ) {
		$theadfix = $attrs['swlFixedHead'];
	} elseif ( false !== strpos( $className, 'sp_fix_thead_' ) ) {
		$theadfix = 'sp';
	}
	if ( $theadfix ) {
		$props .= ' data-theadfix="' . esc_attr( $theadfix ) . '"';
		\SWELL_Theme::set_use( 'fix_thead', true );
	}

	if ( $props ) {
		// セル内自由になればpreg_replaceで回数指定。
		$block_content = str_replace( '<figure', '<figure' . $props, $block_content );
	}

	return $block_content;
}
