<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( \SWELL_Theme::is_top() && is_active_sidebar( 'sidebar_top' ) ) {
	dynamic_sidebar( 'sidebar_top' );
}
if ( IS_MOBILE && is_active_sidebar( 'sidebar_sp' ) ) {
	dynamic_sidebar( 'sidebar_sp' );
} else {
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		dynamic_sidebar( 'sidebar-1' );
	}
}
if ( ! IS_MOBILE && is_active_sidebar( 'fix_sidebar' ) ) {
	echo '<div id="fix_sidebar" class="w-fixSide pc_">';
	dynamic_sidebar( 'fix_sidebar' );
	echo '</div>';
}
