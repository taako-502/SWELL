<?php
namespace SWELL_Theme\Ajax;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/ajax/lazy_load.php';
require_once __DIR__ . '/ajax/ct_ctr.php';
require_once __DIR__ . '/ajax/reset_data.php';

/**
 * PVカウント
 */
// add_action( 'wp_ajax_swell_pv_count', __NAMESPACE__ . '\pv_count' );
add_action( 'wp_ajax_nopriv_swell_pv_count', __NAMESPACE__ . '\pv_count' );
function pv_count() {
	if ( \SWELL_FUNC::check_ajax_nonce() && isset( $_POST['post_id'] ) ) {
		\SWELL_FUNC::set_post_views( $_POST['post_id'] );
	}
	wp_die( json_encode( '' ) );
}
