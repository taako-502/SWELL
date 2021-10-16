<?php
namespace SWELL_Theme\Utility;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Balloon {

	/**
	 * 古いデータが残っているかどうか
	 */
	public static function has_old_balloon_data() {
		$the_query = new \WP_Query( [
			'post_type'      => 'speech_balloon',
			'no_found_rows'  => true,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		] );

		return (bool) $the_query->post_count;
	}


	/**
	 * ふきだし用テーブル作成
	 */
	public static function create_balloon_table() {

		$table_name = self::DB_TABLES['balloon'];
		if ( \SWELL_Theme::check_table_exists( $table_name ) ) return;

		global $wpdb;
		$collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title text NOT NULL,
			data text DEFAULT NULL,
			order_no bigint(20) unsigned NOT NULL,
			PRIMARY KEY (id)
		) {$collate};";

		// テーブル作成関数の読み込み・実行
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

	}


	/**
	 * ふきだしデータ
	 */
	public static function get_all_balloons() {
		if ( \SWELL_Theme::check_table_exists( self::DB_TABLES['balloon'] ) ) {
			return self::get_all_balloons__new();
		} else {
			return self::get_all_balloons__old();
		}
	}


	/**
	 * 全ふきだしデータ取得 - 新
	 */
	public static function get_all_balloons__new() {
		$rows = self::get_select_all_rows( self::DB_TABLES['balloon'], 'order_no' );
		if ( empty( $rows ) ) return [];

		$return_data = [];
		foreach ( $rows as $row ) {
			$bln_data = json_decode( $row['data'], true );

			$return_data[ 'id:' . $row['id'] ] = [
				'id'     => $row['id'],
				'title'  => $row['title'],
				'icon'   => $bln_data['icon'] ?? '',
				'name'   => $bln_data['name'] ?? '',
				'col'    => $bln_data['col'] ?? 'gray',
				'type'   => $bln_data['type'] ?? 'speaking',
				'align'  => $bln_data['align'] ?? 'left',
				'border' => $bln_data['border'] ?? 'none',
				'shape'  => $bln_data['shape'] ?? 'circle',
				// 'order'  => $row['order_no'],
			];
		}

		return $return_data;
	}


	/**
	 * 全ふきだしデータ取得 - 旧
	 */
	public static function get_all_balloons__old() {
		$return_data = [];
		$the_query   = new \WP_Query( [
			'post_type'      => 'speech_balloon',
			'no_found_rows'  => true,
			'posts_per_page' => -1,
		] );

		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$balloon_id         = get_the_ID();
			$balloon_title      = get_the_title();
			$balloon_icon       = get_post_meta( $balloon_id, 'balloon_icon', true );
			$balloon_name       = get_post_meta( $balloon_id, 'balloon_icon_name', true ) ?: get_the_title();
			$balloon_col        = get_post_meta( $balloon_id, 'balloon_col', true );
			$balloon_type       = get_post_meta( $balloon_id, 'balloon_type', true );
			$balloon_align      = get_post_meta( $balloon_id, 'balloon_align', true );
			$balloon_border     = get_post_meta( $balloon_id, 'balloon_border', true );
			$balloon_icon_shape = get_post_meta( $balloon_id, 'balloon_icon_shape', true );

			$return_data[ 'id:' . $balloon_id ] = [
				'id'     => $balloon_id,
				'title'  => $balloon_title,
				'icon'   => $balloon_icon,
				'name'   => $balloon_name,
				'col'    => $balloon_col,
				'type'   => $balloon_type,
				'align'  => $balloon_align,
				'border' => $balloon_border,
				'shape'  => $balloon_icon_shape,
			];
		endwhile;
		wp_reset_postdata();

		return $return_data;
	}


	/**
	 * ふきだしデータ取得
	 */
	public static function get_balloon_data( $getby, $val ) {
		if ( ! $getby || ! $val ) return [];

		if ( \SWELL_Theme::check_table_exists( self::DB_TABLES['balloon'] ) ) {
			return self::get_balloon_data__new( $getby, $val );
		} else {
			return self::get_balloon_data__old( $getby, $val );
		}
	}


	/**
	 * ふきだしデータ取得 - 新
	 */
	public static function get_balloon_data__new( $getby, $val, $return_row = false ) {

		$table_name = self::DB_TABLES['balloon'];
		if ( 'id' === $getby ) {
			$sql = "SELECT * FROM {$table_name} WHERE id = %d"; // IDでデータ取得
		} elseif ( 'title' === $getby ) {
			$sql = "SELECT * FROM {$table_name} WHERE title = %s";// タイトルでデータ取得
		}

		if ( ! $sql ) return [];

		global $wpdb;
		$query   = $wpdb->prepare( $sql, $val );
		$results = $wpdb->get_row( $query, ARRAY_A );

		// 結果をそのまま返す場合はここで返す
		if ( $return_row ) return $results;

		if ( ! $results ) return [];

		return json_decode( $results['data'], true );
	}


	/**
	 * ふきだしデータ取得 - 新
	 */
	public static function get_balloon_data__old( $getby, $val ) {
		$q_args = [
			'post_type'      => 'speech_balloon',
			'no_found_rows'  => true,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		];

		if ( 'id' === $getby ) {
			$q_args['p'] = $val; // IDでデータ取得
		} elseif ( 'title' === $getby ) {
			$q_args['title'] = $val; // タイトルでデータ取得
		} else {
			return [];
		}

		// ふきだしセットの指定があれば取得
		$the_query = new \WP_Query( $q_args );
		if ( ! $the_query->have_posts() ) return [];

		$bln_id = $the_query->posts[0];
		wp_reset_postdata();

		if ( ! $bln_id ) return [];

		return [
			'icon'   => get_post_meta( $bln_id, 'balloon_icon', true ),
			'name'   => get_post_meta( $bln_id, 'balloon_icon_name', true ),
			'col'    => get_post_meta( $bln_id, 'balloon_col', true ),
			'type'   => get_post_meta( $bln_id, 'balloon_type', true ),
			'align'  => get_post_meta( $bln_id, 'balloon_align', true ),
			'border' => get_post_meta( $bln_id, 'balloon_border', true ),
			'shape'  => get_post_meta( $bln_id, 'balloon_icon_shape', true ),
		];
	}
}
