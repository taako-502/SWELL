<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Header {

	/**
	 * ヘッダーの境界線
	 */
	public static function header_border( $header_border ) {

		$head_style = [];

		if ( $header_border === 'border' ) {
			$head_style[] = 'border-bottom: solid 1px rgba(0,0,0,.1)';
		} elseif ( $header_border === 'shadow' ) {
			$head_style[] = 'box-shadow: 0 1px 4px rgba(0,0,0,.12)';
		}
		Style::add( '.l-header', $head_style );
	}

	/**
	 * ヘッドバー
	 */
	public static function head_bar( $color_head_bar_bg, $color_head_bar_text, $show_head_border ) {
		$color_head_bar_bg = $color_head_bar_bg ?: 'var(--color_main)';
		Style::add( '.l-header__bar', [
			'color:' . $color_head_bar_text,
			'background:' . $color_head_bar_bg,
		]);

		// ヘッドバーの内容がなくてもボーダーとして表示する（PCのみ）
		if ( $show_head_border ) {
			Style::add( '.l-header', 'border-top: solid 4px ' . $color_head_bar_bg, 'pc' );
		}
	}

	/**
	 * ヘッダー（SP）のレイアウト
	 */
	public static function header_sp_layout( $layout ) {

		switch ( $layout ) {
			case 'center_right':
				$menu_btn   = 'order:3';
				$custom_btn = 'order:1';
				$logo_wrap  = 'order:2;text-align:center';
				$head_inner = '';
				break;
			case 'center_left':
				$menu_btn   = 'order:1';
				$custom_btn = 'order:3';
				$logo_wrap  = 'order:2;text-align:center';
				$head_inner = '';
				break;
			default:
				$menu_btn   = '';
				$custom_btn = '';
				$logo_wrap  = 'margin-right:auto';
				$head_inner = '-webkit-box-pack:end;-webkit-justify-content:flex-end;justify-content:flex-end';
				break;
		}
		Style::add( '.l-header__menuBtn', $menu_btn );
		Style::add( '.l-header__customBtn', $custom_btn );
		Style::add( '.l-header__logo', $logo_wrap, 'sp' );
		Style::add( '.l-header__inner', $head_inner );
	}

	/**
	 * スマホのヘッダーボタン
	 */
	public static function header_menu_btn( $menu_btn_bg, $custom_btn_bg ) {
		if ( $menu_btn_bg !== '' ) {
			Style::add( '.l-header__menuBtn', 'color:#fff;background-color:' . $menu_btn_bg );
		}
		if ( $custom_btn_bg !== '' ) {
			Style::add( '.l-header__customBtn', 'color:#fff;background-color:' . $custom_btn_bg );
		}
	}

	/**
	 * ロゴ画像
	 */
	public static function logo( $logo_size_sp, $logo_size_pc, $logo_size_pcfix ) {
		Style::add_root( '--logo_size_sp', $logo_size_sp . 'px' );
		Style::add_root( '--logo_size_pc', $logo_size_pc . 'px' );
		Style::add_root( '--logo_size_pcfix', $logo_size_pcfix . 'px' );
	}


	/**
	 * グローバルナビ
	 */
	public static function gnav( $color_head_hov, $headmenu_effect, $head_submenu_bg ) {

		$gnav_a_after        = [];
		$sp_head_nav_current = [];

		// ヘッダーメニューボーダー  メイン色かテキスト色か a::afterは親のみ
		if ( $color_head_hov === 'main' ) {
			$gnav_a_after[]        = 'background:var(--color_main)';
			$sp_head_nav_current[] = 'border-bottom-color:var(--color_main)';
		} else {
			$gnav_a_after[]        = 'background:var(--color_header_text)';
			$sp_head_nav_current[] = 'border-bottom-color:var(--color_header_text)';
		}

		$gnav_li_hover_a_after = [];
		// グロナビのホバーエフェクト
		switch ( $headmenu_effect ) {
			case 'line_center':
				$gnav_a_after[]          = 'width:100%;height:2px;transform:scaleX(0)';
				$gnav_li_hover_a_after[] = 'transform: scaleX(1)';
				break;
			case 'line_left':
				$gnav_a_after[]          = 'width:0%;height:2px';
				$gnav_li_hover_a_after[] = 'width:100%';
				break;
			case 'block':
				$gnav_a_after[]          = 'width:100%;height:0px';
				$gnav_li_hover_a_after[] = 'height:6px';
				break;
			case 'bg_gray':
				$gnav_li_hover_a[] = 'background:#f7f7f7;color: #333';
				break;
			case 'bg_light':
				$gnav_li_hover_a[] = 'background:rgba(250,250,250,0.16)';
				break;
			default:
				break;
		}

		if ( ! empty( $gnav_a_after ) ) {
			Style::add( '.c-gnav a::after', $gnav_a_after );
		}
		if ( ! empty( $sp_head_nav_current ) ) {
			Style::add( '.p-spHeadMenu .menu-item.-current', $sp_head_nav_current );
		}
		if ( ! empty( $gnav_li_hover_a ) ) {
			Style::add( ['.c-gnav > li:hover > a', '.c-gnav > .-current > a' ], $gnav_li_hover_a );
		}
		if ( ! empty( $gnav_li_hover_a_after ) ) {
			Style::add( ['.c-gnav > li:hover > a::after', '.c-gnav > .-current > a::after' ], $gnav_li_hover_a_after );
		}

		// サブメニューの色
		$submenu_color = '#333';
		$submenu_bg    = '#fff';
		if ( $head_submenu_bg === 'main' ) {
			$submenu_color = '#fff';
			$submenu_bg    = 'var(--color_main)';
		}
		Style::add( '.c-gnav .sub-menu', [
			'color:' . $submenu_color,
			'background:' . $submenu_bg,
		] );
	}


	/**
	 * お知らせバー
	 */
	public static function info_bar( $SETTING ) {

		$infoBar      = [];
		$infoBar__btn = [];

		// テキスト色
		$infoBar[] = 'color:' . $SETTING['color_info_text'];

		// 背景
		$bgcol_01 = $SETTING['color_info_bg'];
		$bgcol_02 = $SETTING['color_info_bg2'];

		if ( $SETTING['info_bar_effect'] === 'gradation' ) {
			// 背景効果：グラデーション
			if ( $bgcol_02 ) {
				$gradation_bg = 'repeating-linear-gradient(' .
					'100deg,' .
					\SWELL_Theme::get_rgba( $bgcol_01, 1, .1 ) . ' 0,' .
					$bgcol_01 . ' 5%,' .
					$bgcol_02 . ' 95%,' .
					\SWELL_Theme::get_rgba( $bgcol_02, 1, .1 ) . ' 100%' .
				')';
			} else {
				// 背景色02が指定されていなければ、単一色からグラデーションを計算
				$gradation_bg = 'repeating-linear-gradient(' .
					'100deg, ' . $bgcol_01 . ' 0,' .
					\SWELL_Theme::get_rgba( $bgcol_01, 1, .1 ) . ' 10%,' .
					\SWELL_Theme::get_rgba( $bgcol_01, 1, .4 ) . ' 90%,' .
					\SWELL_Theme::get_rgba( $bgcol_01, 1, .5 ) . ' 100%' .
				')';
			}

			$infoBar[] = 'background-image:' . $gradation_bg;
		} else {
			 $infoBar[] = 'background-color:' . $bgcol_01;
		}
		// $head_style .= '.c-infoBar{'. $notice_style .'}';
		Style::add( '.c-infoBar', $infoBar );

		// フォントサイズ
		switch ( $SETTING['info_bar_size'] ) {
			case 'small':
				$fz_tab    = '12px';
				$fz_mobile = '3vw';
				break;
			case 'big':
				$fz_tab    = '16px';
				$fz_mobile = '3.8vw';
				break;
			default:
				$fz_tab    = '14px';
				$fz_mobile = '3.4vw';
				break;
		}
		Style::add( '.c-infoBar__text', 'font-size:' . $fz_mobile );
		Style::add( '.c-infoBar__text', 'font-size:' . $fz_tab, 'tab' );

		// ボタンの色
		$color_info_btn = $SETTING['color_info_btn'] ?: $SETTING['color_main'];
		$infoBar__btn[] = 'background-color:' . $color_info_btn . ' !important';
		Style::add( '.c-infoBar__btn', $infoBar__btn );

	}
}
