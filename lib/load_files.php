<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once T_DIRE . '/lib/load/jquery.php';
require_once T_DIRE . '/lib/load/front.php';
require_once T_DIRE . '/lib/load/admin.php';
require_once T_DIRE . '/lib/load/block_assets.php';

add_action( 'wp_head', __NAMESPACE__ . '\pre_parse_blocks', 0 );
function pre_parse_blocks() {
	\SWELL_Theme\Pre_Parse_Blocks::init();
}

add_action( 'wp_body_open', __NAMESPACE__ . '\wp_enqueue_scripts__ttt', 8 );
function wp_enqueue_scripts__ttt() {
	echo '<pre style="margin-left: 100px;">';
	var_dump( \SWELL_Theme::$used_blocks );
	echo '</pre>';
}
