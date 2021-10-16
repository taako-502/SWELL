<?php

use \SWELL_THEME\Admin_Menu;
if ( ! defined( 'ABSPATH' ) ) exit;

$table_exists = \SWELL_Theme::check_table_exists( 'balloon' );
$has_old_data = \SWELL_Theme::has_old_balloon_data();

// 古いデータもなく、新しいテーブルもない場合はアクセス時に作成
if ( ! $has_old_data && ! $table_exists ) {
	\SWELL_Theme::create_balloon_table();
}

?>
<div id="swell_setting_page" class="swl-setting -balloon" data-old="<?=esc_attr( $has_old_data )?>"></div>
