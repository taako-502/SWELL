<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Body {

	/**
	 * フォントファミリー
	 * "Segoe UI" は 500でboldになってしまうので游ゴシックでは使えない
	 */
	public static function font( $body_font_family ) {
		$font_weight = '400';
		$font_family = 'sans-serif';
		switch ( $body_font_family ) {
			case 'yugo':
				$font_weight = '500';
				$font_family = '"游ゴシック体", "Yu Gothic", YuGothic, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif';
				break;
			case 'hirago':
				$font_family = '"Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif';
				break;
			case 'notosans':
				$font_family = '"Noto Sans JP", sans-serif';
				break;
			case 'serif':
				$font_family = '"Noto Serif JP", "Hiragino Mincho ProN", serif';
				break;
			default:
				break;
		}
		Style::add( 'body', [
			'font-weight:' . $font_weight,
			'font-family:' . $font_family,
		] );
	}


	/**
	 * コンテンツサイズ
	 */
	public static function content_size( $container_size, $article_size ) {
		$container_size = (int) $container_size + 96;
		$article_size   = (int) $article_size + 64;
		Style::add_root( '--container_size', $container_size . 'px' );
		Style::add_root( '--article_size', $article_size . 'px' );
	}


	/**
	 * 背景
	 */
	public static function bg( $SETTING ) {

		if ( $SETTING['body_bg'] === '' ) return;

		// backgroundプロパティ生成
		$body_bg_repeat = ( $SETTING['noloop_body_bg'] ) ? 'no-repeat' : 'repeat';
		$body_bg_prop   = 'url(' . $SETTING['body_bg'] . ') ' . $body_bg_repeat . ' ' . $SETTING['body_bg_pos_x'] . ' ' . $SETTING['body_bg_pos_y'];
		if ( $SETTING['body_bg_size'] !== '' ) {
			$body_bg_prop .= ' / ' . $SETTING['body_bg_size'];
		}

		// fixかどうかで変える
		$selector = $SETTING['fix_body_bg'] ? '#body_wrap::before ' : '#body_wrap';

		Style::add( $selector, 'background:' . $body_bg_prop );
		if ( $SETTING['body_bg_sp'] !== '' ) {
			// spの画像が指定されていれば、imageだけ上書き
			Style::add( $selector, 'background-image:url(' . $SETTING['body_bg_sp'] . ')', 'sp' );
		}
	}


	/**
	 * フレーム
	 */
	public static function frame( $frame_class ) {
		// frame-on は 2パターンあるので、 !frame-off で判定
		if ( '-frame-off' !== $frame_class ) Style::add_module( '-frame-on' );
	}


	/**
	 * Radisu
	 */
	public static function radius( $sidettl_type, $h2_type, $frame_class ) {

		// 2px丸める
		$maru2 = [];

		if ( 'fill' === $sidettl_type && '-frame-off' !== $frame_class ) {
			$maru2[] = '.c-widget__title';
		}
		if ( 'block' === $h2_type ) {
			$maru2[] = '.post_content h2';
		}
		Style::add( $maru2, 'border-radius:2px' );

	}
}
