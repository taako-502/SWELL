<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * テンプレートパーツ取得メソッド
 */
trait Parts {

	/**
	 * 関数で呼び出すパーツ
	 */
	public static function pluggable_parts( $name, $args = [] ) {
		$func = 'swl_parts__' . $name;
		if ( function_exists( $func ) ) {
			$func( $args );
		}
	}

	/**
	 * pluggable_parts 取得版
	 */
	public static function get_pluggable_parts( $name, $args = [] ) {
		ob_start();
		self::pluggable_parts( $name, $args );
		return ob_get_clean();
	}


	/**
	 * lazyload画像
	 */
	public static function lazyimg( $args = [] ) {
		$src         = $args['src'] ?? '';
		$alt         = $args['alt'] ?? '';
		$class       = $args['class'] ?? '';
		$placeholder = $args['placeholder'] ?? self::$placeholder;

		if ( self::is_rest() || self::is_iframe() ) {
			echo '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" class="' . esc_attr( $class ) . '">';
		} else {
			$class .= ' lazyload';
			echo '<img src="' . esc_url( $placeholder ) . '" data-src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" class="' . esc_attr( $class ) . '">';
		}
	}

}
