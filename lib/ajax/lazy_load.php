<?php
namespace SWELL_Theme\Ajax;

if ( ! defined( 'ABSPATH' ) ) exit;

// コンテンツ読み込み
add_action( 'wp_ajax_swell_load_footer', __NAMESPACE__ . '\load_footer' );
add_action( 'wp_ajax_nopriv_swell_load_footer', __NAMESPACE__ . '\load_footer' );

add_action( 'wp_ajax_swell_load_foot_before', __NAMESPACE__ . '\load_foot_before' );
add_action( 'wp_ajax_nopriv_swell_load_foot_before', __NAMESPACE__ . '\load_foot_before' );

add_action( 'wp_ajax_swell_load_after_article', __NAMESPACE__ . '\load_after_article' );
add_action( 'wp_ajax_nopriv_swell_load_after_article', __NAMESPACE__ . '\load_after_article' );


/**
 * フッター読み込み
 */
function load_footer() {
	if ( ! \SWELL_FUNC::check_ajax_nonce() ) wp_die( json_encode( '' ) );
	ob_start();
	\SWELL_FUNC::get_parts( 'parts/footer/footer_contents' );
	$return = ob_get_clean();
	wp_die( json_encode( $return ) );
}


/**
 * フッター直前ウィジェット読み込み
 */
function load_foot_before() {
	if ( ! \SWELL_FUNC::check_ajax_nonce() ) wp_die( json_encode( '' ) );
	ob_start();
	\SWELL_FUNC::get_parts( 'parts/footer/before_footer' );
	$return = ob_get_clean();
	wp_die( json_encode( $return ) );
}


/**
 * 記事下コンテンツ読み込み
 */
function load_after_article() {
	if ( ! \SWELL_FUNC::check_ajax_nonce() || ! isset( $_POST['post_id'] ) ) {
		wp_die( json_encode( '' ) );
	}

	ob_start();

	// ループ回す
	$post_id = $_POST['post_id'] ?: '';

	$the_query = new \WP_Query( [
		'p'              => $post_id,
		'no_found_rows'  => true,
		'posts_per_page' => 1,
	] );
	if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		\SWELL_FUNC::get_parts( 'parts/single/after_article', ['post_id' => $post_id ] );
	endwhile;
	endif;
	wp_reset_postdata();

	$return = ob_get_clean();
	wp_die( json_encode( $return ) );
}
