<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 先に定義すべき定数
 */
define( 'T_DIRE', get_template_directory() );
define( 'S_DIRE', get_stylesheet_directory() );
define( 'T_DIRE_URI', get_template_directory_uri() );
define( 'S_DIRE_URI', get_stylesheet_directory_uri() );

// テキストドメイン
load_theme_textdomain( 'swell', T_DIRE . '/languages' );


/**
 * CLASSのオートロード
 */
require_once T_DIRE . '/lib/autoloader.php';


/**
 * メインクラス
 */
class SWELL_Theme extends \SWELL_Theme\Theme_Data {

	use \SWELL_Theme\Utility\Get;
	use \SWELL_Theme\Utility\Attrs;
	use \SWELL_Theme\Utility\Balloon;
	use \SWELL_Theme\Utility\Parts;
	use \SWELL_Theme\Utility\Status;
	use \SWELL_Theme\Utility\Others;

	public function __construct() {

		self::data_init();

		// テーマサポート機能
		require_once T_DIRE . '/lib/theme_support.php';

		// 定数定義
		require_once T_DIRE . '/lib/define_const.php';

		// ファイル読み込み
		require_once T_DIRE . '/lib/load_files.php';

		// カスタマイザー
		require_once T_DIRE . '/lib/customizer.php';

		// 投稿タイプ
		require_once T_DIRE . '/lib/post_type.php';

		// タクソノミー
		require_once T_DIRE . '/lib/taxonomy.php';

		// カスタムメニュー
		require_once T_DIRE . '/lib/custom_menu.php';

		// ウィジェット
		require_once T_DIRE . '/lib/widget.php';

		// TinyMCE
		require_once T_DIRE . '/lib/tiny_mce.php';

		// コードの出力関係
		require_once T_DIRE . '/lib/output.php';

		// Gutenberg
		require_once T_DIRE . '/lib/gutenberg.php';

		// カスタムフィールド
		require_once T_DIRE . '/lib/post_meta.php';

		// タームメタ
		require_once T_DIRE . '/lib/term_meta.php';

		// ユーザーメタ情報
		require_once T_DIRE . '/lib/user_meta.php';

		// ショートコード
		require_once T_DIRE . '/lib/shortcode.php';

		// 関数で呼び出すパーツ
		require_once T_DIRE . '/lib/pluggable_parts.php';

		// 設定上書き処理
		require_once T_DIRE . '/lib/overwrite.php';

		// コンテンツフィルター
		require_once T_DIRE . '/lib/content_filter.php';

		// REST API
		require_once T_DIRE . '/lib/rest_api.php';

		// その他、フック処理
		require_once T_DIRE . '/lib/hooks.php';

		// 管理者ログイン時
		if ( current_user_can( 'manage_options' ) ) {
			// アップデートチェック
			require_once T_DIRE . '/lib/update.php';

			// アップデート時の処理
			require_once T_DIRE . '/lib/updated_action.php';
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
