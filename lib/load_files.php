<?php
namespace SWELL_Theme\Load_Files;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once T_DIRE . '/lib/load/jquery.php';
require_once T_DIRE . '/lib/load/front.php';
require_once T_DIRE . '/lib/load/admin.php';
require_once T_DIRE . '/lib/load/block_assets.php';

add_action( 'init', function() {

	if ( 1 ) {
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );
	}

	// add_action( 'wp_head', 'gutenberg_maybe_inline_styles', 1 ); より前で処理
	add_action( 'wp_head', __NAMESPACE__ . '\pre_parse_blocks', 0 );
	function pre_parse_blocks() {
		if ( \SWELL_Theme::is_separate_css() ) {
			\SWELL_Theme\Pre_Parse_Blocks::init();
		}
	}
}, 9 );



add_action( 'wp_body_open', __NAMESPACE__ . '\wp_enqueue_scripts__ttt', 8 );
function wp_enqueue_scripts__ttt() {
	?>
<style>
.dump-blocks{
	padding:10px;
	background:#efefef;
	position: fixed;
	width: 240px;
	bottom: 10px;
	right: 10px;
	height: 400px;
	overflow: auto;
	z-index: 100;
}
</style>
	<?php
	// echo '<pre class="dump-blocks u-fz-s">';
	// var_dump( array_keys( \SWELL_Theme::$used_blocks ) );
	// echo '</pre>';
}
