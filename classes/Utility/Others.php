<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Others {


	/**
	 * wp_nonce_fieldを設置する
	 */
	public static function set_nonce_field( $key = '' ) {
		wp_nonce_field( self::$nonce['action'] . $key, self::$nonce['name'] . $key );
	}


	/**
	 * NONCE チェック
	 */
	public static function check_nonce( $key = '' ) {

		$nonce_name   = self::$nonce['name'] . $key;
		$nonce_action = self::$nonce['action'] . $key;

		if ( ! isset( $_POST[ $nonce_name ] ) ) {
			return false;
		}

		return wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action );
	}


	/**
	 * 編集権限のチェック
	 */
	public static function check_user_can_edit( $post_id ) {

		// 現在のユーザーに投稿の編集権限があるかのチェック （投稿 : 'edit_post' / 固定ページ & LP : 'edit_page')
		$check_can = ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) ? 'edit_post' : 'edit_page';
		if ( ! current_user_can( $check_can, $post_id ) ) {
			return false;
		}

		return true;
	}


	/**
	 * カラーコードをrgbaに変換
	 * $brightness : -1 ~ 1
	 */
	public static function get_rgba( $color_code, $alpha = 1, $brightness = 0 ) {

		$color_code = str_replace( '#', '', $color_code );

		$rgba_code          = [];
		$rgba_code['red']   = hexdec( substr( $color_code, 0, 2 ) );
		$rgba_code['green'] = hexdec( substr( $color_code, 2, 2 ) );
		$rgba_code['blue']  = hexdec( substr( $color_code, 4, 2 ) );

		if ( 0 !== $brightness ) {
			foreach ( $rgba_code as $key => $val ) {
				$val               = (int) $val;
				$result            = $val + ( $val * $brightness );
				$rgba_code[ $key ] = max( 0, min( 255, round( $result ) ) );
			}
		}

		$rgba_code['alpha'] = $alpha;

		return 'rgba(' . implode( ', ', $rgba_code ) . ' )';
	}


	/**
	 * HTMLソースのminify化
	 */
	public static function minify_html_code( $src ) {
		$search  = [
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',       // shorten multiple whitespace sequences
			'/<!--[\s\S]*?-->/s', // コメントを削除
		];
		$replace = [
			'>',
			'<',
			'\\1',
			'',
		];
		return preg_replace( $search, $replace, $src );
	}


	/**
	 * width,height から aspectratio を指定
	 */
	public static function set_aspectratio( $props, $src = '' ) {

		// width , height指定を取得
		preg_match( '/\swidth="([0-9]*)"/', $props, $width_matches );
		preg_match( '/\sheight="([0-9]*)"/', $props, $height_matches );
		$width  = ( $width_matches ) ? $width_matches[1] : '';
		$height = ( $height_matches ) ? $height_matches[1] : '';

		if ( $width && $height ) {
			// widthもheightもある時
			$props .= ' data-aspectratio="' . $width . '/' . $height . '"';
		} elseif ( $width ) {
			// widthしかない時
			$img_size = \SWELL_FUNC::get_file_size( $src );
			if ( $img_size ) {
				$props .= ' data-aspectratio="' . $img_size['width'] . '/' . $img_size['height'] . '"';
			}
		} else {
			// widthすら指定されていない時
			$img_size = \SWELL_FUNC::get_file_size( $src );
			if ( $img_size ) {
				$props .= ' width="' . $img_size['width'] . '" data-aspectratio="' . $img_size['width'] . '/' . $img_size['height'] . '"';
			}
		}
		return $props;
	}
}
