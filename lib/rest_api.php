<?php
namespace SWELL_Theme\REST_API;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * カスタムフィールドの登録
 */
add_action( 'init', __NAMESPACE__ . '\register_rest_metas' );
function register_rest_metas() {
	register_meta( 'post', 'swell_btn_cv_data', [
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
		// 'object_subtype' => 'post'
	] );
}


/**
 * /wp/v2/swell-block-settings を追加
 * データベース名 : swell-block-settings
 */
add_action( 'rest_api_init', __NAMESPACE__ . '\hook_rest_api_init' );
function hook_rest_api_init() {

	register_rest_route( 'wp/v2', '/swell-block-settings', [
		'methods'             => 'GET',
		'callback'            => function() {

			$default = [
				'show_device_toolbtn'    => true,
				'show_margin_toolbtn'    => true,
				'show_shortcode_toolbtn' => true,
				'show_marker_top'        => false,
				'show_bgcolor_top'       => false,
				'show_fz_top'            => false,
				'show_header_postlink'   => true,
			];

			$settings = get_option( 'swell_block_settings' ) ?: [];

			// 何らかの理由で配列として受け取れなかった場合、空配列にリセット
			if ( ! is_array( $settings ) ) {
				$settings = [];
			}

			// 初期値とマージ
			$settings = array_merge( $default, $settings );

			return $settings;
		},
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		},
	] );

	// アップデート処理
	register_rest_route( 'wp/v2', '/swell-block-settings', [
		'methods'             => 'POST',
		'callback'            => function( $request ) {

			// 現在の設定を取得
			$settings = get_option( 'swell_block_settings' ) ?: [];

			// 何らかの理由で配列として受け取れなかった場合、空配列にリセット
			if ( ! is_array( $settings ) ) {
				$settings = [];
			}

			// 必要な情報が渡ってきているかどうかチェック
			if ( ! isset( $request['key'] ) || ! isset( $request['val'] ) ) {
				return false;
			}
			$key = $request['key'];
			$val = $request['val'];

			// 'key' の 値を 'val' で更新する。
			$settings[ $key ] = $val;

			// 設定を更新
			update_option( 'swell_block_settings', $settings );

			return $settings;
		},
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		},
	] );

}
