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
 * エンドポイントを追加
 */
add_action( 'rest_api_init', __NAMESPACE__ . '\hook_rest_api_init' );
function hook_rest_api_init() {

	// SWELLブロック設定の取得
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

	// SWELLブロック設定のアップデート処理
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

	// PV数の計測
	register_rest_route( 'wp/v2', '/swell-ct-pv', [
		'methods'             => 'POST',
		'callback'            => function( $request ) {
			if ( ! isset( $request['postid'] ) ) wp_die( json_encode( [] ) );

			\SWELL_Theme::set_post_views( $request['postid'] );

			$return = [
				'postid'   => $request['postid'],
			];

			return json_encode( $return );
		},
	] );

	// ボタンの計測
	register_rest_route( 'wp/v2', '/swell-ct-btn-data', [
		'methods'             => 'POST',
		'callback'            => function( $request ) {
			if ( ! isset( $request['btnid'] ) || ! isset( $request['postid'] ) || ! isset( $request['ct_name'] ) ) wp_die( json_encode( [] ) );

			$btnid   = $request['btnid'];
			$postid  = $request['postid'];
			$ct_name = $request['ct_name']; // 何をカウントするか

			// 不正なパラメータ
			if ( ! in_array( $ct_name, [ 'pv', 'imp', 'click' ], true ) ) wp_die( json_encode( [] ) );

			$btn_cv_metas = get_post_meta( $postid, 'swell_btn_cv_data', true ) ?: [];

			if ( $btn_cv_metas ) $btn_cv_metas = json_decode( $btn_cv_metas, true );

			// ここで配列になっていなければ何かがおかしいので return
			if ( ! is_array( $btn_cv_metas ) ) wp_die( json_encode( [] ) );

			if ( 'pv' === $ct_name ) {
				// PV数
				$btnids = explode( ',', $btnid );
				foreach ( $btnids as $the_id ) {
					$btn_cv_metas = ct_up_btn_data( $btn_cv_metas, $the_id, $ct_name );
				}
			} else {
				// 表示回数、クリック数
				$btn_cv_metas = ct_up_btn_data( $btn_cv_metas, $btnid, $ct_name );
			}

			$btn_cv_metas = json_encode( $btn_cv_metas );

			update_post_meta( $postid, 'swell_btn_cv_data', $btn_cv_metas );

			$return = [
				'btnid'   => $btnid,
				'cvdata'  => $btn_cv_metas,
				'ct_name' => $ct_name,
			];

			return json_encode( $return );
		},
	] );

	// 広告の計測
	register_rest_route( 'wp/v2', '/swell-ct-ad-data', [
		'methods'             => 'POST',
		'callback'            => function( $request ) {
			if ( ! isset( $request['adid'] ) || ! isset( $request['ct_name'] ) ) wp_die( json_encode( [] ) );

			$ad_id   = $request['adid'];
			$ct_name = $request['ct_name']; // 何をカウントするか

			// 不正なパラメータ
			if ( ! in_array( $ct_name, [ 'pv', 'imp', 'click' ], true ) ) wp_die( json_encode( [] ) );

			$return = [];

			switch ( $ct_name ) {
				// PV数
				case 'pv':
					$ad_ids = explode( ',', $ad_id );
					foreach ( $ad_ids as $the_id ) {
						$count       = (int) get_post_meta( $ad_id, 'pv_count', true );
						$update_meta = update_post_meta( $the_id, 'pv_count', $count + 1 );

						$return[] = [
							'id' => $the_id,
							'ct' => $count,
						];
					}
					break;

				// 広告表示回数
				case 'imp':
					$count       = (int) get_post_meta( $ad_id, 'imp_count', true );
					$update_meta = update_post_meta( $ad_id, 'imp_count', $count + 1 );

					$return = [
						'id' => $ad_id,
						'ct' => $count,
					];
					break;

				// 広告クリック数
				case 'click':
					// どこがクリックされたか
					$ad_target = ( isset( $request['target'] ) ) ? $request['target'] : '';
					$meta_key  = $ad_target . '_clicked_ct';

					$count       = (int) get_post_meta( $ad_id, $meta_key, true );
					$update_meta = update_post_meta( $ad_id, $meta_key, $count + 1 );

					$return = [
						'id'     => $ad_id,
						'target' => $ad_target,
						'ct'     => $count,
					];
					break;
			}

			return json_encode( $return );
		},
	] );

	// 広告の計測データのリセット
	register_rest_route( 'wp/v2', '/swell-reset-ad-data', [
		'methods'             => 'POST',
		'callback'            => function( $request ) {
			if ( ! isset( $request['id'] ) ) wp_die( 'リセットに失敗しました' );

			$ad_id = $request['id'];

			$keys = [
				'imp_count',
				'pv_count',
				'tag_clicked_ct',
				'btn1_clicked_ct',
				'btn2_clicked_ct',
			];
			foreach ( $keys as $key ) {
				$update_meta = update_post_meta( $ad_id, $key, 0 );
			}
			return 'リセットに成功しました';
		},
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		},
	] );
}

// ボタン計測データの加算
function ct_up_btn_data( $cv_data, $btnid, $ct_name ) {
	if ( ! isset( $cv_data[ $btnid ] ) ) {
		$cv_data[ $btnid ]             = [];
		$cv_data[ $btnid ][ $ct_name ] = 1;
	} else {
		$count                         = (int) $cv_data[ $btnid ][ $ct_name ];
		$cv_data[ $btnid ][ $ct_name ] = $count + 1;
	}

	return $cv_data;
};
