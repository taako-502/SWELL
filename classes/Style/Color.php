<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Color {

	/**
	 * カラー変数のセット（フロント & エディターで共通のもの）
	 */
	public static function common( $SETTING, $EDITOR ) {
		$color_main = $SETTING['color_main'];
		$color_text = $SETTING['color_text'];

		Style::add_root( '--color_main', $color_main );
		Style::add_root( '--color_text', $color_text );
		Style::add_root( '--color_link', $SETTING['color_link'] );
		Style::add_root( '--color_border', 'rgba(200,200,200,.5)' );
		Style::add_root( '--color_gray', 'rgba(200,200,200,.15)' );
		Style::add_root( '--color_htag', $SETTING['color_htag'] ?: $SETTING['color_main'] );

		Style::add_root( '--color_bg', $SETTING['color_bg'] );
		Style::add_root( '--color_gradient1', $SETTING['color_gradient1'] );
		Style::add_root( '--color_gradient2', $SETTING['color_gradient2'] );

		Style::add_root( '--color_main_thin', \SWELL_Theme::get_rgba( $SETTING['color_main'], 0.05, 0.25 ) );
		Style::add_root( '--color_main_dark', \SWELL_Theme::get_rgba( $SETTING['color_main'], 1, -.25 ) );

		// リストアイコンの色
		// Style::add_root( '--color_list_dot', $EDITOR['color_list_dot'] ?: $color_text );
		Style::add_root( '--color_list_check', $EDITOR['color_list_check'] ?: $color_main );
		Style::add_root( '--color_list_num', $EDITOR['color_list_num'] ?: $color_main );
		Style::add_root( '--color_list_good', $EDITOR['color_list_good'] );
		Style::add_root( '--color_list_bad', $EDITOR['color_list_bad'] );

		// FAQ
		Style::add_root( '--color_faq_q', $EDITOR['color_faq_q'] );
		Style::add_root( '--color_faq_a', $EDITOR['color_faq_a'] );

		// キャプション付きブロックの色
		for ( $i = 1; $i < 4; $i++ ) {
			Style::add_root( '--color_capbox_0' . $i, $EDITOR[ 'color_cap_0' . $i ] );
			Style::add_root( '--color_capbox_0' . $i . '_bg', $EDITOR[ 'color_cap_0' . $i . '_light' ] );
		}

		// アイコンボックスの色
		foreach ( [
			'color_icon_good',
			'color_icon_good_bg',
			'color_icon_bad',
			'color_icon_bad_bg',
			'color_icon_info',
			'color_icon_info_bg',
			'color_icon_announce',
			'color_icon_announce_bg',
			'color_icon_pen',
			'color_icon_pen_bg',
			'color_icon_book',
			'color_icon_book_bg',
			'color_icon_point',
			'color_icon_check',
			'color_icon_batsu',
			'color_icon_hatena',
			'color_icon_caution',
			'color_icon_memo',
		] as $color_key ) {
			Style::add_root( '--' . $color_key, $EDITOR[ $color_key ] );
		}

		// Style::add_root( '--color_border', $SETTING['color_border'] );
	}

	/**
	 * フロントだけで使うもの
	 */
	public static function front( $SETTING ) {

		// ヘッダーやフッターの色
		Style::add_root( '--color_header_bg', $SETTING['color_header_bg'] );
		Style::add_root( '--color_header_text', $SETTING['color_header_text'] );
		Style::add_root( '--color_footer_bg', $SETTING['color_footer_bg'] );
		Style::add_root( '--color_footer_text', $SETTING['color_footer_text'] );

		if ( $color_footwdgt_bg = $SETTING['color_footwdgt_bg'] ) {
			Style::add( '.l-footer__widgetArea', 'background:' . $color_footwdgt_bg );
		}
		if ( $color_footwdgt_text = $SETTING['color_footwdgt_text'] ) {
			Style::add( '.l-footer__widgetArea', 'color:' . $color_footwdgt_text );
		}

		// その他 :: ここまで変数化するべきか？
		Style::add_root( '--color_fbm_text', $SETTING['color_fbm_text'] );
		Style::add_root( '--color_fbm_bg', $SETTING['color_fbm_bg'] );
		Style::add_root( '--fbm_opacity', $SETTING['fbm_opacity'] );
		Style::add_root( '--fix_header_opacity', $SETTING['fix_header_opacity'] );

	}

}
