<?php
namespace SWELL_Theme\Style;

use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Title {

	/**
	 * セクションタイトル
	 */
	public static function section( $sec_title_style ) {

		$styles = Widget::get_widget_title_style( $sec_title_style );

		Style::add( '.c-secTitle', $styles['main'] );
		Style::add( '.c-secTitle::before', $styles['before'] );
		Style::add( '.c-secTitle::after', $styles['after'] );
	}

}
