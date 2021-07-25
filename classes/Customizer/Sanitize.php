<?php
namespace SWELL_THEME\Customizer;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * カスタマイザーで使用するサニタイズ関数たち
 */
class Sanitize {

	private function __construct() {}

	/**
	 * controlのtypeからサニタイズ関数の名前を取得
	 */
	public static function get_sanitize_name( $type ) {

		switch ( $type ) {
			case 'checkbox':
				return ['\SWELL_THEME\Customizer\Sanitize', 'checkbox' ];
				break;
			case 'radio':
			case 'select':
				return ['\SWELL_THEME\Customizer\Sanitize', 'select' ];
				break;
			case 'number':
				return ['\SWELL_THEME\Customizer\Sanitize', 'float' ];
				break;
			case 'image':
				return ['\SWELL_THEME\Customizer\Sanitize', 'image' ];
				break;
			case 'color':
				return 'sanitize_hex_color';
				break;
			default: // text | textarea
				return 'wp_kses_post';
				break;
		}
	}


	/**
	 * 数値 int
	 */
	public static function int( $input ) {
		return intval( $input );
	}


	/**
	 * 数値 float
	 */
	public static function float( $input ) {
		return floatval( $input );
	}


	/**
	 * リミット付き数値
	 */
	// public static function number_range( $number, $setting ) {
	// 	$number = absint( $number );
	// 	$atts = $setting->manager->get_control( $setting->id )->input_attrs;
	// 	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	// 	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	// 	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	// 	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
	// }


	/**
	 * チェックボックス用サニタイズ関数
	 */
	public static function checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}


	/**
	 * ラジオボタン & セレクトボックス用サニタイズ関数
	 */
	public static function select( $input, $setting ) {
		// $input = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}


	/**
	 * ファイルアップローダー（画像）
	 * WP_Customize_Image_Controlに対して使う。
	 */
	public static function image( $image, $setting ) {
		$mimes = [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
			'svg'          => 'image/svg+xml',
		];
		$file  = wp_check_filetype( $image, $mimes );
		return ( $file['ext'] ? $image : $setting->default );
	}


	/**
	 * 動画用
	 */
	public static function video( $video_id, $setting ) {
		$video_url = wp_get_attachment_url( $video_id );
		$mimes     = [
			'mpg|mpeg' => 'video/mpeg',
			'mp4'      => 'video/mp4',
			'webm'     => 'video/webm',
			'mov|qt'   => 'video/quicktime',
			'avi'      => 'video/x-msvideo',
		];
		$file      = wp_check_filetype( $video_url, $mimes );
		return ( $file['ext'] ? $video_id : $setting->default );
	}

}
