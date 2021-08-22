<?php
namespace SWELL_Theme\Style;

use \SWELL_Theme as SWELL;
use SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * フロント・エディター共通で出力したいCSS
 */
class Editor {

	/**
	 * 記事コンテンツの背景色。
	 */
	public static function content_bg() {

		global $post_id; // 新規追加時は null
		global $post_type;

		// コンテンツの背景を白にするがオンのページを判定
		$is_bg_white = false;
		if ( 'frame_off' !== SWELL::get_setting( 'content_frame' ) ) {

			$frame_scope = SWELL::get_setting( 'frame_scope' );

			if ( '' === $frame_scope ) {
				// 全ページが対象のとき
				$is_bg_white = true;
			} else {
				$front_id = (int) get_option( 'page_on_front' );
				$is_page  = 'page' === $post_type && $post_id !== $front_id;
				$is_post  = 'post' === $post_type;

				if ( 'page' === $frame_scope && $is_page ) {
					$is_bg_white = true;
				} elseif ( 'post' === $frame_scope && $is_post ) {
					$is_bg_white = true;
				} elseif ( 'post_page' === $frame_scope && ( $is_post || $is_page ) ) {
					$is_bg_white = true;
				}
			}
		}

		if ( $is_bg_white ) {
			Style::add_root( '--color_content_bg', '#fff' );
		} else {
			Style::add_root( '--color_content_bg', SWELL::get_setting( 'color_bg' ) );
		}
	}


}
