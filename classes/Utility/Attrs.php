<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Attrs {

	/**
	 * HTMLタグに付与する属性値
	 */
	public static function root_attrs() {
		$attrs  = 'data-loaded="false"'; // DOM読み込み御用
		$attrs .= ' data-scrolled="false"'; // スクロール制御用
		$attrs .= ' data-spmenu="closed"'; // SPメニュー制御用

		echo apply_filters( 'swell_root_attrs', $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * #body_wrap に付与する属性値
	 */
	public static function body_attrs() {
		$attrs = self::is_use( 'pjax' ) ? 'data-barba="wrapper"' : '';
		echo apply_filters( 'swell_body_attrs', $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * #content に付与する属性値
	 */
	public static function content_attrs() {
		$attrs = '';
		if ( is_single() || is_page() || ( ! is_front_page() && is_home() ) ) {
			$the_id = get_queried_object_id();
			$attrs .= ' data-postid="' . $the_id . '"';
		}

		$is_bot = self::is_bot() || is_robots();
		if ( is_singular( self::$post_types_for_pvct ) && ! is_user_logged_in() && ! $is_bot ) {
			$attrs .= ' data-pvct="true"';
		}

		echo trim( apply_filters( 'swell_content_attrs', $attrs ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * #lp-content に付与する属性値
	 */
	public static function lp_content_attrs() {
		$attrs = 'data-postid="' . get_queried_object_id() . '"';
		echo trim( apply_filters( 'swell_lp_content_attrs', $attrs ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
