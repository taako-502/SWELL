<?php

use \SWELL_THEME\Admin_Menu;
if ( ! defined( 'ABSPATH' ) ) exit;

// ふきだし用テーブルがなければ作成
$table_name   = 'swell_balloon';
$table_exists = \SWELL_Theme::check_table_exists( $table_name );

if ( ! $table_exists ) {
	global $wpdb;
	$collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table_name} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		title text NOT NULL,
		data text DEFAULT NULL,
		PRIMARY KEY (id)
	) {$collate};";

	// テーブル作成関数の読み込み・実行
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
?>

<div id="swell_setting_page" class="swl-setting -balloon"></div>
