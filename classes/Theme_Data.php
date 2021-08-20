<?php
namespace SWELL_Theme;

/**
 * テーマで使用するデータ
 */
class Theme_Data {

	use \SWELL_Theme\Data\Default_Settings;

	// DB名
	const DB_NAME_CUSTOMIZER = 'loos_customizer';
	const DB_NAME_OPTIONS    = 'swell_options';
	const DB_NAME_EDITORS    = 'swell_editors';

	const MENU_SLUGS = [
		'basic'  => 'swell_settings',
		'editor' => 'swell_settings_editor',
	];

	// カスタマイザー用
	public static $setting            = '';
	protected static $default_setting = '';

	// 設定画面用
	public static $options            = '';
	protected static $default_options = '';

	// エディター設定
	public static $editors            = '';
	protected static $default_editors = '';

	// version
	public static $swell_version = '';

	// ユーザーエージェント
	public static $user_agent = '';

	// サイトデータ
	public static $site_data = [];

	// NO IMG
	public static $noimg = [];

	// NONCE
	public static $nonce = [
		'name'   => 'swl_nonce',
		'action' => 'swl_nonce_action',
	];

	// リストレイアウト
	public static $list_layouts = [];

	// ぱんくずのJSONデータ保持用
	public static $bread_json_data = [];

	// PVカウント機能を利用する投稿タイプ
	public static $post_types_for_pvct = [ 'post' ];

	// 投稿リストのレイアウトタイプ
	public static $list_type = 'card';

	// EXCERPT_LENGTH
	public static $excerpt_length = 120;

	// プレースホルダー
	public static $placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	// 6:2 data:image/gif;base64,R0lGODlhBgACAPAAAP///wAAACH5BAEAAAAALAAAAAAGAAIAAAIDhI9WADs=

	// lazyloadの種類
	public static $lazy_type = 'none';

	// 目次の生成フックがすでに処理されたかどうか
	public static $added_toc = false;

	// JSの読み込みを制御する変数
	public static $use = [];

	// キャッシュキー
	public static $cache_keys = [
		'style' => [ // スタイルに関わるキャッシュキー
			'style_top',
			'style_single',
			'style_page',
			'style_other',
		],
		'header' => [ // ヘッダーに関わるキャッシュキー
			'header_top',
			'header_notop',
		],
		'post' => [ // 投稿に関わるキャッシュキー
			'home_posts',
			'home_posts_sp',
			'post_slider',
			'pickup_banner',
		],
		'widget' => [ // ウィジェット系キャッシュキー（「ヘッダー内部」を除く）
			'spmenu',
			'sidebar_top',
			'sidebar_top_sp',
			'sidebar_single',
			'sidebar_single_sp',
			'sidebar_page',
			'sidebar_page_sp',
			'sidebar_archive',
			'sidebar_archive_sp',
		],
		'others' => [
			'mv',
			'mv_info',
			'fix_bottom_menu',
			'sidebars_widgets',
		],
	];

	// サムネイル比率
	public static $thumb_ratios = [
		'silver' => [
			'value' => '70.72%',
			'label' => '白銀比率(1:1.414)',
		],
		'golden' => [
			'value' => '61.8%',
			'label' => '黄金比率(1:1.618)',
		],
		'slr'    => [
			'value' => '66.66%',
			'label' => '一眼(3:2)',
		],
		'wide'   => [
			'value' => '56.25%',
			'label' => 'ワイド(16:9)',
		],
		'wide2'  => [
			'value' => '50%',
			'label' => '横長(2:1)',
		],
		'wide3'  => [
			'value' => '40%',
			'label' => '超横長(5:2)',
		],
		'square' => [
			'value' => '100%',
			'label' => '正方形(1:1)',
		],
	];


	/**
	 * 画像HTMLを許可する時にwp_ksesに渡す配列
	 */
	public static $allowed_img_html = [
		'img' => [
			'alt'     => true,
			'src'     => true,
			'secset'  => true,
			'class'   => true,
			'seizes'  => true,
			'width'   => true,
			'height'  => true,
			'loading' => true,
		],
	];


	/**
	 * テキスト系HTMLを許可する時にwp_ksesに渡す配列
	 */
	public static $allowed_text_html = [
		'a'      => [
			'href'   => true,
			'rel'    => true,
			'target' => true,
			'class'  => true,
		],
		'b'      => [ 'class' => true ],
		'br'     => [ 'class' => true ],
		'i'      => [ 'class' => true ],
		'em'     => [ 'class' => true ],
		'span'   => [ 'class' => true ],
		'strong' => [ 'class' => true ],
		'ul'     => [ 'class' => true ],
		'ol'     => [ 'class' => true ],
		'li'     => [ 'class' => true ],
		'p'      => [ 'class' => true ],
		'div'    => [ 'class' => true ],
		'img'    => [
			'alt'     => true,
			'src'     => true,
			'secset'  => true,
			'class'   => true,
			'seizes'  => true,
			'width'   => true,
			'height'  => true,
			'loading' => true,
		],
	];


