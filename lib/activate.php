<?php
namespace SWELL_Theme\Activate;

if ( ! defined( 'ABSPATH' ) ) exit;

use \SWELL_Theme as SWELL;

add_action( 'admin_init', function() {
}, 99 );

add_action( 'current_screen', function() {
	$current_screen = get_current_screen();
	global $hook_suffix;

	// アクティベートページのみの処理
	if ( false !== strpos( $hook_suffix, 'swell_settings_swellers' ) ) {
		// phpcs:ignore
		if ( isset( $_GET['cache'] ) && 'delete' === wp_unslash( $_GET['cache'] ) ) {
			delete_transient( 'swlr_user_status' );
			wp_safe_redirect( admin_url( 'admin.php?page=swell_settings_swellers' ) );
		}

		check_post();
	}

	self::check_swlr_licence();

}, 99 );


function check_post() {
	if ( isset( $_POST['sweller_email'] ) ) {

		$posted_email = sanitize_email( $_POST['sweller_email'] );

		// nonceチェック
		if ( ! SWELL::check_nonce( '_email' ) ) wp_die( '不正アクセスです。' );

		// 先にDB程度更新
		update_option( 'sweller_email', $posted_email );

		delete_transient( 'swlr_user_status' );
		SWELL::check_swlr_licence( $posted_email );
	}

}
