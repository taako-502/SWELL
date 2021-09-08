<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_breadcrumb';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'パンくずリスト',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );

// パンくずリストの位置
Customizer::add( $section, 'pos_breadcrumb', [
	'label'   => 'パンくずリストの位置',
	'type'    => 'radio',
	'choices' => [
		'top'    => 'ページ上部',
		'bottom' => 'ページ下部',
	],
] );

// 「ホーム」の文字列
Customizer::add( $section, 'breadcrumb_home_text', [
	'label' => '「ホーム」の文字列',
	'type'  => 'text',
] );

// その他の設定
Customizer::sub_title( $section, 'breadcrumb_others', [
	'classname' => '',
	'label'     => 'その他の設定',
] );

// カテゴリー・タグページで「投稿ページ」を親にセットする
Customizer::add( $section, 'breadcrumb_set_home', [
	'label'       => 'カテゴリー・タグの親に「投稿ページ」をセット',
	'description' => '※ ホームページの表示設定で「投稿ページ」を設定している時のみ有効です。',
	'type'        => 'checkbox',
] );


// パンくずリストの背景効果を無くす
Customizer::add( $section, 'hide_bg_breadcrumb', [
	'label'       => 'パンくずリストの背景効果を無くす',
	'description' => '※ タイトル位置が「コンテンツ上」のページやコンテンツ背景の白設定がオンの場合は、自動的にオフになります。',
	'type'        => 'checkbox',
	'partial'     => [
		'selector'            => '#breadcrumb',
		'container_inclusive' => true,
		'render_callback'     => ['\SWELL_THEME\Customizer\Partial', 'breadcrumb' ],
	],
] );
