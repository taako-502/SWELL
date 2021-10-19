<?php
namespace SWELL_Theme\Content_Filter;

use \SWELL_Theme as SWELL;

if ( ! defined( 'ABSPATH' ) ) exit;

// memo: ショートコード展開の優先度:11 , ダイナミックブロック展開の優先度:9

// エディター側では処理されないように。
if ( ! is_admin() ) {

	global $wp_embed;

	// ショートコード展開より後に実行する処理
	add_filter( 'the_content', [ $wp_embed, 'autoembed' ], 12 ); // 再利用ブロックとブログパーツでも埋め込みを有効化する
	add_filter( 'the_content', __NAMESPACE__ . '\remove_empty_p', 12 );

	// SEOプラグインのディスクリプション生成時に発火しないように、登録を遅らせる。
	add_action('wp_head', function () {
		add_filter( 'the_content', __NAMESPACE__ . '\add_toc', 12 );

		// URLの自動ブログカード化機能: プラグインなどで不具合があるページだけオフにしたりできるように apply_filters 付き
		$remove_url_to_card = apply_filters( 'swell_remove_url_to_card', SWELL::get_option( 'remove_url2card' ) );
		if ( ! $remove_url_to_card ) {
			add_filter( 'the_content', __NAMESPACE__ . '\url_to_blog_card', 12 );
		}

		if ( 'lazysizes' === SWELL::$lazy_type ) {
			add_filter( 'the_content', __NAMESPACE__ . '\add_lazysizes', 12 );
		}

		add_filter( 'the_content', __NAMESPACE__ . '\check_luminous', 12 );
		add_filter( 'swell_do_blog_parts', __NAMESPACE__ . '\check_luminous', 12 );

	}, 99 );
};

// ajaxでの遅延読み込み時も is_admin() true になって処理されなくなってしまうので、ウィジェット関連は is_admin 分岐外にて。
add_action( 'wp_loaded', function () {

	global $wp_embed;

	// カスタムHTMLウィジェット にも処理を追加
	// add_filter( 'widget_text', __NAMESPACE__ . '\remove_p_by_shortcode' );
	add_filter( 'widget_text', 'do_shortcode' );
	add_filter( 'widget_text', [ $wp_embed, 'autoembed' ], 12 );
	add_filter( 'widget_text', __NAMESPACE__ . '\add_toc_on_widget', 12 );
	add_filter( 'widget_text', __NAMESPACE__ . '\remove_empty_p', 12 );

	// テキストウィジェット にも処理を追加
	add_filter( 'widget_text_content', [ $wp_embed, 'autoembed' ], 12 );
	add_filter( 'widget_text_content', __NAMESPACE__ . '\add_toc_on_widget', 12 );
	add_filter( 'widget_text_content', __NAMESPACE__ . '\remove_empty_p', 12 );

	if ( 'lazysizes' === SWELL::$lazy_type ) {
		add_filter( 'widget_text', __NAMESPACE__ . '\add_lazysizes', 12 );
		add_filter( 'widget_text_content', __NAMESPACE__ . '\add_lazysizes', 12 );
	}

}, 20 );

// 過去ブロックとの後方互換性を保つための処理
// require_once( T_DIRE.'/lib/block/replace_old_blocks.php' );


/**
 * ショートコードによるpタグのズレを除去
 */
// function remove_p_by_shortcode( $content ) {
// 	$content = preg_replace( '/<p>\[([^\]]*)\]<\/p>/', '[$1]', $content );
// 	return $content;
// }


/**
 * 空のpタグを除去
 */
function remove_empty_p( $content ) {
	$content = str_replace( '<p></p>', '', $content );
	return $content;
}

/**
 * 目次ショートコードだけセット
 */
function add_toc_on_widget( $content ) {
	return add_toc( $content, false );
}


/**
 * 目次 + 目次広告のセット
 */
