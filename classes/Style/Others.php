<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Others {

	/**
	 * SPメニュー
	 */
	public static function spmenu() {
		$SETTING = \SWELL_FUNC::get_setting();
		Style::add( '.p-spMenu', 'color:' . $SETTING['color_spmenu_text'] );
		Style::add( '.p-spMenu__inner::before', [
			'background:' . $SETTING['color_spmenu_bg'],
			'opacity:' . $SETTING['spmenu_opacity'],
		] );
		Style::add( '.p-spMenu__overlay', [
			'background:' . $SETTING['color_menulayer_bg'],
			'opacity:' . $SETTING['menulayer_opacity'],
		] );
	}


	/**
	 * サイドバー
	 */
	public static function sidebar( $sidebar_pos ) {
		if ( $sidebar_pos === 'left' ) {
			Style::add( '#main_content', 'order:2', 'pc' );
			Style::add( '#sidebar', 'order:1', 'pc' );
		}
	}


	/**
	 * ページャー
	 */
	public static function pager( $pager_shape, $pager_style ) {

		$pager_style = ( $pager_shape === 'circle' ) ? ['border-radius:50%;margin:4px' ] : [];

		if ( $pager_style === 'bg' ) {
			$pager_style[] = 'color:#fff;background-color:#dedede';
		} else {
			$pager_style[] = 'color:var(--color_main);border: solid 1px var(--color_main)';
		}

		Style::add( '[class*="page-numbers"]', $pager_style );
	}

	/**
	 * リンクに下線つけるかどうか
	 */
	public static function link( $show_link_underline ) {
		if ( $show_link_underline ) {
			Style::add( ['.post_content a:not([class])', '.term_description a' ], 'text-decoration: underline' );
		}
	}

}
