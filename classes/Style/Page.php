<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Page {

	/**
	 * タイトル横の日付
	 */
	public static function title_date( $show_title_date, $show_title_date_sp ) {
		if ( ! $show_title_date ) style::add( '.single .c-postTitle__date', 'display:none', 'pc' );
		if ( ! $show_title_date_sp ) style::add( '.single .c-postTitle__date', 'display:none', 'sp' );
	}


	/**
	 * タイトル背景
	 */
	public static function title_bg( $ttlbg_overlay_color, $ttlbg_overlay_opacity ) {
		style::add( '.l-topTitleArea.c-filterLayer::before', [
			'background-color:' . $ttlbg_overlay_color,
			'opacity:' . $ttlbg_overlay_opacity,
		]);
	}


	/**
	 * 記事のフォントサイズ
	 */
	public static function font_size( $fz_sp, $fz_pc ) {
		style::add( ['#main_content .post_content', '#lp-content .post_content' ], 'font-size:' . $fz_sp );
		style::add( ['#main_content .post_content', '#lp-content .post_content' ], 'font-size:' . $fz_pc, 'tab' );
	}


	/**
	 * 目次 toc_dotcol_main
	 */
	public static function toc( $toc_before_color, $toc_before_custom_color ) {
		if ( 'main' === $toc_before_color ) {
			style::add( ['.p-toc__list.is-style-index li::before' ], 'color:var(--color_main)' );
		} elseif ( 'custom' === $toc_before_color ) {
			style::add( ['.p-toc__list.is-style-index li::before' ], 'color:' . $toc_before_custom_color );
		}
	}


	/**
	 * 目次広告
	 */
	public static function toc_ad( $show_alone_post, $show_alone_page ) {
		if ( ! $show_alone_post ) {
			style::add( '.single.-index-off .w-beforeToc', 'display:none' );
		}
		if ( ! $show_alone_page ) {
			style::add( '.page.-index-off .w-beforeToc', 'display:none' );
		}
	}


	/**
	 * シェアボタン
	 */
	public static function share_btn( $share_btn_style ) {
		$btn_mr  = '';
		$btn_css = [];
		switch ( $share_btn_style ) {
			case 'btn':
			case 'btn-small':
				$btn_mr    = '8px';
				$btn_css[] = 'padding:6px 8px;border-radius:2px';
				break;
			case 'icon':
			case 'box':
				// 共通のスタイル
				style::add( '.c-shareBtns__btn:not(:hover)', 'background:none' );
				style::add( '.-fix .c-shareBtns__btn:not(:hover)', 'background:#fff' );
				style::add( '.c-shareBtns__btn:not(:hover) .c-shareBtns__icon', 'color:inherit' );

				$btn_mr    = '8px';
				$btn_css[] = 'padding:8px 0;transition:background-color .25s';

				// 個別のスタイル
				if ( 'icon' === $share_btn_style ) {
					$btn_css[] = 'box-shadow:none!important';
					style::add( '.c-shareBtns__list', [
						'padding: 8px 0',
						'border-top: solid 1px var(--color_border)',
						'border-bottom: solid 1px var(--color_border)',
					] );
				} else {
					$btn_css[] = 'border: solid 1px';
				}
				break;
			default:
				// block
				$btn_mr    = '4px';
				$btn_css[] = 'padding:8px 0';
				break;
		}
		style::add( '.c-shareBtns__item:not(:last-child)', 'margin-right:' . $btn_mr );
		style::add( '.c-shareBtns__btn', $btn_css );
	}

}
