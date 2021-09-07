<?php
namespace SWELL_Theme\REST_API;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * エンドポイントを追加
 */
add_action( 'rest_api_init', __NAMESPACE__ . '\hook_rest_api_init' );
function hook_rest_api_init() {

	// SWELLブロック設定の取得
	register_rest_route( 'wp/v2', '/swell-block-settings', [
		'methods'             => 'GET',
		'permission_callback' => [ '\SWELL_Theme', 'is_administrator' ],
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

	] );

	// SWELLブロック設定のアップデート処理
	register_rest_route( 'wp/v2', '/swell-block-settings', [
		'methods'             => 'POST',
		'permission_callback' => [ '\SWELL_Theme', 'is_administrator' ],
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
	] );

	// PV数の計測
	register_rest_route( 'wp/v2', '/swell-ct-pv', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
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
		'permission_callback' => '__return_true',
		'callback'            => function( $request ) {
			if ( ! isset( $request['btnid'] ) || ! isset( $request['postid'] ) || ! isset( $request['ct_name'] ) ) wp_die( json_encode( [] ) );

			$btnid   = $request['btnid'];
			$postid  = (int) $request['postid'];
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
		'permission_callback' => '__return_true',
		'callback'            => function( $request ) {
			if ( ! isset( $request['adid'] ) || ! isset( $request['ct_name'] ) ) wp_die( json_encode( [] ) );

			$ad_id   = (int) $request['adid'];
			$ct_name = $request['ct_name']; // 何をカウントするか

			// 不正なパラメータ
			if ( ! in_array( $ct_name, [ 'pv', 'imp', 'click' ], true ) ) wp_die( json_encode( [] ) );

			$return = [];
			switch ( $ct_name ) {
				// PV数
				case 'pv':
					$ad_ids = explode( ',', $ad_id );
					foreach ( $ad_ids as $the_id ) {
						$return[] = ct_up_ad_data( $ad_id, 'pv_count' );
					}
					break;

				// 広告表示回数
				case 'imp':
					$return = ct_up_ad_data( $ad_id, 'imp_count' );
					break;

				// 広告クリック数
				case 'click':
					// どこがクリックされたか
					$ad_target = ( isset( $request['target'] ) ) ? $request['target'] : '';
					$meta_key  = $ad_target . '_clicked_ct';
					$return    = ct_up_ad_data( $ad_id, $meta_key );
					break;
			}

			return json_encode( $return );
		},
	] );

	// 広告の計測データのリセット
	register_rest_route( 'wp/v2', '/swell-reset-ad-data', [
		'methods'             => 'POST',
		'permission_callback' => [ '\SWELL_Theme', 'is_administrator' ],
		'callback'            => function( $request ) {
			if ( ! isset( $request['id'] ) ) wp_die( 'リセットに失敗しました' );

			$ad_id = (int) $request['id'];

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
	] );

	// コンテンツの遅延読み込み
	register_rest_route( 'wp/v2', '/swell-lazyload-contents', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function( $request ) {
			if ( ! isset( $request['placement'] ) ) wp_die( json_encode( [] ) );

			$placement = $request['placement']; // 遅延読み込みするコンテンツ

			// 不正なパラメータ
			if ( ! in_array( $placement, [ 'after_article', 'before_footer_widget', 'footer' ], true ) ) wp_die( json_encode( [] ) );

			$return = [];

			switch ( $placement ) {
				case 'after_article':
					// 記事下コンテンツ
					if ( ! isset( $request['post_id'] ) ) wp_die( json_encode( [] ) );
					$post_id = $request['post_id'];

					ob_start();

					// ループ回す
					$post_id = $_POST['post_id'] ?? '';

					$the_query = new \WP_Query( [
						'p'              => $post_id,
						'no_found_rows'  => true,
						'posts_per_page' => 1,
					] );
					if ( $the_query->have_posts() ) :
					while ( $the_query->have_posts() ) :
						$the_query->the_post();
						\SWELL_Theme::get_parts( 'parts/single/after_article' );
					endwhile;
					endif;
					wp_reset_postdata();

					$contents = ob_get_clean();
					break;

				// フッター直前ウィジェット
				case 'before_footer_widget':
					ob_start();
					\SWELL_Theme::get_parts( 'parts/footer/before_footer' );
					$contents = ob_get_clean();
					break;
				// フッターコンテンツ
				case 'footer':
					ob_start();
					\SWELL_Theme::get_parts( 'parts/footer/footer_contents' );
					$contents = ob_get_clean();
					break;
			}

			return $contents;
		},
	] );

	// キャッシュのクリア
	register_rest_route( 'wp/v2', '/swell-reset-cache', [
		'methods'             => 'POST',
		'permission_callback' => [ '\SWELL_Theme', 'is_administrator' ],
		'callback'            => function( $request ) {
			if ( ! isset( $request['action'] ) ) wp_die( json_encode( [] ) );

			$action = $request['action'];

			// 不正なパラメータ
			if ( ! in_array( $action, [ 'cache', 'card_cache' ], true ) ) wp_die( json_encode( [] ) );

			switch ( $action ) {
				// カスタマイザー
				case 'cache':
					// キャッシュ
					\SWELL_Theme::clear_cache();
					break;
				// カスタマイザー
				case 'card_cache':
					// ブログカード
					\SWELL_Theme::clear_card_cache();
					break;
			}

			return 'キャッシュクリアに成功しました。';
		},
	] );

	// 設定のクリア
	register_rest_route( 'wp/v2', '/swell-reset-settings', [
		'methods'             => 'POST',
		'permission_callback' => [ '\SWELL_Theme', 'is_administrator' ],
		'callback'            => function( $request ) {
			if ( ! isset( $request['action'] ) ) wp_die( json_encode( [] ) );

			$action = $request['action'];

			// 不正なパラメータ
			if ( ! in_array( $action, [ 'customizer', 'pv' ], true ) ) wp_die( json_encode( [] ) );

			switch ( $action ) {
				// カスタマイザー
				case 'customizer':
					delete_option( \SWELL_Theme::DB_NAME_CUSTOMIZER );
					\SWELL_Theme::clear_cache();
					break;

				// PV
				case 'pv':
					$args      = [
						'post_type'      => 'post',
						'fields'         => 'ids',
						'posts_per_page' => -1,
					];
					$the_query = new \WP_Query( $args );
					if ( $the_query->have_posts() ) {
						foreach ( $the_query->posts as $the_id ) {
							delete_post_meta( $the_id, SWELL_CT_KEY );
						}
					}
					wp_reset_postdata();
					break;
			}

			return 'リセットに成功しました。';
		},
	] );
}


/**
 * ボタン計測データの加算
 */
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


/**
 * 広告計測データの加算
 */
function ct_up_ad_data( $ad_id, $meta_key ) {
	$count = (int) get_post_meta( $ad_id, $meta_key, true );
	$count++;
	update_post_meta( $ad_id, $meta_key, $count );

	return [
		'id'   => $ad_id,
		'meta' => $meta_key,
		'ct'   => $count,
	];
};
