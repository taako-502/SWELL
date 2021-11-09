<?php
namespace SWELL_Theme\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_notices', function() {
	if ( 'ok' === \SWELL_Theme::$licence_status ) return;
	$licence_check_url = admin_url( 'admin.php?page=swell_settings_swellers' );

	echo '<div class="notice notice-error">' .
			'<p>SWELLの<a href="' . esc_url( $licence_check_url ) . '">ユーザー認証</a>が完了していません。現在、バージョンアップデート機能が制限されています。</p>' .
		'</div>';
} );