function add_toc( $content, $is_content_hook = true ) {

	SWELL::create_toc( $content );

	// ウィジェットですでにswell_tocで生成されている時に本文エリアでの2重生成を防ぐ
	// if ( SWELL::$added_toc ) return $content;

	$SETTING = SWELL::get_setting();
	$toc_ad  = '';
	$toc     = '';

	// ショートコードで目次が挿入されているかどうか
	if ( false !== strpos( $content, 'class="swell-toc-placeholder"' ) ) {

		// 目次本体
		$toc = '<div class="p-toc -called-from-sc -' . $SETTING['index_style'] . '">' .
			'<span class="p-toc__ttl">' . $SETTING['toc_title'] . '</span></div>';

		// 目次広告コード
		if ( SWELL::is_show_toc_ad() ) {
			$toc_ad = \SWELL_PARTS::toc_ad();
		}

		// 広告を目次の前に設置するか、後ろに設置するか
		$toc_content = 'after' === $SETTING['toc_ad_position'] ? $toc . $toc_ad : $toc_ad . $toc;

		$content = str_replace(
			'<div class="swell-toc-placeholder"></div>',
			$toc_content,
			$content
		);

		SWELL::$added_toc = true;

	} elseif ( $is_content_hook ) {

		if ( SWELL::$added_toc ) return $content;

		// 目次本体
		if ( SWELL::is_show_index() ) {
			$toc = '<div class="p-toc -' . $SETTING['index_style'] . '">' .
				'<span class="p-toc__ttl">' . $SETTING['toc_title'] . '</span></div>';
		}

		// 目次広告コード
		if ( SWELL::is_show_toc_ad() ) {
			$toc_ad = \SWELL_PARTS::toc_ad();
		}

		// 広告を目次の前に設置するか、後ろに設置するか
		$toc_content = 'after' === $SETTING['toc_ad_position'] ? $toc . $toc_ad : $toc_ad . $toc;

		// １つ目の見出しの前へ設置
		$tag = '/^<h2.*?>/im';
		if ( $toc_content && preg_match( $tag, $content, $tags ) ) {

			// $h_count = substr_count( $content, '</h2' );
			// if ( 1 ) $h_count += substr_count( $content, '</h3' );

			if ( (int) get_query_var( 'page' ) > 1 ) {
				// ２ページ目以降の時はコンテンツ上部に目次を追加
				$content = $toc_content . $content;
			} else {
				$content = preg_replace( $tag, $toc_content . $tags[0], $content, 1 );
			}

			SWELL::$added_toc = true;
		}
	}

	return $content;
}


/**
 * lazyloadを追加
 */
function add_lazysizes( $content ) {

	// サーバーサイドレンダー, wp-json/wp/v2 などからはフック通さない
	if ( SWELL::is_rest() || SWELL::is_iframe() ) return $content;

	// iframe
	$content = preg_replace_callback( '/<iframe([^>]*)>/', function( $matches ) {
		$props = rtrim( $matches[1], '/' );

		// すでにlazyload設定が済んでいれば何もせず返す
		if ( strpos( $props, ' data-src=' ) !== false ) {
			return $matches[0];
		}

		$noscript = '<noscript>' . $matches[0] . '</noscript>';

		// src を data-srcへ
		$props = str_replace( ' src=', ' data-src=', $props );

		// クラスの追加
		if ( strpos( $props, 'class=' ) === false ) {
			// class自体がまだがなければ
			$props .= ' class="lazyload" ';
		} else {
			// クラスの中身を調べる
			$props = preg_replace_callback( '/class="([^"]*)"/', function( $class_match ) {
				$class_value = $class_match[1];
				// クラスにまだ 'lazyload' が付与されていなければ
				if ( strpos( $class_value, 'lazyload' ) === false ) {
					return 'class="' . $class_value . ' lazyload"';
				}
				return $class_match[0];
			}, $props );
		}

		return $noscript . '<iframe' . $props . '>';
	}, $content );

	// img, video
	$content = preg_replace_callback( '/<(img|video)([^>]*)>/', function( $matches ) {
		// var_dump( $matches );
		$tag   = $matches[1];
		$props = rtrim( $matches[2], '/' );

		$noscript = '<noscript><' . $tag . $props . '></noscript>';

		// すでにlazyload設定が済んでいれば何もせず返す
		if ( strpos( $props, ' data-src=' ) !== false || strpos( $props, ' data-srcset=' ) !== false ) {
			return $matches[0];
		}

		// インライン画像の場合は -no-lb つけるだけ
		// if ( 'img' === $tag && strpos( $props, 'style=' ) !== false ) {
		// 	$props = str_replace( ' class="', ' class="-no-lb ', $props );
		// 	return '<' . $tag . $props . '>';
		// }

		// srcを取得
		preg_match( '/\ssrc="([^"]*)"/', $props, $src_matches );
		$src = ( $src_matches ) ? $src_matches[1] : '';
		if ( ! $src ) return $matches[0]; // srcなければ何もせず返す

		// src を data-srcへ
		$props = str_replace( ' src=', ' src="' . SWELL::$placeholder . '" data-src=', $props );

		// srcset を data-srcsetへ
		$props = str_replace( ' srcset=', ' data-srcset=', $props );

		// width , height指定を取得（インライン画像の場合は styleでサイズ指定されてるのでそれが消えないようにスキップ）
		if ( strpos( $props, 'style=' ) === false && strpos( $props, 'width:' ) === false ) {
			$props = SWELL::set_aspectratio( $props, $src );
		}

		// クラスの追加
		if ( strpos( $props, 'class=' ) === false ) {
			// class自体がまだがなければ
			$props .= ' class="lazyload" ';
		} else {
			// クラスの中身を調べる
			$props = preg_replace_callback( '/class="([^"]*)"/', function( $class_match ) {
				$class_value = $class_match[1];
				// クラスにまだ 'lazyload' が付与されていなければ
				if ( strpos( $class_value, 'lazyload' ) === false ) {
					return 'class="' . $class_value . ' lazyload"';
				}
				return $class_match[0];
			}, $props );
		}

		return '<' . $tag . $props . '>' . $noscript;

	}, $content );

	return $content;
}


