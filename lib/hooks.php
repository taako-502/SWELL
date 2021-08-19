<?php
namespace SWELL_Theme\Hooks;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/hooks/admin_display.php';
require_once __DIR__ . '/hooks/admin_toolbar.php';
require_once __DIR__ . '/hooks/edit_core_html.php';
require_once __DIR__ . '/hooks/remove.php';

// キャッシュクリア処理
if ( is_user_logged_in() ) {
	require_once __DIR__ . '/hooks/cache_clear.php';
}


/**
 * カテゴリーの説明文に対するフィルター処理を緩める (wp_filter_kses -> wp_kses_post に。)
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
add_filter( 'pre_term_description', 'wp_kses_post' );


/**
 * body_class()にクラスを追加する
 */
add_filter( 'body_class', '\SWELL_Theme\Hooks\hook_body_class' );
function hook_body_class( $classes ) {
	$SETTING = \SWELL_Theme::get_setting();

	if ( ! $SETTING['to_site_flat'] ) {
		$classes[] = '-body-solid';
	}

	if ( $SETTING['fix_body_bg'] ) {
		$classes[] = '-bg-fix';
	}
	if ( ! SWELL::is_show_index() ) {
		$classes[] = '-index-off';
	};

	if ( is_singular( 'lp' ) ) {
		$classes[] = '-sidebar-off';
	} else {

		// サイドバーの有無
		if ( SWELL::is_show_sidebar() ) {
			$classes[] = '-sidebar-on';
		} else {
			$classes[] = '-sidebar-off';
		}

		// フレーム設定
		$frame_class = SWELL::get_frame_class();
		$classes[]   = $frame_class;
	}

	// 後方互換性のためにつけるクラス
	if ( SWELL::is_top() ) {
		$classes[] = 'top';
	}

	$the_id = get_queried_object_id();
	if ( $the_id ) {
		$classes[] = 'id_' . $the_id;
	}

	return $classes;
}


/**
 * 特定のカテゴリーをトップから排除
 */
add_action( 'pre_get_posts', '\SWELL_Theme\Hooks\hook_pre_get_posts' );
function hook_pre_get_posts( $query ) {

	if ( is_admin() || ! $query->is_main_query() ) return;

	if ( $query->is_home() ) {

		$SETTING = \SWELL_Theme::get_setting();
		$exc_cat = explode( ',', $SETTING['exc_cat_id'] );
		$exc_tag = explode( ',', $SETTING['exc_tag_id'] );
		if ( ! empty( $exc_cat ) ) {
			$query->set( 'category__not_in', $exc_cat );
		}
		if ( ! empty( $exc_tag ) ) {
			$query->set( 'tag__not_in', $exc_tag );
		}
		if ( $SETTING['cache_top'] ) {
			$query->set( 'post_status', 'publish' ); // 非公開時期の投稿がキャッシュされないように
		}
	}
}


/**
 * HTTPヘッダー追加
 */
add_action( 'wp_headers', '\SWELL_Theme\Hooks\hook_wp_headers' );
function hook_wp_headers( $headers ) {
	$headers['Vary'] = 'User-Agent';
	return $headers;
}


/**
 * WordPressでのJPG圧縮比を最高品質で
 */
add_action( 'jpeg_quality', '\SWELL_Theme\Hooks\hook_jpeg_quality' );
function hook_jpeg_quality() {
	return 100;
}


/**
 * カテゴリーチェック時、順番をそのままに保つ
 */
add_action( 'wp_terms_checklist_args', '\SWELL_Theme\Hooks\hook_wp_terms_checklist_args', 10, 2 );
function hook_wp_terms_checklist_args( $args, $post_id ) {
	$args['checked_ontop'] = false;
	return $args;
}


/**
 * パスワード記事で MORE タグより前の文章だけ表示させる
 * (5.8からは第二引数で$post受け取れる) https://core.trac.wordpress.org/changeset/50791
 */
add_action( 'the_password_form', '\SWELL_Theme\Hooks\hook_the_password_form' );
function hook_the_password_form( $output ) {
	// phpcs:disable WordPress.WP.I18n.MissingArgDomain

	$post_data = get_post();

	// more前後を取得
	$content_data = get_extended( $post_data->post_content );

	// moreのあとがある時（ = moreタグが使用されている時）
	if ( ! empty( $content_data['extended'] ) ) {

		// デフォ：このコンテンツはパスワードで保護されています。閲覧するには以下にパスワードを入力してください。
		$output = str_replace(
			__( 'This content is password protected. To view it please enter your password below:' ),
			'この続きはパスワードで保護されています。全文を閲覧するためにはパスワードの入力が必要です。',
			$output
		);
		return apply_filters( 'the_content', $content_data['main'] ) . $output;
	}
	return $output;
}


/**
 * Feedlyでアイキャッチ画像を取得できるようにする
 */
add_filter( 'the_excerpt_rss', '\SWELL_Theme\Hooks\add_rss_thumb' );
add_filter( 'the_content_feed', '\SWELL_Theme\Hooks\add_rss_thumb' );
function add_rss_thumb( $content ) {
	global $post;

	$thumb = get_the_post_thumbnail_url( $post->ID, 'large' );
	if ( $thumb ) {
		$content = '<p><img src="' . $thumb . '" class="webfeedsFeaturedVisual" /></p>' . $content;
	}
	return $content;
}


/**
 * IEに警告を出す
 */
global $is_IE;
if ( $is_IE ) {
	add_action( 'admin_footer', '\SWELL_Theme\Hooks\show_ie_alert', 20 );
	add_action( 'wp_footer', '\SWELL_Theme\Hooks\show_ie_alert', 20 );
	function show_ie_alert() {
		swl_parts__ie_alert();
	}
}
