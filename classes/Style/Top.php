<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Top {

	public static function init() {

		$SETTING = \SWELL_Theme::get_setting();

		// コンテンツ上の余白量
		Style::add( '.top #content', 'padding-top:' . $SETTING['top_content_mt'] );

		// ヘッダーの透過設定
		if ( $SETTING['header_transparent'] !== 'no' ) {
			Style::add_module( '-top-header' );
		}

		// MV
		if ( $SETTING['main_visual_type'] !== 'none' ) {
			self::mv( $SETTING );
			Style::add_module( '-main-visual' );
		};

		// 記事スライダー
		if ( $SETTING['show_post_slide'] !== 'off' ) {
			self::post_slider( $SETTING );
			Style::add_module( '-post-slider' );
		}
	}


	/**
	 * メインビジュアル
	 */
	public static function mv( $SETTING ) {
		// 高さ
		$mv_slide_height_sp = 'auto';
		$mv_slide_height_pc = 'auto';
		if ( 'set' === $SETTING['mv_slide_size'] ) {
			$mv_slide_height_pc = $SETTING['mv_slide_height_pc'];
			$mv_slide_height_sp = $SETTING['mv_slide_height_sp'];
		}
		Style::add( '.p-mainVisual__inner', 'height:' . $mv_slide_height_sp );
		Style::add( '.p-mainVisual__inner', 'height:' . $mv_slide_height_pc, 'pc' );

		// メボタンの丸み
		Style::add_root( '--mv_btn_radius', $SETTING['mv_btn_radius'] . 'px' );

		// スライダーアニメーション
		Style::add_root( '--mv_slide_animation', $SETTING['mv_slide_animation'] );

		// オーバレイカラー
		Style::add(
			'.p-mainVisual .c-filterLayer::before',
			[
				'background-color:' . $SETTING['mv_overlay_color'],
				'opacity:' . $SETTING['mv_overlay_opacity'],
			]
		);

		// ページネーション表示時は、スライダーのscrollを少し上にずらす
		if ( $SETTING['mv_on_pagination'] ) {
			Style::add( '.-type-slider .p-mainVisual__scroll', 'padding-bottom: 16px' );
		}
	}

	/**
	 * 記事スライダー
	 */
	public static function post_slider( $SETTING ) {

		$ps_style     = [];
		$ps_style_tab = [];

		// 背景色
		$ps_bg_color = $SETTING['ps_bg_color'];
		if ( $ps_bg_color ) {
			$ps_style[] = 'background-color:' . $ps_bg_color;
		}

		// 文字色
		$pickup_font_color = $SETTING['pickup_font_color'];
		if ( $pickup_font_color ) {
			$ps_style[] = 'color:' . $pickup_font_color;
		}

		// 上下の余白量
		switch ( $SETTING['pickup_pad_tb'] ) {
			case 'small':
				$ps_style[] = 'padding-top:16px';
				$ps_style[] = 'padding-bottom:16px';
				break;
			case 'middle':
				$ps_style[]     = 'padding-top:5vw';
				$ps_style[]     = 'padding-bottom:5vw';
				$ps_style_tab[] = 'padding-top:40px';
				$ps_style_tab[] = 'padding-bottom:40px';
				break;
			case 'wide':
				$ps_style[]     = 'padding-top:8vw';
				$ps_style[]     = 'padding-bottom:8vw';
				$ps_style_tab[] = 'padding-top:64px';
				$ps_style_tab[] = 'padding-bottom:64px';
				break;
			default:
				break;
		}

		Style::add( '.p-postSlider', $ps_style );
		Style::add( '.p-postSlider', $ps_style_tab, 'tab' );

		// 背景画像 & 不透明度
		$post_slider__before = ( $SETTING['bg_pickup'] === '' )
			? 'content:none'
			: 'opacity: ' . $SETTING['ps_img_opacity'] . ';background-image: url(' . $SETTING['bg_pickup'] . ')';
		Style::add( '#post_slider::before', $post_slider__before );

		// スライド間の余白
		if ( ! $SETTING['ps_no_space'] ) {
			$ps_space = '8px';
		} else {
			$ps_space = '0';
			Style::add( '#post_slider .p-postList__thumb', 'box-shadow:none;border-radius:0' );
			Style::add( '#post_slider .p-postList__link', 'border-radius:0' );
		}
		Style::add_root( '--ps_space', $ps_space );

		// その他
		$post_slider         = [];
		$ps_swiper_container = [];
		if ( $SETTING['ps_on_pagination'] ) {
			// ページネーションがあればpaddingつける
			$ps_swiper_container[] = 'padding-bottom:24px';
		}

		// 左右の余白量
		switch ( $SETTING['pickup_pad_lr'] ) {
			case 'small': // 左右に少し余白あり
				Style::add( '#post_slider', 'padding-left:40px;padding-right:40px', 'pc' );
				break;
			case 'wide': // コンテンツ幅に収める
				if ( ! $SETTING['ps_no_space'] ) {
					$ps_swiper_container[] = 'margin-left:-8px;margin-right:-8px;';
				}
				break;
			default:
				break;
		}

		Style::add( '#post_slider .swiper-container', $ps_swiper_container );

	}

}
