<?php
namespace SWELL_THEME;

use \SWELL_THEME\Menu as Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Menu {

	private static $instance;

	// ページスラッグ
	const PAGE_SLUG     = 'swell_settings';
	const SUB_PAGE_SLUG = [
		'editor'   => 'swell_settings_editor',
		'ads'      => 'swell_settings_ads',
		'widget'   => 'swell_settings_widget',
		'swellers' => 'swell_settings_swellers',
	];

	// グループ名
	const SETTING_GROUPS = [
		'options' => 'swell_setting_group_options',
		'editors' => 'swell_setting_group_editors',
	];

	// settings_field() と settings_section() で使う $page
	const PAGE_NAMES = [
		// basic
		'speed'     => 'swell_settings_speed',
		'structure' => 'swell_settings_structure',
		'jquery'    => 'swell_settings_jquery',
		'fa'        => 'swell_settings_fa',
		'remove'    => 'swell_settings_remove',
		'ad'        => 'swell_settings_ad',

		// editor
		'colors'    => 'swell_settings_block_colors',
		'border'    => 'swell_settings_block_border',
		'btn'       => 'swell_settings_block_btn',
		'marker'    => 'swell_settings_block_marker',
		'iconbox'   => 'swell_settings_block_iconbox',
		'balloon'   => 'swell_settings_block_balloon',
		'others'    => 'swell_settings_block_others',
		'custom'    => 'swell_settings_block_custom',

	];

	// 外部からインスタンス化させない
	private function __construct() {}


	/**
	 * init
	 */
	public static function init() {

		if ( isset( self::$instance ) ) return;
		self::$instance = new Admin_Menu();

		add_action( 'admin_menu', [ self::$instance, 'hook_admin_menu' ] );
		add_action( 'admin_init', [ self::$instance, 'hook_admin_init' ] );
	}


	/**
	 * 管理画面に独自メニューを追加
	 */
	public function hook_admin_menu() {

		// 「SWELL設定」を追加
		add_menu_page(
			__( 'SWELL Settings', 'swell' ), // ページタイトルタグ
			__( 'SWELL Settings', 'swell' ), // メニュータイトル
			'manage_options', // 必要な権限
			self::PAGE_SLUG, // このメニューを参照するスラッグ名
			[self::$instance, 'swell_setting' ], // 表示内容
			'', // アイコン
			29 // 管理画面での表示位置
		);

		// トップメニュー複製
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'SWELL Settings', 'swell' ),
			__( 'SWELL Settings', 'swell' ), // サブ側の名前
			'manage_options',
			self::PAGE_SLUG, // 親と同じに
			[self::$instance, 'swell_setting' ]
		);
		// サブメニュー
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Editor Settings', 'swell' ),
			__( 'Editor Settings', 'swell' ),
			'manage_options',
			self::SUB_PAGE_SLUG['editor'],
			[self::$instance, 'editor_setting' ]
		);

		$adstxt_title = sprintf( __( 'Edit %s', 'swell' ), 'ads.txt' );

		// サブメニュー
		add_submenu_page(
			self::PAGE_SLUG,
			$adstxt_title,
			$adstxt_title,
			'manage_options',
			self::SUB_PAGE_SLUG['ads'],
			[self::$instance, 'ads_txt_setting' ]
		);
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'SWELL Widget', 'swell' ),
			__( 'SWELL Widget', 'swell' ),
			'manage_options',
			self::SUB_PAGE_SLUG['widget'],
			[self::$instance, 'swell_widget' ]
		);
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'SWELLERS\'', 'swell' ),
			__( 'SWELLERS\'', 'swell' ),
			'manage_options',
			self::SUB_PAGE_SLUG['swellers'],
			[self::$instance, 'swellers_id_setting' ]
		);

		// 「再利用ブロック」を追加
		add_menu_page(
			__( 'Reusable Block', 'swell' ),
			__( 'Reusable Block', 'swell' ),
			'manage_options',
			'edit.php?post_type=wp_block',
			'',
			'dashicons-image-rotate',
			81 // 「設定」 の下
		);
	}

	/**
	 * 「SWELL設定」の内容
	 */
	public function swell_setting() {
		require_once T_DIRE . '/lib/menu/swell_menu.php';
	}


	/**
	 * サブメニューの表示内容
	 */
	public function editor_setting() {
		require_once T_DIRE . '/lib/menu/swell_menu_editor.php';
	}

	public function ads_txt_setting() {
		require_once T_DIRE . '/lib/menu/swell_menu_ads.php';
	}
	public function swellers_id_setting() {
		require_once T_DIRE . '/lib/menu/swell_menu_swellers.php';
	}
	public function swell_widget() {
		require_once T_DIRE . '/lib/menu/swell_menu_widget_editor.php';
	}

	/**
	 * 設定の追加
	 */
	public function hook_admin_init() {

		// 同じオプションに配列で値を保存するので、register_setting()は１つだけ
		register_setting( self::SETTING_GROUPS['options'], \SWELL_Theme::DB_NAME_OPTIONS );
		register_setting( self::SETTING_GROUPS['editors'], \SWELL_Theme::DB_NAME_EDITORS );

		$cb = [ '\SWELL_THEME\Menu\Output_Field', 'settings_field_cb' ];

		// SWELL設定
		Menu\Tab_Speed::cache_settings( self::PAGE_NAMES['speed'], $cb );
		Menu\Tab_Speed::lazyload_settings( self::PAGE_NAMES['speed'], $cb );
		Menu\Tab_Speed::file_load_settings( self::PAGE_NAMES['speed'], $cb );
		Menu\Tab_Speed::pjax_settings( self::PAGE_NAMES['speed'], $cb );
		Menu\Tab_Jquery::jquery_settings( self::PAGE_NAMES['jquery'], $cb );
		Menu\Tab_FontAwesome::fa_settings( self::PAGE_NAMES['fa'], $cb );
		Menu\Tab_Remove::remove_settings( self::PAGE_NAMES['remove'], $cb );
		Menu\Tab_Structure::jsonld_settings( self::PAGE_NAMES['structure'], $cb );
		Menu\Tab_Ad::ad_settings( self::PAGE_NAMES['ad'], $cb );

		// エディター設定
		Menu\Tab_Colors::palette_settings( self::PAGE_NAMES['colors'] );
		Menu\Tab_Colors::list_settings( self::PAGE_NAMES['colors'] );
		Menu\Tab_Colors::capblock_settings( self::PAGE_NAMES['colors'] );
		Menu\Tab_Colors::faq_settings( self::PAGE_NAMES['colors'] );

		Menu\Tab_Border::border_settings( self::PAGE_NAMES['border'] );
		Menu\Tab_Marker::marker_settings( self::PAGE_NAMES['marker'] );
		Menu\Tab_Btn::btn_settings( self::PAGE_NAMES['btn'] );
		Menu\Tab_Iconbox::small_settings( self::PAGE_NAMES['iconbox'] );
		Menu\Tab_Iconbox::big_settings( self::PAGE_NAMES['iconbox'] );
		Menu\Tab_Balloon::balloon_settings( self::PAGE_NAMES['balloon'] );
		Menu\Tab_Others::blogcard_settings( self::PAGE_NAMES['others'] );
		Menu\Tab_Others::blockquote_settings( self::PAGE_NAMES['others'] );
		Menu\Tab_Custom::custom_format_settings( self::PAGE_NAMES['custom'] );
		Menu\Tab_Custom::custom_format_css_editor( self::PAGE_NAMES['custom'] );
	}
}
