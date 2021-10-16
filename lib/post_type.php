<?php
namespace SWELL_Theme\Post_Type;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', __NAMESPACE__ . '\hook_init' );
add_action( 'admin_init', __NAMESPACE__ . '\hook_admin_init' );


/**
 * 投稿タイプの登録
 */
function hook_init() {
	$OPTION = \SWELL_Theme::get_option();

	/**
	 * LPを追加
	 */
	if ( ! $OPTION['remove_lp'] ) {
		register_post_type(
			'lp', // 投稿タイプ名の定義
			[
				'labels'              => [
					'name'          => 'LP',
					'singular_name' => 'LP',
				],
				'public'              => true,
				'exclude_from_search' => true,
				// 'menu_position' => 6,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'capability_type'     => 'page', // 固定ページと同じ権限レベルにする
				'map_meta_cap'        => true, // capability_type を使用するために必要
				'has_archive'         => false,
				'menu_icon'           => 'dashicons-media-default',
				'show_in_rest'        => true,  // ブロックエディターに対応させる
				'supports'            => [ 'title', 'editor', 'thumbnail', 'author', 'revisions', 'custom-fields' ],
			]
		);
	}

	/**
	 * ブログパーツを追加
	 * 寄稿者(contributor) には新規追加の権限をなくしておく（変に増やされないように）
	 */
	if ( ! $OPTION['remove_blog_parts'] ) {
		$parts_name = __( 'Blog Parts', 'swell' );
		register_post_type(
			'blog_parts', // 投稿タイプ名の定義
			[
				'labels'        => [
					'name'          => $parts_name,
					'singular_name' => $parts_name,
				],
				'public'        => false,
				// 'menu_position' => 6,
				'show_ui'       => true,
				'show_in_menu'  => true,
				'capabilities'  => ['create_posts' => 'create_blog_parts' ],
				'map_meta_cap'  => true, // capabilities を使用するために必要
				'has_archive'   => false,
				'menu_icon'     => 'dashicons-welcome-widgets-menus',
				'show_in_rest'  => true,  // ブロックエディターに対応させる
				'supports'      => [ 'title', 'editor' ],
			]
		);
	}

	/**
	 * 広告タグを追加
	 * 寄稿者(contributor) には新規追加の権限をなくしておく（変に増やされないように）
	 */
	if ( ! $OPTION['remove_ad_tag'] ) {
		$ad_name = __( 'Ad Tags', 'swell' );
		register_post_type(
			'ad_tag', // 投稿タイプ名の定義
			[
				'labels'        => [
					'name'          => $ad_name,
					'singular_name' => $ad_name,
				],
				'public'        => false,
				// 'menu_position' => 6,
				'show_ui'       => true,
				'show_in_menu'  => true,
				'capabilities'  => ['create_posts' => 'create_ad_tag' ],
				'map_meta_cap'  => true, // capabilities を使用するために必要
				'has_archive'   => false,
				'menu_icon'     => 'dashicons-tickets-alt',
				'show_in_rest'  => false,  // ブロックエディターに対応させる
				'supports'      => [ 'title' ],
			]
		);
	}

	/**
	 * ふきだしを追加
	 * 寄稿者(contributor) は画像を扱えないので、新規追加の権限をなくしておく
	 */
	if ( ! $OPTION['remove_balloon'] ) {

		// 新ふきだしデータに移行済の場合は、メニューを追加しない
		$table_name   = \SWELL_Theme::DB_TABLES['balloon'];
		$table_exists = \SWELL_Theme::check_table_exists( $table_name );

		if ( $table_exists ) return;

		$balloon_name = __( 'Balloons', 'swell' );
		register_post_type(
			'speech_balloon', // 投稿タイプ名の定義
			[
				'labels'        => [
					'name'          => $balloon_name,
					'singular_name' => $balloon_name,
				],
				'public'        => false,
				// 'menu_position' => 6,
				'show_ui'       => true,
				'show_in_menu'  => true,
				'capabilities'  => ['create_posts' => 'create_speech_balloon' ],
				'map_meta_cap'  => true, // capabilities を使用するために必要
				'has_archive'   => false,
				'menu_icon'     => 'dashicons-format-chat',
				'show_in_rest'  => false,
				'supports'      => [ 'title' ],
			]
		);
	}
}


/**
 * 独自権限を各権限グループに付与する
 * add_cap() は remove_cap() するまで永続的に権限が付与されることに注意。
 */
function hook_admin_init() {
	global $wp_roles;
	$wp_roles->add_cap( 'administrator', 'create_blog_parts' );
	$wp_roles->add_cap( 'editor', 'create_blog_parts' );
	$wp_roles->add_cap( 'author', 'create_blog_parts' );
	$wp_roles->add_cap( 'administrator', 'create_ad_tag' );
	$wp_roles->add_cap( 'editor', 'create_ad_tag' );
	$wp_roles->add_cap( 'author', 'create_ad_tag' );
	$wp_roles->add_cap( 'administrator', 'create_speech_balloon' );
	$wp_roles->add_cap( 'editor', 'create_speech_balloon' );
	$wp_roles->add_cap( 'author', 'create_speech_balloon' );
}
