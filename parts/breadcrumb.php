<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// トップページでは何も出力しない
if ( \SWELL_Theme::is_top() ) return false;

$SETTING = \SWELL_Theme::get_setting();

$wp_obj    = get_queried_object();  // そのページのWPオブジェクトを取得
$list_data = [];

// 「投稿ページ」をパンくずリストに入れる場合
$home_data = null;
if ( $SETTING['breadcrumb_set_home'] ) {
	if ( $home_page_id = (int) get_option( 'page_for_posts' ) ) {
		$home_data = [
			'url'  => get_permalink( $home_page_id ),
			'name' => get_the_title( $home_page_id ),
		];
	}
}
/**
 * 生成処理
 */
if ( is_attachment() ) {

	/**
	 * 添付ファイルページ ※ is_single()もtrueになるので先に分岐
	 */
	$post_title  = apply_filters( 'the_title', $wp_obj->post_title );
	$list_data[] = [
		'url'  => '',
		'name' => $post_title,
	];


} elseif ( is_single() ) {

	/**
	 * 投稿ページ
	 */
	$the_id        = $wp_obj->ID;
	$the_post_type = $wp_obj->post_type;
	$post_title    = apply_filters( 'the_title', $wp_obj->post_title );

	// カスタム投稿タイプかどうか
	if ( $the_post_type !== 'post' ) {

		$the_tax = '';

		// 投稿タイプに紐づいたタクソノミーを取得 (投稿フォーマットは除く)
		$tax_array = get_object_taxonomies( $the_post_type, 'names' );
		foreach ( $tax_array as $tax_name ) {
			if ( $tax_name !== 'post_format' ) {
				$the_tax = $tax_name;
				break;
			}
		}

		$post_type_link  = get_post_type_archive_link( $the_post_type );
		$post_type_label = get_post_type_object( $the_post_type )->label;

		// カスタム投稿タイプ名の表示
		$list_data[] = [
			'url'  => $post_type_link,
			'name' => $post_type_label,
		];

	} else {

		// 通常の投稿はカテゴリーを表示する
		$the_tax = 'category';

		// 「投稿ページ」をパンくずリストに入れる場合
		if ( $home_data ) $list_data[] = $home_data;

	}

	// 投稿に紐づくタームを全て取得
	$terms = get_the_terms( $the_id, $the_tax );

	// タームーが紐づいていれば表示
	if ( $terms !== false ) {

		// 子を持たないタームだけを集めた配列
		$child_terms = [];

		// 子を持つタームだけを集めた配列
		$parents_list = [];

		// 全タームの親IDを取得
		foreach ( $terms as $the_term ) {
			if ( $the_term->parent !== 0 ) {
				$parents_list[] = $the_term->parent;
			}
		}

		// 親リストに含まれないタームのみ取得
		foreach ( $terms as $the_term ) {
			if ( ! in_array( $the_term->term_id, $parents_list, true ) ) {
				$child_terms[] = $the_term;
			}
		}

		// 最下層のターム配列から一つだけ取得
		$the_term = $child_terms[0];

		if ( $the_term->parent !== 0 ) {

			// 親タームのIDリストを取得
			$parent_array = array_reverse( get_ancestors( $the_term->term_id, $the_tax ) );

			foreach ( $parent_array as $parent_id ) {
				$parent_term = get_term( $parent_id, $the_tax );
				$parent_link = get_term_link( $parent_id, $the_tax );
				$parent_name = $parent_term->name;

				$list_data[] = [
					'url'  => $parent_link,
					'name' => $parent_name,
				];
			}
		}

		// 最下層のタームを表示
		$term_link = get_term_link( $the_term->term_id, $the_tax );
		$term_name = $the_term->name;

		$list_data[] = [
			'url'  => $term_link,
			'name' => $term_name,
		];
	}

	// 投稿自身の表示
	$list_data[] = [
		'url'  => '',
		'name' => $post_title,
	];


} elseif ( is_page() || is_home() ) {

	/**
	 * 固定ページ
	 * $wp_obj : WP_Post
	 */
	$page_id    = $wp_obj->ID;
	$page_title = apply_filters( 'the_title', $wp_obj->post_title );

	// 親ページがあれば順番に表示
	if ( $wp_obj->post_parent !== 0 ) {
		$parent_array = array_reverse( get_post_ancestors( $page_id ) );
		foreach ( $parent_array as $parent_id ) {
			$parent_link = get_permalink( $parent_id );
			$parent_name = get_the_title( $parent_id );

			$list_data[] = [
				'url'  => $parent_link,
				'name' => $parent_name,
			];
		}
	}
	// 投稿自身の表示
	$list_data[] = [
		'url'  => '',
		'name' => $page_title,
	];

} elseif ( is_post_type_archive() ) {

	/**
	 * 投稿タイプアーカイブページ
	 * $wp_obj : WP_Post_Type
	 */
	$list_data[] = [
		'url'  => '',
		'name' => $wp_obj->label,
	];

} elseif ( is_date() ) {

	/**
	 * 日付アーカイブ ※ $wp_obj : null
	 */
	$y     = get_query_var( 'year' );
	$month = get_query_var( 'monthnum' );
	$day   = get_query_var( 'day' );

	if ( $day !== 0 ) {
		// 日別アーカイブ
		$list_data[] = [
			'url'  => get_year_link( $y ),
			'name' => $y . '年',
		];
		$list_data[] = [
			'url'  => get_month_link( $y, $month ),
			'name' => $month . '月',
		];
		$list_data[] = [
			'url'  => '',
			'name' => $day . '日',
		];

	} elseif ( $month !== 0 ) {
		// 月別アーカイブ
		$list_data[] = [
			'url'  => get_year_link( $y ),
			'name' => $y . '年',
		];
		$list_data[] = [
			'url'  => '',
			'name' => $month . '月',
		];

	} else {
		// 年別アーカイブ
		$list_data[] = [
			'url'  => '',
			'name' => $y . '年',
		];
	}
} elseif ( is_author() ) {

	/**
	 * 投稿者アーカイブ
	 */
	$list_data[] = [
		'url'  => '',
		'name' => $wp_obj->display_name . ' の執筆記事',
	];

} elseif ( is_archive() ) {

	/**
	 * その他アーカイブ（タームアーカイブ）
	 */

	// 「投稿ページ」をパンくずリストに入れる場合
	if ( $home_data && ( is_category() || is_tag() ) ) {
		$list_data[] = $home_data;
	}

	// ターム情報について
	$term_id   = $wp_obj->term_id;
	$term_name = $wp_obj->name;
	$tax_name  = $wp_obj->taxonomy;

	// 親ページがあれば順番に表示
	if ( $wp_obj->parent !== 0 ) {

		$parent_array = array_reverse( get_ancestors( $term_id, $tax_name ) );
		foreach ( $parent_array as $parent_id ) {
			$parent_term = get_term( $parent_id, $tax_name );
			$parent_link = get_term_link( $parent_id, $tax_name );
			$parent_name = $parent_term->name;

			$list_data[] = [
				'url'  => $parent_link,
				'name' => $parent_name,
			];
		}
	}

	// ターム自身の表示
	$list_data[] = [
		'url'  => '',
		'name' => $term_name,
	];

} elseif ( is_search() ) {

	/**
	 * 検索結果ページ
	 */
	$list_data[] = [
		'url'  => '',
		'name' => '「' . get_search_query() . '」で検索した結果',
	];

} elseif ( is_404() ) {

	/**
	 * 404ページ
	 */
	$list_data[] = [
		'url'  => '',
		'name' => 'お探しの記事は見つかりませんでした。',
	];

} else {

	/**
	 * その他のページ（一応）
	 */
	$list_data[] = [
		'url'  => '',
		'name' => get_the_title(),
	];
}

