<?php
namespace SWELL_THEME\Customizer;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * $wp_customize->selective_refresh->add_partialのコールバックを集めたクラス
 */
class Partial {

	private function __construct() {}

	/**
	 * ヘッダーロゴ
	 */
	public static function head_logo() {
		$logo = \SWELL_PARTS::head_logo();
		return $logo;
	}

	/**
	 * パンくず
	 */
	public static function breadcrumb() {
		ob_start();
		\SWELL_Theme::get_parts( 'parts/breadcrumb' );
		return ob_get_clean();
	}
}
