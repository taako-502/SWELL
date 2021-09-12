<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_after_article';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => '記事下エリア',
	'priority' => 10,
	'panel'    => 'swell_panel_single_page',
] );

// ■ SNSアクションエリア設定
Customizer::big_title( $section, 'sns_cta', [
	'label' => 'SNSアクションエリア設定',
] );

// 表示するボタン
Customizer::sub_title( $section, 'sns_cta_check', [
	'label' => '表示するボタン',
] );

// Twitterフォローボタン
Customizer::add( $section, 'show_tw_follow_btn', [
	'label' => 'Twitterフォローボタン',
	'type'  => 'checkbox',
] );

// Instagramフォローボタン
Customizer::add( $section, 'show_insta_follow_btn', [
	'label' => 'Instagramフォローボタン',
	'type'  => 'checkbox',
] );

// Facebookいいねボタン
Customizer::add( $section, 'show_fb_like_box', [
	'label' => 'Facebookいいねボタン',
	'type'  => 'checkbox',
] );

// TwitterのユーザーID
Customizer::add( $section, 'tw_follow_id', [
	'classname'   => '-twitter-setting',
	'label'       => 'TwitterのユーザーID',
	'description' => '@は含めずに入力してください。',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );


// InstagramのユーザーID
Customizer::add( $section, 'insta_follow_id', [
	'classname'   => '-insta-setting',
	'label'       => 'InstagramのユーザーID',
	'description' => '@は含めずに入力してください。',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );


// Facebookいいねボタンの対象URL
Customizer::add( $section, 'fb_like_url', [
	'classname'   => '-fb-setting',
	'label'       => 'Facebookいいねボタンの対象URL',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );

// Facebookいいねボタンに使用するappID
Customizer::add( $section, 'fb_like_appID', [
	'classname'   => '-fb-setting',
	'label'       => 'Facebookいいねボタンに使用するappID',
	'type'        => 'text',
	'sanitize'    => 'wp_filter_nohtml_kses',
] );


// ■ 前後記事へのページリンク設定
Customizer::big_title( $section, 'pn_links', [
	'label' => '前後記事へのページリンク設定',
] );

// 前後記事へのページリンクを表示
Customizer::add( $section, 'show_page_links', [
	'label' => '前後記事へのページリンクを表示',
	'type'  => 'checkbox',
] );

// ページリンクにサムネイル画像を表示する
Customizer::add( $section, 'show_page_link_thumb', [
	'label' => 'ページリンクにサムネイル画像を表示する',
	'type'  => 'checkbox',
] );

// 同じカテゴリーの記事を取得する
Customizer::add( $section, 'pn_link_is_same_term', [
	'label' => '同じカテゴリーの記事を取得する',
	'type'  => 'checkbox',
] );

// 前後記事へのページリンクのデザイン
Customizer::add( $section, 'page_link_style', [
	'label'   => '前後記事へのページリンクのデザイン',
	'type'    => 'select',
	'choices' => [
		'normal' => '標準',
		'simple' => 'シンプル',
	],
] );


// ■ 著者情報エリアの設定
Customizer::big_title( $section, 'post_author', [
	'label' => '著者情報エリアの設定',
] );

// 著者情報を表示
Customizer::add( $section, 'show_author', [
	'label' => '著者情報を表示',
	'type'  => 'checkbox',
] );

// 著者ページへのリンクを表示する
Customizer::add( $section, 'show_author_link', [
	'label' => '著者ページへのリンクを表示する',
	'type'  => 'checkbox',
] );

// 著者情報エリアのタイトル
Customizer::add( $section, 'post_author_title', [
	'label' => '著者情報エリアのタイトル',
	'type'  => 'text',
] );

// ■ 関連記事エリアの設定
Customizer::big_title( $section, 'related_posts', [
	'label' => '関連記事エリアの設定',
] );

// 関連記事を表示
Customizer::add( $section, 'show_related_posts', [
	'label' => '関連記事を表示',
	'type'  => 'checkbox',
] );

// 関連記事エリアのタイトル
Customizer::add( $section, 'related_post_title', [
	'label' => '関連記事エリアのタイトル',
	'type'  => 'text',
] );

// 関連記事のレイアウト
Customizer::add( $section, 'related_post_style', [
	'label'   => '関連記事のレイアウト',
	'type'    => 'select',
	'choices' => [
		'card' => 'カード型',
		'list' => 'リスト型',
	],
] );

// 関連記事の取得方法
Customizer::add( $section, 'post_relation_type', [
	'classname'   => '-radio-button -related-post',
	'label'       => '関連記事の取得方法',
	'description' => 'どの情報から関連記事を取得するかどうか',
	'type'        => 'radio',
	'choices'     => [
		'category' => 'カテゴリー',
		'tag'      => 'タグ',
	],
] );


// ■ コメントエリアの設定
Customizer::big_title( $section, 'comment_area', [
	'label' => 'コメントエリアの設定',
] );

// コメントエリアを表示
Customizer::add( $section, 'show_comments', [
	'label' => 'コメントエリアを表示',
	'type'  => 'checkbox',
] );


Customizer::add( $section, 'comments_title', [
	'label' => '関連記事エリアのタイトル',
	'type'  => 'text',
] );