/**
 * 出力処理
 */
$list_html  = '';
$json_array = []; // JSON-LD用の配列
$list_data  = apply_filters( 'swell_breadcrumb_list_data', $list_data );
foreach ( $list_data as $data ) {

	// urlの有無で処理を分ける
	if ( $data['url'] ) {

		// JSON LD用の配列にも追加
		$json_array[] = $data;

		$list_html .= '<li class="p-breadcrumb__item">' .
			'<a href="' . esc_url( $data['url'] ) . '" class="p-breadcrumb__text">' .
				'<span>' . esc_html( wp_strip_all_tags( $data['name'] ) ) . '</span>' .
			'</a>' .
		'</li>';

	} else {

		$list_html .= '<li class="p-breadcrumb__item">' .
			'<span class="p-breadcrumb__text">' . esc_html( wp_strip_all_tags( $data['name'] ) ) . '</span>' .
		'</li>';

	}
}

// HTMLの出力
$add_class = \SWELL_Theme::get_setting( 'hide_bg_breadcrumb' ) ? '' : ' -bg-on';

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<div id="breadcrumb" class="p-breadcrumb' . $add_class . '">' .
	'<ol class="p-breadcrumb__list l-container">' .
		'<li class="p-breadcrumb__item">' .
			'<a href="' . esc_url( \SWELL_Theme::site_data( 'home' ) ) . '" class="p-breadcrumb__text">' .
				'<span class="icon-home"> ' . $SETTING['breadcrumb_home_text'] . '</span>' .
			'</a>' .
		'</li>' .
		$list_html .
	'</ol>' .
'</div>';

// JSON-LDデータの受け渡し
\SWELL_Theme::$bread_json_data = $json_array;