	/**
	 * data_init
	 */
	public static function data_init() {
		self::set_variables();
		self::set_default();

		add_action( 'init', '\SWELL_Theme::set_options', 9 ); // set_settings よりも前で。
		add_action( 'wp_loaded', '\SWELL_Theme::set_editors', 10 ); // set_settings よりも前で。
		add_action( 'wp_loaded', '\SWELL_Theme::set_settings', 10 ); // ※早すぎるとカスタマイザーの値が受け取れない
	}


	/**
	 * 動的な変数をセット
	 */
	private static function set_variables() {

		// SWELLバージョンをセット
		self::$swell_version = wp_get_theme( 'swell' )->Version;

		// ユーザーエージョントを小文字化して取得 @codingStandardsIgnoreStart
		$user_agent       = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
		self::$user_agent = mb_strtolower( $user_agent ); // @codingStandardsIgnoreEnd

		// リストレイアウト
		self::$list_layouts = [
			'card'   => __( 'Card type', 'swell' ),
			'list'   => __( 'List type', 'swell' ),
			'list2'  => __( 'List type', 'swell' ) . __( '(Alternate left and right)', 'swell' ),
			'thumb'  => __( 'Thumbnail type', 'swell' ),
			'big'    => __( 'Blog type', 'swell' ),
			'simple' => __( 'Text type', 'swell' ),
		];

		self::$site_data = [
			'home'  => home_url( '/' ),
			'title' => get_option( 'blogname' ),
		];
	}


	/**
	 * デフォルト値をセット
	 */
	private static function set_default() {

		// 設定データ
		self::$default_options = self::set_default_options();

		// エディター設定のデータ
		self::$default_editors = self::set_default_editor_options();

		// カスタマイザー デフォルト値のセット
		self::$default_setting = self::set_default_customizer();
	}


	/**
	 * $default_setting 取得
	 */
	public static function get_default_setting( $key = null ) {

		if ( null !== $key ) {
			return self::$default_setting[ $key ] ?? '';
		}
		return self::$default_setting;
	}


	/**
	 * $default_options 取得
	 */
	public static function get_default_option( $key = null ) {

		if ( null !== $key ) {
			return self::$default_options[ $key ];
		}
		return self::$default_options;
	}


	/**
	 * $default_editors 取得
	 */
	public static function get_default_editor( $key = null ) {

		if ( null !== $key ) {
			return self::$default_editors[ $key ];
		}
		return self::$default_editors;
	}


	/**
	 * エディター設定をセット
	 */
	public static function set_editors() {
		$editors       = get_option( self::DB_NAME_EDITORS ) ?: [];
		self::$editors = array_merge( self::$default_editors, $editors );
	}


	/**
	 * 管理画面での設定をセット
	 */
	public static function set_options() {
		$options       = get_option( self::DB_NAME_OPTIONS ) ?: [];
		self::$options = array_merge( self::$default_options, $options );
	}


	/**
	 * カスタマイザー の設定項目をセット( 互換性を保つために swell_options も $SETTINGに全部まとめる )
	 */
	public static function set_settings() {
		$setting       = get_option( self::DB_NAME_CUSTOMIZER ) ?: [];
		self::$setting = array_merge( self::$default_setting, $setting, self::$options );
	}


	/**
	 * settingsデータを個別でセット
	 */
	// public static function set_setting( $key = null, $val = '' ) {
	// 	if ( null === $key ) return;
	// 	self::$setting[ $key ] = $val;
	// }


	/**
	 * get_setting
	 */
	public static function get_setting( $key = null ) {

		if ( null !== $key ) {
			if ( ! isset( self::$setting[ $key ] ) ) return '';
			return self::$setting[ $key ];
		}
		return self::$setting;
	}


	/**
	 * get_option
	 */
	public static function get_option( $key = null ) {

		if ( null !== $key ) {
			if ( ! isset( \SWELL_Theme::$options[ $key ] ) ) return '';
			return \SWELL_Theme::$options[ $key ];
		}
		return \SWELL_Theme::$options;
	}


	/**
	 * get_editor
	 */
	public static function get_editor( $key = null ) {

		if ( null !== $key ) {
			if ( ! isset( \SWELL_Theme::$editors[ $key ] ) ) return '';
			return \SWELL_Theme::$editors[ $key ];
		}
		return \SWELL_Theme::$editors;
	}

}
