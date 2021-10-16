<?php
namespace SWELL_Theme\REST_API;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * エンドポイントを追加
 * callback では、returnで返すとそのままのデータを渡せる。wp_dieを使うと {code: 'wp_die', message: 'wp_dieで出力した文字列', ...}
 */
register_rest_route('wp/v2', '/swell-balloon', [
	// データの取得
	[
		'methods'             => 'GET',
		'permission_callback' => function( $request ) {
			return current_user_can( 'create_speech_balloon' );
		},
		'callback'            => function( $request ) {

			$table_name = \SWELL_Theme::DB_TABLES['balloon'];
			if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'No table.' );

			// データ取得
			if ( isset( $request['id'] ) ) {
				// 1件取得
				$row = \SWELL_Theme::get_balloon_data__new( 'id', $request['id'], true );
				return [
					'id'    => $row['id'],
					'title' => $row['title'],
					'data'  => json_decode( $row['data'], true ),
					'order' => $row['order_no'],
				];
			} else {
				// 全件取得
				$rows = \SWELL_Theme::get_select_all_rows( $table_name, 'order_no' );
				if ( ! $rows ) return [];

				$results = [];
				foreach ( $rows as $row ) {
					$results[] = [
						'id'    => $row['id'],
						'title' => $row['title'],
						'data'  => json_decode( $row['data'], true ),
						'order' => $row['order_no'],
					];
				}

				return $results;
			}
		},
	],
	// データの登録・更新
	[
		'methods'             => 'POST',
		'permission_callback' => function( $request ) {
			return current_user_can( 'create_speech_balloon' );
		},
		'callback'            => function( $request ) {

			$table_name = \SWELL_Theme::DB_TABLES['balloon'];
			if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'No table.' );

			$id    = isset( $request['id'] ) ? $request['id'] : null;
			$data  = isset( $request['data'] ) ? json_encode( $request['data'] ) : null;
			$title = isset( $request['title'] ) ? trim( $request['title'] ) : null;

			// タイトルは必須
			if ( ! $title ) wp_die( 'No title.' );

			global $wpdb;

			if ( $id ) {
				// 更新
				$result = $wpdb->update(
					$table_name,
					[
						'title' => $title,
						'data'  => $data,
					],
					['id' => $id ],
					[
						'%s',
						'%s',
					],
					[ '%d' ]
				);
				if ( $result !== false ) {
					return [
						'message'  => '吹き出しセットを更新しました。',
					];
				}
			} else {
				// 新規登録

				// 並び順の最大値を取得
				$sql   = "SELECT MAX(order_no) AS order_no FROM {$table_name}";
				$order = $wpdb->get_row( $sql, ARRAY_A );

				// 並び順を決定
				$order_no = $order ? $order['order_no'] + 1 : 1;

				$result = $wpdb->insert(
					$table_name,
					[
						'title'    => $title,
						'data'     => $data,
						'order_no' => $order_no,
					],
					[
						'%s',
						'%s',
						'%d',
					]
				);
				if ( $result ) {
					return [
						'insertId' => $wpdb->insert_id,
						'message'  => '吹き出しセットを登録しました。',
					];
				}
			}

			wp_die( 'データの更新に失敗しました。' );
		},
	],
	// データの削除
	[
		'methods'             => 'DELETE',
		'permission_callback' => function( $request ) {
			return current_user_can( 'create_speech_balloon' );
		},
		'callback'            => function( $request ) {

			$table_name = \SWELL_Theme::DB_TABLES['balloon'];
			if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'No table.' );

			// IDが渡ってきていない場合は終了
			$id = isset( $request['id'] ) ? $request['id'] : null;
			if ( ! $id ) wp_die( 'No ID.' );

			global $wpdb;
			$result = $wpdb->delete(
				$table_name,
				[ 'id' => $id ],
				[ '%d' ]
			);

			if ( $result ) {
				return [ 'status' => 'ok' ];
			}

			wp_die( '削除に失敗しました。' );
		},
	],
	// データの移行
	[
		'methods'             => 'PATCH',
		'permission_callback' => function( $request ) {
			return current_user_can( 'create_speech_balloon' );
		},
		'callback'            => function( $request ) {

			global $wpdb;
			$table_name = \SWELL_Theme::DB_TABLES['balloon'];

			// テーブルが存在しない場合は終了
			if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'no table' );

			// 旧データ全部取得
			$the_query = new \WP_Query( [
				'post_type'      => 'speech_balloon',
				'no_found_rows'  => true,
				'posts_per_page' => -1,
			] );

			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				// データ移行処理

			}

			wp_reset_postdata();

			return [ 'status' => 'ok' ];
		},
	],
]);


