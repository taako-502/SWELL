<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Footer {

	/**
	 * ページトップボタン
	 */
	public static function pagetop_btn( $pagetop_style ) {
		if ( $pagetop_style === 'fix_circle' ) {
			Style::add( '#pagetop', 'border-radius:50%' );
		}
	}

	/**
	 * 目次ボタン
	 */
	public static function index_btn( $index_btn_style ) {
		if ( $index_btn_style === 'circle' ) {
			Style::add( '#fix_index_btn', 'border-radius:50%' );
		}
	}

	/**
	 * 固定フッターメニューが表示される場合の固定ボタンたち
	 */
	public static function fix_menu_btns( $show_fbm_pagetop, $show_fbm_index ) {

		if ( $show_fbm_pagetop ) {
			Style::add( '#pagetop', 'display:none', 'sp' );
		}
		if ( $show_fbm_index ) {
			Style::add( '#fix_index_btn', 'display:none', 'sp' );
		}
	}

}
