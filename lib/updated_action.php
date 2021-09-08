<?php
namespace SWELL_Theme\Updated_Action;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * バージョン更新時の処理
 */
add_action( 'init', '\SWELL_Theme\Updated_Action\updated_hook', 1 );
function updated_hook() {
	// \SWELL_Theme\Updated_Action::db_update();

	$now_ver  = \SWELL_Theme::$swell_version;
	$old_ver  = get_option( 'swell_version' ); // データベースに保存されているバージョン
	$free_ver = get_option( 'swell_free_version' );

	if ( $free_ver ) {
		// 無料版からの移行であれば
		delete_option( 'swell_free_version' );
		$old_ver = $free_ver;
	}

	// まだバージョン情報が記憶されていなければ
	if ( false === $old_ver ) {

		update_option( 'swell_version', $now_ver ); // 現在のバージョンをDBに記憶
		all_cache_delete();

	} elseif ( $old_ver !== $now_ver ) {

		// バージョンが更新されていれば、アップデート時に一度だけ処理

		update_option( 'swell_version', $now_ver ); // 現在のバージョンをDBに記憶

		// バージョンアップ時の処理
		all_cache_delete();
		\SWELL_Theme\Updated_Action::db_update();
	}
}


/**
 * SWELL用 transientキャッシュをすべて削除
 */
function all_cache_delete() {
	\SWELL_Theme::clear_cache();
	\SWELL_Theme::clear_all_parts_cache_by_wpdb(); // 念のため、query回して削除
}
