<?php
namespace SWELL_Theme\Update;

if ( ! defined( 'ABSPATH' ) ) exit;

// sslverifyオフ
add_filter( 'puc_request_update_options_theme-swell', function( $option ) {
	$option['sslverify'] = false;
	return $option;
} );

if ( ! class_exists( '\Puc_v4_Factory' ) ) {
	require_once T_DIRE . '/lib/update/plugin-update-checker.php';
}

if ( class_exists( '\Puc_v4_Factory' ) ) {
	$file_name = apply_filters( 'swell_update_json_name', 'update.json' );
	\Puc_v4_Factory::buildUpdateChecker(
		'https://loos.co.jp/products/swell/' . $file_name,
		T_DIRE . '/functions.php',
		'swell'
	);
}