// ふきだし設定ページ（複製）
register_rest_route('wp/v2', '/swell-balloon-copy', [
	'methods'             => 'POST',
	'permission_callback' => function( $request ) {
		return current_user_can( 'create_speech_balloon' );
	},
	'callback'            => function( $request ) {
		$table_name = \SWELL_Theme::DB_TABLES['balloon'];
		if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'No table.' );

		// IDが渡ってきていない場合は終了
		$id = isset( $request['id'] ) ? $request['id'] : null;
		if ( ! $id ) wp_die( 'No ID.' );

		// 複製元の吹き出しを取得
		$results = \SWELL_Theme::get_balloon_data__new( 'id', $id, true );
		if ( ! $results ) wp_die( '複製元のデータ取得に失敗しました。' );

		global $wpdb;

		// 並び順の最大値を取得
		$sql   = "SELECT MAX(order_no) AS order_no FROM {$table_name}";
		$order = $wpdb->get_row( $sql, ARRAY_A );

		// 並び順を決定
		$order_no = $order ? $order['order_no'] + 1 : 1;

		// 複製したふきだしの登録
		$wpdb->insert(
			$table_name,
			[
				'title'    => "{$results['title']}_copy",
				'data'     => $results['data'],
				'order_no' => $order_no,
			],
			[
				'%s',
				'%s',
				'%d',
			]
		);

		// 複製したふきだしデータの取得
		$new_balloon = \SWELL_Theme::get_balloon_data__new( 'id', $wpdb->insert_id, true );
		return [
			'id'    => $new_balloon['id'],
			'title' => $new_balloon['title'],
			'data'  => json_decode( $new_balloon['data'], true ),
			'order' => $new_balloon['order_no'],
		];
	},
]);

// ふきだし設定ページ（並び替え）
register_rest_route('wp/v2', '/swell-balloon-sort', [
	'methods'             => 'POST',
	'permission_callback' => function( $request ) {
		return current_user_can( 'create_speech_balloon' );
	},
	'callback'            => function( $request ) {
		$balloon1 = isset( $request['balloon1'] ) ? $request['balloon1'] : null;
		$balloon2 = isset( $request['balloon2'] ) ? $request['balloon2'] : null;

		// 不正なパラメータの場合は修了
		if ( ! $balloon1 || ! $balloon2 ) wp_die( '入れ替え対象が正常に取得できませんでした。' );

		// テーブルが存在しない場合は終了
		$table_name = \SWELL_Theme::DB_TABLES['balloon'];
		if ( ! \SWELL_Theme::check_table_exists( $table_name ) ) wp_die( 'No table.' );

		global $wpdb;

		// 二つのレコードの並び順を入れ替え
		$result1 = $wpdb->update(
			$table_name,
			[ 'order_no' => $balloon2['order'] ],
			[ 'id' => $balloon1['id'] ],
			[ '%d' ]
		);

		$result2 = $wpdb->update(
			$table_name,
			[ 'order_no' => $balloon1['order'] ],
			[ 'id' => $balloon2['id'] ],
			[ '%d' ]
		);

		// アップデート失敗の場合は終了
		if ( $result1 === false || $result2 === false ) wp_die( '順序の更新に失敗しました。' );

		$rows = \SWELL_Theme::get_select_all_rows( $table_name, 'order_no' );

		if ( empty( $rows ) ) return [];

		$results = [];
		foreach ( $rows as $row ) {
			$results[] = [
				'id'    => $row['id'],
				'title' => $row['title'],
				'data'  => json_decode( $row['data'], true ),
				'order' => $row['order_no'],
			];
		}

		return $results;
	},
]);
