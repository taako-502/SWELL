<?php
namespace SWELL_THEME;
if ( ! defined( 'ABSPATH' ) ) exit;

class Gutenberg {

	private static $instance;

	//外部からインスタンス化させない
	private function __construct() {}

	// インスタンスを取得関数
	public static function get_instance() {
		if ( isset( self::$instance ) ) return self::$instance;
		return false;
	}


	/**
	 * init
	 */
	public static function init() {

		if ( isset( self::$instance ) ) return;
		self::$instance = new Gutenberg();


	}

}
