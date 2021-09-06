<?php
namespace SWELL_Theme;

use \SWELL_Theme as SWELL;
use \SWELL_Theme\Style as Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class Style {

	/**
	 * CSS変数をまとめておく
	 */
	public static $root_styles = [
		'all'    => '',
		'pc'     => '',
		'sp'     => '',
		'tab'    => '',
		'mobile' => '',
	];

	/**
	 * 最終的に吐き出すCSS
	 */
	public static $styles = [
		'all'    => '',
		'pc'     => '',
		'sp'     => '',
		'tab'    => '',
		'mobile' => '',
	];

	/**
	 * 別ファイルとして分離しているCSS
	 */
	public static $modules = [];

	/**
	 * 外部からのインタンス呼び出し無効
	 */
	private function __construct() {}


	/**
	 * :rootスタイル生成
	 */
	public static function add_root( $name, $val, $media_query = 'all' ) {
		self::$root_styles[ $media_query ] .= $name . ':' . $val . ';';
	}


	/**
	 * スタイル生成
	 *
	 * @param string|array $selectors
	 * @param string|array $properties
	 * @param string       $media_query
	 * @param string       $branch
	 * @return void
	 */
	public static function add( $selectors, $properties, $media_query = 'all', $branch = '' ) {

		if ( empty( $properties ) ) return;

		if ( is_array( $selectors ) ) {
			$selectors = implode( ',', $selectors );
		}

		if ( is_array( $properties ) ) {
			$properties = implode( ';', $properties );
		}

		if ( 'editor' === $branch ) {
			if ( ! is_admin() ) return;
		} elseif ( 'front' === $branch ) {
			if ( is_admin() ) return;
		}

		self::$styles[ $media_query ] .= $selectors . '{' . $properties . '}';
	}


	/**
	 * スタイル生成（フロントとエディターで出し分ける）
	 *
	 * @param string|array $selectors
	 * @param string|array $properties
	 * @param string       $media_query
	 * @return void
	 */
	public static function add_post_style( $selectors, $properties, $media_query = 'all', $branch = 'both' ) {

		if ( empty( $properties ) ) return;
		if ( 'editor' === $branch && ! is_admin() ) return;
		if ( 'front' === $branch && is_admin() ) return;

		$new_selector = '';
		foreach ( $selectors as $s ) {
			if ( is_admin() ) {
				$new_selector .= '.mce-content-body ' . $s . ', .editor-styles-wrapper ' . $s;
			} else {
				$new_selector .= '.post_content ' . $s;
			}
			if ( end( $selectors ) !== $s ) {
				$new_selector .= ',';
			}
		}

		self::add( $new_selector, $properties, $media_query );
	}


	/**
	 * パーツ化したCSSファイルの読み込み
	 */
	public static function add_module( $filename = '' ) {

		self::$modules[] = $filename;

	}


	/**
	 * カスタムスタイルの生成（フロント用）
	 */
	public static function front_style() {
		$SETTING     = SWELL::get_setting();
		$frame_class = SWELL::get_frame_class();

		// ページ表示時のアニメーション
		if ( ! $SETTING['remove_page_fade'] ) {
			self::add_module( '-loaded-animation' );
		}

		// カラーのセット
		Style\Color::front( $SETTING );

		// Body
		Style\Body::font( $SETTING['body_font_family'] );
		Style\Body::content_size( $SETTING['container_size'], $SETTING['article_size'] );
		Style\Body::content_frame( $SETTING['color_bg'], $frame_class );
		Style\Body::bg();
		if ( $SETTING['to_site_rounded'] ) {
			Style\Body::radius( $SETTING['sidettl_type'], $SETTING['h2_type'], $frame_class );
		}

		// ヘッダー周りのスタイル
		Style\Header::header_border( $SETTING['header_border'] );
		Style\Header::head_bar( $SETTING['color_head_bar_bg'], $SETTING['color_head_bar_text'], $SETTING['show_head_border'] );
		Style\Header::header_sp_layout( $SETTING['header_layout_sp'] );
		Style\Header::header_menu_btn( $SETTING['menu_btn_bg'], $SETTING['custom_btn_bg'] );
		Style\Header::logo( $SETTING['logo_size_sp'], $SETTING['logo_size_pc'], $SETTING['logo_size_pcfix'] );

		// ヘッダーレイアウト
		if ( 'series_right' === $SETTING['header_layout'] || 'series_left' === $SETTING['header_layout'] ) {
			self::add_module( '-header-series' );
		} else {
			self::add_module( '-header-parallel' );
		}

		// gnav周り
		Style\Header::gnav( $SETTING['color_head_hov'], $SETTING['headmenu_effect'], $SETTING['head_submenu_bg'] );

		// グロナビ背景の上書き
		if ( 'overwrite' === $SETTING['gnav_bg_type'] ) {
			$color_gnav_bg = $SETTING['color_gnav_bg'] ?: $SETTING['color_main'];
			self::add_root( '--color_gnav_bg', $color_gnav_bg );
			self::add_module( '-gnav-overwrite' );
		}

		// グロナビとスマホメニューのサブメニューの展開方式
		if ( SWELL::is_use( 'acc_submenu' ) ) {
			self::add_module( '-submenu-acc' );
		} else {
			self::add_module( '-submenu-normal' );
		}

		// お知らせバー
		if ( 'none' !== $SETTING['info_bar_pos'] ) {
			self::add_module( '-info-bar' );
			Style\Header::info_bar( $SETTING );
		}

		// トップページの１ページ目だけに使用するスタイル
		if ( SWELL::is_top() && ! is_paged() ) Style\Top::init();

		// タイトルデザイン
		Style\Title::section( $SETTING['sec_title_style'] );

		// 投稿ページ
		Style\Page::title_date( $SETTING['show_title_date'], $SETTING['show_title_date_sp'] );
		Style\Page::title_bg( $SETTING['ttlbg_overlay_color'], $SETTING['ttlbg_overlay_opacity'] );
		Style\Page::share_btn( $SETTING['share_btn_style'] );
		Style\Page::toc( $SETTING['toc_before_color'], $SETTING['toc_before_custom_color'] );
		Style\Page::toc_ad( $SETTING['show_toc_ad_alone_post'], $SETTING['show_toc_ad_alone_page'] );

		// コンテンツのフォントサイズ
		Style\Page::font_size( $SETTING['post_font_size_sp'], $SETTING['post_font_size_pc'] );

		// Footer周り
		Style\Footer::pagetop_btn( $SETTING['pagetop_style'] );
		Style\Footer::index_btn( $SETTING['index_btn_style'] );
		if ( has_nav_menu( 'fix_bottom_menu' ) ) {
			Style\Footer::fix_menu_btns( $SETTING['show_fbm_pagetop'], $SETTING['show_fbm_index'] );
		}
		// フッター前マージン
		if ( $SETTING['footer_no_mt'] ) self::add( '.w-beforeFooter', 'margin-bottom:0' );

		// ウィジェット
		Style\Widget::title( $SETTING['sidettl_type'], $SETTING['sidettl_type_sp'], $frame_class );
		Style\Widget::spmenu_title( $SETTING['spmenu_title_type'] );
		Style\Widget::footer_title( $SETTING['footer_title_type'] );

		// その他
		Style\Others::spmenu();
		Style\Others::sidebar( $SETTING['sidebar_pos'] );
		Style\Others::pager( $SETTING['pager_shape'], $SETTING['pager_style'] );
		Style\Others::link( $SETTING['show_link_underline'] );

	}

	/**
	 * フロント&エディター共通
	 */
	public static function editor_style() {
		Style\Editor::content_bg();
	}


	/**
	 * フロント&エディター共通
	 */
	public static function common_style() {

		$EDITOR     = SWELL::get_editor();
		$SETTING    = SWELL::get_setting();
		$color_main = $SETTING['color_main'];

		// カラー用CSS変数のセット
		Style\Color::common( $SETTING, $EDITOR );

		// ボタン
		Style\Post::btn();

		// 引用
		Style\Post::blockquote( $EDITOR['blockquote_type'] );

		// パレット
		self::add_root( '--color_deep01', $EDITOR['color_deep01'] );
		self::add_root( '--color_deep02', $EDITOR['color_deep02'] );
		self::add_root( '--color_deep03', $EDITOR['color_deep03'] );
		self::add_root( '--color_deep04', $EDITOR['color_deep04'] );
		self::add_root( '--color_pale01', $EDITOR['color_pale01'] );
		self::add_root( '--color_pale02', $EDITOR['color_pale02'] );
		self::add_root( '--color_pale03', $EDITOR['color_pale03'] );
		self::add_root( '--color_pale04', $EDITOR['color_pale04'] );

		// マーカー
		self::add_root( '--color_mark_blue', $EDITOR['color_mark_blue'] );
		self::add_root( '--color_mark_green', $EDITOR['color_mark_green'] );
		self::add_root( '--color_mark_yellow', $EDITOR['color_mark_yellow'] );
		self::add_root( '--color_mark_orange', $EDITOR['color_mark_orange'] );
		Style\Post::marker( $EDITOR['marker_type'], $SETTING['body_font_family'] );

		// ボーダーセット
		self::add_root( '--border01', $EDITOR['border01'] );
		self::add_root( '--border02', $EDITOR['border02'] );
		self::add_root( '--border03', $EDITOR['border03'] );
		self::add_root( '--border04', $EDITOR['border04'] );

		// アイコンボックス
		Style\Post::iconbox( $EDITOR['iconbox_s_type'], $EDITOR['iconbox_type'] );

		// アイコンボックス
		Style\Post::balloon();

		// 投稿リストのサムネイル比率
		Style\Post_List::thumb_ratio(
			$SETTING['card_posts_thumb_ratio'],
			$SETTING['list_posts_thumb_ratio'],
			$SETTING['big_posts_thumb_ratio'],
			$SETTING['thumb_posts_thumb_ratio']
		);

		// 投稿リストのREAD MORE
		Style\Post_List::read_more( $SETTING['post_list_read_more'] );

		// 投稿リストのカテゴリー部分
		$cat_bg_color = $SETTING['pl_cat_bg_color'] ?: $color_main;
		Style\Post_List::category( $SETTING['pl_cat_bg_style'], $cat_bg_color, $SETTING['pl_cat_txt_color'] );

		// サイト全体の画像を丸くするかどうか
		if ( $SETTING['to_site_rounded'] ) {
			self::add_module( '-site-radius' );
		}

		// 見出し
		$color_htag = $SETTING['color_htag'] ?: $color_main;
		Style\Post::h2( $SETTING['h2_type'], $color_htag );
		Style\Post::h3( $SETTING['h3_type'], $color_htag );
		Style\Post::h4( $SETTING['h4_type'], $color_htag );
		Style\Post::h2_section( $SETTING['sec_h2_type'], $SETTING['color_sec_htag'] );

		// 太字
		if ( $SETTING['show_border_strong'] ) {
			self::add_post_style( ['p > strong' ], 'padding: 0 4px 3px;border-bottom: 1px dashed #bbb' );
		}

		// エディターに追加
		Style\Post::editor( $SETTING['to_site_flat'] );

	}


	/**
	 * 生成したCSSの出力
	 */
	public static function output( $type = 'front' ) {

		// スタイルを生成
		if ( 'front' === $type ) {
			self::common_style();
			self::front_style();
		} elseif ( 'editor' === $type ) {
			self::common_style();
			self::editor_style();
		}

		$output_style  = '';
		$output_style .= ':root{' . self::$root_styles['all'] . '}';

		$styles = self::$styles;

		$output_style .= $styles['all'];
		$output_style .= '@media screen and (min-width: 960px){:root{' . self::$root_styles['pc'] . '}' . $styles['pc'] . '}';
		$output_style .= '@media screen and (max-width: 959px){:root{' . self::$root_styles['sp'] . '}' . $styles['sp'] . '}';
		$output_style .= '@media screen and (min-width: 600px){:root{' . self::$root_styles['tab'] . '}' . $styles['tab'] . '}';
		$output_style .= '@media screen and (max-width: 599px){:root{' . self::$root_styles['mobile'] . '}' . $styles['mobile'] . '}';

		// モジュールの連結
		self::$modules;
		foreach ( self::$modules as $filename ) {

			$include_path  = T_DIRE . '/assets/css/module/' . $filename . '.css';
			$output_style .= SWELL::get_file_contents( $include_path );

			// $include_path = T_DIRE_URI . '/assets/css/module/' . $filename . '.css';
			// wp_enqueue_style( 'swell-module' . $filename, $include_path, ['main_style' ], SWELL_VERSION );
		}

		return $output_style;
	}

}
