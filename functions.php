<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 動作環境チェック
 */
require_once __DIR__ . '/lib/check_environment.php';
if ( isset( $GLOBALS['swell_env_err_text'] ) ) {
	swl__throw_env_err();
	return;
}


/**
 * 各種パスを定数化
 */
define( 'T_DIRE', get_template_directory() );
define( 'S_DIRE', get_stylesheet_directory() );
define( 'T_DIRE_URI', get_template_directory_uri() );
define( 'S_DIRE_URI', get_stylesheet_directory_uri() );



/**
 * テキストドメイン読み込み
 */
load_theme_textdomain( 'swell', __DIR__ . '/languages' );

/**
 * CLASSのオートロード
 */
require_once __DIR__ . '/lib/autoloader.php';

/**
 * メインクラス
 */
class SWELL_Theme extends \SWELL_Theme\Theme_Data {

	use \SWELL_Theme\Utility\Get;
	use \SWELL_Theme\Utility\Attrs;
	use \SWELL_Theme\Utility\Parts;
	use \SWELL_Theme\Utility\Status;
	use \SWELL_Theme\Utility\Others;

	public function __construct() {

		self::data_init();

		// テーマサポート機能
		require_once __DIR__ . '/lib/theme_support.php';

		// 定数定義
		require_once __DIR__ . '/lib/define_const.php';

		// ファイル読み込み
		require_once __DIR__ . '/lib/load_files.php';

		// カスタマイザー
		require_once __DIR__ . '/lib/customizer.php';

		// 投稿タイプ
		require_once __DIR__ . '/lib/post_type.php';

		// タクソノミー
		require_once __DIR__ . '/lib/taxonomy.php';

		// カスタムメニュー
		require_once __DIR__ . '/lib/custom_menu.php';

		// ウィジェット
		require_once __DIR__ . '/lib/widget.php';

		// TinyMCE
		require_once __DIR__ . '/lib/tiny_mce.php';

		// コードの出力関係
		require_once __DIR__ . '/lib/output.php';

		// Gutenberg
		require_once __DIR__ . '/lib/gutenberg.php';

		// カスタムフィールド
		require_once __DIR__ . '/lib/post_meta.php';

		// タームメタ
		require_once __DIR__ . '/lib/term_meta.php';

		// ユーザーメタ情報
		require_once __DIR__ . '/lib/user_meta.php';

		// ショートコード
		require_once __DIR__ . '/lib/shortcode.php';

		// 関数で呼び出すパーツ
		require_once __DIR__ . '/lib/pluggable_parts.php';

		// 設定上書き処理
		require_once __DIR__ . '/lib/overwrite.php';

		// コンテンツフィルター
		require_once __DIR__ . '/lib/content_filter.php';

		// REST API
		require_once __DIR__ . '/lib/rest_api.php';

		// その他、フック処理
		require_once __DIR__ . '/lib/hooks.php';

		// その他、フック処理
		require_once __DIR__ . '/lib/rewrite_html.php';

		// 管理者ログイン時
		if ( current_user_can( 'manage_options' ) ) {
			// アップデートチェック
			require_once __DIR__ . '/lib/update.php';

			// アップデート時の処理
			require_once __DIR__ . '/lib/updated_action.php';
		}

		// 管理画面でのみ
		if ( is_admin() ) {
			// メニュー生成
			\SWELL_THEME\Admin_Menu::init();
		}
	}
}

/**
 * SWELL start!
 */
new SWELL_Theme();
