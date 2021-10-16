<?php

use \SWELL_THEME\Admin_Menu;
if ( ! defined( 'ABSPATH' ) ) exit;

// 新ふきだし用テーブルがあるかどうか
$table_name   = \SWELL_Theme::DB_TABLES['balloon'];
$table_exists = \SWELL_Theme::check_table_exists( $table_name );
// 旧ふきだし用データがあるかどうか
$has_old_data = \SWELL_Theme::has_old_balloon_data();

?>
<div id="swell_setting_page" class="swl-setting -balloon" data-old="<?=esc_attr( $has_old_data || ! $table_exists )?>"></div>
