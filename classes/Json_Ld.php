<?php
namespace SWELL_Theme;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

class Json_Ld {

	// public function __construct() {}

	/**
	 * 生成
	 */
	public static function genrate() {
		$json_lds = [];

		if ( SWELL::is_top() ) {
			$json_lds['WebSite'] = self::get_front_json();

		} elseif ( is_single() || is_page() || is_home() ) {

			// 記事系ページ
			$article_json = self::get_article_json();
			if ( ! empty( $article_json ) ) {
				$json_lds['Article'] = $article_json;
			}
		}

		// グローバルナビ（SiteNavigationElement）
		$gnav_json = self::get_gnav_json();
		if ( ! empty( $gnav_json ) ) {
			$json_lds['SiteNavigationElement'] = $gnav_json;
		}

		// パンくずリスト（BreadcrumbList）
		$bread_json = self::get_bread_json( SWELL::$bread_json_data );
		if ( ! empty( $bread_json ) ) {
			$json_lds['BreadcrumbList'] = $bread_json;
		}

		return $json_lds;
	}


	/**
	 * フロントページの JSON-LD
	 */
	public static function get_front_json() {
		// memo : SearchAction{s}は検索フォームのnameに合わせる
		return [
			'@context'        => 'http://schema.org',
			'@type'           => 'WebSite',
			'url'             => home_url(),
			'potentialAction' => [
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={s}' ),
				'query-input' => 'name=s required',
			],
		];
	}


	/**
	 * 投稿・固定ページの JSON-LD
	 * See: https://developers.google.com/search/docs/advanced/structured-data/article
	 */
	public static function get_article_json() {

		$post_data = get_queried_object();
		if ( empty( $post_data ) ) return [];

		$the_id      = $post_data->ID;
		$title       = wp_strip_all_tags( get_the_title( $the_id ) );
		$url         = get_permalink( $the_id );
		$author_data = get_userdata( $post_data->post_author );

		$description = '';
		if ( class_exists( '\SSP_Output' ) && method_exists( '\SSP_Output', 'get_meta_data' ) ) {
			// SSP導入されていれば、そっちで生成された description を流用する
			$description = \SSP_Output::get_meta_data( 'description' ) ?: '';
		} else {
			$description = $post_data->post_content;
			$description = wp_strip_all_tags( strip_shortcodes( $description ), true );
			$description = mb_substr( $description, 0, 300 );
		}
		$thumb      = get_the_post_thumbnail_url( $the_id, 'full' ) ?: SWELL::get_noimg( 'url' );
		$logo_url   = SWELL::site_data( 'logo_url' ) ?: T_DIRE_URI . '/assets/img/article_schrma_logo.png';
		$author_url = get_the_author_meta( 'schema_url', $author_data->ID ) ?: $author_data->user_url ?: home_url( '/' );

		$data = [
			'url'                => $url,
			'headline'           => $title,
			'image_url'          => $thumb,
			'author_name'        => $author_data->display_name ?: '',
			'author_url'         => $author_url,
			'publisher_name'     => SWELL::site_data( 'title' ) ?: '',
			'publisher_logo_url' => $logo_url,
			'description'        => $description,
		];
		$data = apply_filters( 'swell_json_ld_article_data', $data, $the_id );

		return [
			'@context'          => 'http://schema.org',
			'@type'             => 'Article',
			'mainEntityOfPage'  => [
				'@type' => 'WebPage',
				'@id'   => $data['url'],
			],
			'headline'          => $data['headline'],
			'image'             => [
				'@type'  => 'ImageObject',
				'url'    => $data['image_url'],
			],
			'datePublished'     => $post_data->post_date,
			'dateModified'      => $post_data->post_modified,
			'author'            => [
				'@type'  => 'Person',
				'name'   => $data['author_name'],
				'url'    => $data['author_url'],
			],
			'publisher'         => [
				'@type'  => 'Organization',
				'name'   => $data['publisher_name'],
				'logo'   => [
					'@type'  => 'ImageObject',
					'url'    => $data['publisher_logo_url'],
				],
			],
			'description'       => $data['description'],
		];

	}


	/**
	 * グローバルナビの JSON-LD
	 */
	public static function get_gnav_json() {
		$locations  = get_nav_menu_locations(); // 各ロケーションにセットされているメニューIDを取得
		$gnav_items = [];
		$gnav_names = [];
		$gnav_urls  = [];

		// グロナビが何もセットされていれば
		if ( ! isset( $locations['header_menu'] ) ) return [];

		// wp_get_nav_menu_items()は ロケーション名から取得できないので、IDから取得。
		$gnav_id    = $locations['header_menu'];
		$gnav       = wp_get_nav_menu_object( $gnav_id ); // gnav自体情報を取得（WP_Term）
		$gnav_items = wp_get_nav_menu_items( $gnav_id ) ?: []; // gnavの各メニュー項目の情報を取得

		if ( empty( $gnav_items ) ) return [];

		foreach ( $gnav_items as $key => $menu_item ) {
			$gnav_names[] = wp_strip_all_tags( strip_shortcodes( $menu_item->title ) );
			$gnav_urls[]  = $menu_item->url;
		}

		return [
			'@context' => 'http://schema.org',
			'@type'    => 'SiteNavigationElement',
			'name'     => $gnav_names,
			'url'      => $gnav_urls,
		];
	}


	/**
	 * パンくずリストの JSON-LD
	 */
	public static function get_bread_json( $bread_json_data ) {
		if ( empty( $bread_json_data ) ) return [];

		$pos       = 1;
		$item_json = [];
		foreach ( $bread_json_data as $data ) :
			$item_json[] = [
				'@type'    => 'ListItem',
				'position' => $pos,
				'item'     => [
					'@id'  => $data['url'],
					'name' => wp_strip_all_tags( $data['name'] ),
				],
			];
			++$pos;
		endforeach;

		$bread_json = [
			'@context'        => 'http://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $item_json,
		];

		return $bread_json;
	}

}
