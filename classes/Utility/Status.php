<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Status {

	/**
	 * WodPressのバージョンチェック
	 */
	public static function wpver_is_above( $version ) {
		global $wp_version;
		return ( version_compare( $wp_version, $version . '-beta' ) >= 0 );
	}


	/**
	 * $use の値を取得
	 */
	public static function is_use( $key ) {
		if ( ! isset( self::$use[ $key ] ) ) {
			return false;
		}
		return self::$use[ $key ];
	}


	/**
	 * モバイル判定
	 */
	public static function is_mobile() {

		$ua = \SWELL_Theme::$user_agent;

		// iPhone
		if (strpos( $ua, 'iphone' ) !== false) return true;
		// Android Mobile
		if (strpos( $ua, 'android' ) !== false && strpos( $ua, 'mobile' ) !== false) return true;
		// BlackBerry
		if (strpos( $ua, 'blackberry' ) !== false) return true;
		// FireFox Mobile
		if (strpos( $ua, 'firefox' ) !== false && strpos( $ua, 'mobile' ) !== false) return true;
		// Win mobile
		if (strpos( $ua, 'windows' ) !== false && strpos( $ua, 'phone' ) !== false) return true;

		return false;
	}


	/**
	 * モバイル判定 の結果を IS_MOBILE に渡すための関数
	 * customize_previewed_device はカスタマイザーのプレビュー画面で受け取れる値
	 */
	public static function get_is_mobile() {

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$customize_previewed_device = '';
		if ( isset( $_GET['customize_previewed_device'] ) ) {
			$customize_previewed_device = $_GET['customize_previewed_device'];

		}
		return self::is_mobile() || 'mobile' === $customize_previewed_device;
	}


	/**
	 * Android判定
	 */
	public static function is_android() {
		$is_android = strpos( \SWELL_Theme::$user_agent, 'android' ) !== false;
		return (bool) $is_android;
	}


	/**
	 * ボット判定
	 */
	public static function is_bot() {
		$bots = [
			'googlebot',            // Google
			'yahoo',                // Yahoo
			'bingbot',              // Bing
			'msnbot',               // = Bingbot
			'baiduspider',          // Baidu
			'yeti',                 // NAVER
			'naverbot',             // NAVER
			'livedoor',             // Livedoor
			'twitterbot',           // Twitter
			'facebookexternalhit',  // Facebook
			'pinterest',            // Pinterest
			'tumblr',               // Tumblr
			'feedly',               // Feedly
		];
		foreach ( $bots as $bot ) {
			if ( stripos( \SWELL_Theme::$user_agent, $bot ) !== false ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * トップページかどうか (フロントページの存在しない場合の投稿ページまで考慮)
	 */
	public static function is_top() {
		if ( is_front_page() ) {
			return true;
		} elseif ( is_home() && 0 === get_queried_object_id() ) {
			return true;
		}
		return false;
	}

	/**
	 * タームアーカイブページかどうか
	 */
	public static function is_term() {
		return is_category() || is_tag() || is_tax();
	}


	/**
	 * ウィジェットブロック化が有効かどうか
	 */
	public static function use_widgets_block() {

		if ( ! function_exists( '\wp_use_widgets_block_editor' ) ) {
			return false;
		}
		return wp_use_widgets_block_editor();
	}


	/**
	 * RESTリクエスト中かどうか
	 */
	public static function is_rest() {
		return ( defined( 'REST_REQUEST' ) && REST_REQUEST );
	}

	/**
	 * ウィジェットプレビューiframeの中かどうか
	 */
	public static function is_iframe() {
		return ( defined( 'IFRAME_REQUEST' ) && IFRAME_REQUEST );
	}

	/**
	 * ウィジェットプレビューiframeの中かどうか
	 */
	public static function is_widget_iframe() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return ! empty( $_GET['legacy-widget-preview'] );
	}


}