/**
 * Luminousをオンにするクラスがあるかどうか
 */
function check_luminous( $content ) {

	if ( SWELL::is_use( 'luminous' ) ) return $content;

	// クラスの中身を調べる
	$is_matched = preg_match_all( '/class="([^"]*)"/', $content, $matches );
	if ( ! $is_matched ) return $content;

	if ( ! empty( $matches ) ) {
		foreach ( $matches[1] as $classnames ) {

			if ( false !== strpos( $classnames, 'u-lb-on' ) ) {
				SWELL::set_use( 'luminous', true );
			}
		}
	}
	return $content;
}


/**
 * URLから自動でブログカード化
 */
function url_to_blog_card( $content ) {

	// ( ? ) -> 過去のブログカードに対する処理...？
	// $content = preg_replace( '/<div class="wp-block-embed__wrapper">([^<]+?)<\/div>/is', '$1', $content );

	$pat_sub = preg_quote( '-._~%:/?#[]@!$&\'()*+,;=', '/' );
	/* $pat     = '/^(<p>)?(<a.+?>)?(https?:\/\/[0-9a-z' . $pat_sub . ']+)(<\/a>)?(<\/p>)?/im'; */

	// pタグの中のURL
	$content = preg_replace_callback(
		'/^<p>(https?:\/\/[0-9a-z' . $pat_sub . ']+)<\/p>/im',
		__NAMESPACE__ . '\url_matches_callback',
		$content
	);

	// embedブロックの中のURL
	$content = preg_replace_callback(
		'/<div class="wp-block-embed__wrapper">[\n\s]*(https?:\/\/[0-9a-z' . $pat_sub . ']+)[\n\s]*<\/div>/im',
		__NAMESPACE__ . '\url_matches_callback',
		$content
	);

	return $content;
}


function url_matches_callback( $matches ) {

	$url = wp_strip_all_tags( $matches[1] );

	if ( strpos( $url, 'instagram.com' ) !== false ) return $url;
	if ( strpos( $url, 'facebook.com' ) !== false ) return $url;
	if ( strpos( $url, 'twitter.com' ) !== false ) return $url;
	if ( strpos( $url, 'youtube.com' ) !== false ) return $url;

	$post_id = url_to_postid( $url );
	if ( $post_id ) {
		// 内部リンク
		return SWELL::get_internal_blog_card( $post_id );
	} else {
		// 外部リンク
		return SWELL::get_external_blog_card( $url );
	}
	return $url;

}
