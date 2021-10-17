<?php
if ( version_compare( phpversion(), '7.3.0', '<' ) ) {
	$GLOBALS['swell_env_err_text'] = 'お使いのPHPバージョン(' . phpversion() . ')がSWELLの必要動作環境を満たしていません。PHPバージョン7.3.0以上へ更新してください。';
}
if ( version_compare( get_bloginfo( 'version' ), '5.5', '<' ) ) {
	$GLOBALS['swell_env_err_text'] = 'お使いのWordPressバージョン(' . get_bloginfo( 'version' ) . ')がSWELLの必要動作環境を満たしていません。WordPressバージョン5.5以上へ更新してください。';
}

function swl__throw_env_err() {
	if ( is_admin() ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice error"><p>' . $GLOBALS['swell_env_err_text'] . '</p></div>'; // phpcs:ignore
		} );
	} else {
		wp_die( $GLOBALS['swell_env_err_text'] ); // phpcs:ignore
	}
}
