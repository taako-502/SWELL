<?php
namespace SWELL_Theme;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

class Pre_Parse_Blocks {

	public static $sidebar_blocks = [];
	public static $dump           = [];

	/**
	 * check_widget_blocks
	 */
	public static function init() {

		if ( ! ( is_singular( 'post' ) || is_page() ) ) return;
		$post = get_post( get_queried_object_id() );
		if ( ! $post) return;

		// コンテンツをパースしてブロックをチェック
		$parsed_content = parse_blocks( $post->post_content );
		foreach ( $parsed_content as $block ) {
			self::check_block( $block, \SWELL_Theme::$used_blocks );
		}

		// コンテンツの文字列を直接チェック
		self::check_content_str( $post->post_content );

		// add_filter( 'pre_do_shortcode_tag', [ __CLASS__, 'pre_check_do_shortcode' ], 10, 2 );
		add_filter( 'widget_text', [ __CLASS__, 'check_widget_content_str' ], 1 );
		add_filter( 'widget_text_content', [ __CLASS__, 'check_widget_content_str' ], 1 );

		// サイドバーのチェック
		if ( \SWELL_Theme::is_show_sidebar() ) {
			self::parse_sidebar();
		}

		// ページ種別ごとのウィジェットチェック
		if ( \SWELL_Theme::is_top() ) {
			self::parse_front_widget();
		} elseif ( is_page() || is_home() ) {
			self::parse_page_widget();
		} elseif ( is_single() ) {
			self::parse_single_widget();
		}

		// その他のウィジェットチェック
		self::parse_other_area();

		// remove_filter( 'pre_do_shortcode_tag', [ __CLASS__, 'pre_check_do_shortcode' ], 10, 2 );
		remove_filter( 'widget_text', [ __CLASS__, 'check_widget_content_str' ], 1 );
		remove_filter( 'widget_text_content', [ __CLASS__, 'check_widget_content_str' ], 1 );

	}


	/**
	 * サイドバーのチェック
	 */
	public static function parse_sidebar() {
		add_filter( 'render_block', [ __CLASS__, 'check_sidebar_block' ], 10, 2 );
		ob_start();
		if ( \SWELL_Theme::is_show_sidebar() ) {
			\SWELL_Theme::get_parts( 'parts/sidebar_content' );
		}
		ob_clean();
		// $sidebar = ob_get_clean();
		remove_filter( 'render_block', [ __CLASS__, 'check_sidebar_block' ], 10, 2 );

		// echo '<pre style="background:#efefef;margin:20px;padding:20px;">';
		// var_dump( self::$sidebar_blocks );
		// echo '</pre>';

		// マージ
		\SWELL_Theme::$used_blocks = array_merge( \SWELL_Theme::$used_blocks, self::$sidebar_blocks );
	}


	/**
	 *  フロントページ専用ウィジェットのブロックチェック
	 */
	public static function parse_front_widget() {
		add_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
		ob_start();
		\SWELL_Theme::outuput_widgets( 'front_top' );
		\SWELL_Theme::outuput_widgets( 'front_bottom' );
		ob_clean();
		remove_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
	}


	/**
	 * 投稿ページ専用ウィジェットのブロックチェック
	 */
	public static function parse_single_widget() {
		add_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
		ob_start();
		\SWELL_Theme::outuput_cta();
		\SWELL_Theme::outuput_content_widget( 'single', 'top' );
		\SWELL_Theme::outuput_content_widget( 'single', 'bottom' );
		\SWELL_Theme::outuput_widgets( 'before_related' );
		\SWELL_Theme::outuput_widgets( 'after_related' );
		ob_clean();
		remove_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
	}


	/**
	 * 固定ページ専用ウィジェットのブロックチェック
	 */
	public static function parse_page_widget() {
		add_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
		ob_start();
		\SWELL_Theme::outuput_content_widget( 'page', 'top' );
		\SWELL_Theme::outuput_content_widget( 'page', 'bottom' );
		ob_clean();
		remove_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
	}

	/**
	 * その他のウィジェットのチェック
	 */
	public static function parse_other_area() {
		add_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
		ob_start();
		\SWELL_Theme::outuput_widgets( 'footer_sp' );
		\SWELL_Theme::outuput_widgets( 'footer_box1' );
		\SWELL_Theme::outuput_widgets( 'footer_box2' );
		\SWELL_Theme::outuput_widgets( 'footer_box3' );
		\SWELL_Theme::outuput_widgets( 'before_footer' );
		\SWELL_Theme::outuput_widgets( 'sp_menu_bottom' );
		\SWELL_Theme::outuput_widgets( 'head_box' );
		ob_clean();
		remove_filter( 'render_block', [ __CLASS__, 'check_other_area_block' ], 10, 2 );
	}


	/**
	 * 使用されたブロックをリストに追加
	 */
	public static function check_block( $block, &$list ) {
		$block_name = $block['blockName'] ?? '';
		if ( ! $block_name ) return;

		// まだリストに追加されてない時だけ追加
		if ( ! isset( $list[ $block_name ] ) ) {
			$list[ $block_name ] = true;
		}

		// インナーブロックにも同じ処理を。
		if ( ! empty( $block['innerBlocks'] ) ) {
			self::check_block( $block['innerBlocks'], $list );
		}
	}


	/**
	 * サイドバーチェック
	 */
	public static function check_sidebar_block( $block_content, $block ) {
		self::check_block( $block, self::$sidebar_blocks );
		return $block_content;
	}


	/**
	 * その他チェック
	 */
	public static function check_other_area_block( $block_content, $block ) {
		self::check_block( $block, \SWELL_Theme::$used_blocks );
		return $block_content;
	}


	/**
	 * ショートコードのチェック
	 */
	// public static function pre_check_do_shortcode( $return, $tag ) {
	// 	if ( 'ふきだし' === $tag || 'speech_balloon' === $tag ) {
	// 		\SWELL_Theme::$used_blocks['loos/balloon'] = true;
	// 	}
	// 	return $return;
	// }


	/**
	 * 文字列チェック
	 */
	public static function check_content_str( $content ) {
		if ( false !== strpos( $content, '[ふきだし' ) || false !== strpos( $content, '[speech_balloon' ) ) {
			\SWELL_Theme::$used_blocks['loos/balloon'] = true;
		}
		if ( false !== strpos( $content, 'cap_box' ) ) {
			\SWELL_Theme::$used_blocks['loos/cap-block'] = true;
		}
		if ( false !== strpos( $content, '[full_wide_content' ) ) {
			\SWELL_Theme::$used_blocks['loos/full-wide'] = true;
		}
	}


	/**
	 * 文字列チェック
	 */
	public static function check_widget_content_str( $content ) {
		self::check_content_str( $content );
		return $content;

	}

}
