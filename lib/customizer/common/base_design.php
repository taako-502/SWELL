<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セクション追加
 */
$section = 'swell_section_base_design';

$wp_customize->add_section( $section, [
	'title'    => '基本デザイン',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );


// サイト全体の見た目
Customizer::big_title( $section, 'base_display', [
	'label' => 'サイト全体の見た目',
] );

// 全体の質感
Customizer::sub_title( $section, 'base_material', [
	'label' => '全体の質感',
] );

// 全体をフラットにする
Customizer::add( $section, 'to_site_flat', [
	'label' => '全体をフラットにする',
	'type'  => 'checkbox',
] );

// 全体に丸みをもたせる
Customizer::add( $section, 'to_site_rounded', [
	'label' => '全体に丸みをもたせる',
	'type'  => 'checkbox',
] );

// コンテンツの背景を白にする
Customizer::add( $section, 'content_frame', [
	'label'   => 'コンテンツの背景を白にする',
	'type'    => 'radio',
	'choices' => [
		'frame_off'     => 'オフ',
		'frame_on'      => 'オン',
		'frame_on_main' => 'オン（メインエリアのみ）',
	],
] );

Customizer::add( $section, 'frame_scope', [
	'classname' => '-frame-setting',
	'label'     => 'どのページに適用するか',
	'type'      => 'select',
	'choices'   => [
		''          => '全てのページ',
		'post'      => '投稿ページのみ',
		'page'      => '固定ページのみ',
		'post_page' => '投稿・固定ページ',
	],
] );

Customizer::add( $section, 'on_frame_border', [
	'classname' => '-frame-setting',
	'label'     => 'さらに、コンテンツを線で囲む',
	'type'      => 'checkbox',
] );


// ■ フォント設定
Customizer::big_title( $section, 'body_font', [
	'label' => 'フォント設定',
] );

// ベースとなるフォント
Customizer::add( $section, 'body_font_family', [
	'classname'   => '',
	'label'       => 'ベースとなるフォント',
	'description' => '実際に出力されるfont-familyについて詳しくは<a href="https://swell-theme.com/basic-setting/3114/" target="_blank" rel="noopener">こちら</a>',
	'type'        => 'select',
	'choices'     => [
		'yugo'     => '游ゴシック',
		'hirago'   => 'ヒラギノゴシック > メイリオ',
		'notosans' => 'Noto Sans JP',
		'serif'    => '明朝体 (Noto Serif JP)',
	],
] );

// フォントサイズ(PC・Tab)
Customizer::add( $section, 'post_font_size_pc', [
	'label'     => 'フォントサイズ(PC・Tab)',
	'type'      => 'select',
	'choices'   => [
		'14px' => '極小(14px)',
		'15px' => '小(15px)',
		'16px' => '中(16px)',
		'17px' => '大(17px)',
		'18px' => '極大(18px)',
	],
] );

// フォントサイズ(SP)
Customizer::add( $section, 'post_font_size_sp', [
	'label'     => 'フォントサイズ(Mobile)',
	'type'      => 'select',
	'choices'   => [
		'14px'  => '固定サイズ：小',
		'15px'  => '固定サイズ：中',
		'16px'  => '固定サイズ：大',
		'3.8vw' => 'デバイス可変：小',
		'4vw'   => 'デバイス可変：中',
		'4.2vw' => 'デバイス可変：大',
	],
] );


// ■ コンテンツ幅の設定
Customizer::big_title( $section, 'content_width', [
	'label' => 'コンテンツ幅の設定',
] );

// サイト幅の最大値
Customizer::add( $section, 'container_size', [
	'classname'   => '',
	'label'       => 'サイト幅',
	'description' => '※ 左右に48pxずつpaddingがつきます。',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '20',
		'min'  => '400',
	],
	'sanitize'    => 'absint',
] );

// 記事コンテンツ幅の最大値
Customizer::add( $section, 'article_size', [
	'classname'   => '',
	'label'       => '１カラム時の記事コンテンツ幅',
	'description' => '※ 左右に32pxずつpaddingがつきます。',
	'type'        => 'number',
	'input_attrs' => [
		'step' => '20',
		'min'  => '400',
	],
	'sanitize'    => 'absint',
] );



// ■ サブメニューの表示形式
Customizer::big_title( $section, 'submenu_type', [
	'label' => 'サブメニューの表示形式',
] );


// サブメニューをアコーディオン化する
Customizer::add( $section, 'acc_submenu', [
	'classname'   => '',
	'label'       => 'サブメニューをアコーディオン化する',
	'type'        => 'checkbox',
	'description' => '※ グローバルナビ・スマホメニュー・ウィジェット内のサブメニューがアコーディンオン式になります。',

] );


// ■ ページ背景
Customizer::big_title( $section, 'body_bg', [
	'label' => 'ページ背景',
] );

// ページ背景画像(PC)
Customizer::add( $section, 'body_bg', [
	'label' => 'ページ背景画像(PC)',
	'type'  => 'image',
] );

// ページ背景画像(SP)
Customizer::add( $section, 'body_bg_sp', [
	'label' => 'ページ背景画像(SP)',
	'type'  => 'image',
] );

// 画像サイズ(backgrond-sizeの値)
Customizer::add( $section, 'body_bg_size', [
	'classname' => '-bodybg-setting',
	'label'     => '画像サイズ(backgrond-sizeの値)',
	'type'      => 'select',
	'choices'   => [
		''          => '設定なし',
		'100% auto' => '横100%',
		'auto 100%' => '縦100%',
		'contain'   => 'contain',
		'cover'     => 'cover',
	],
] );

// 画像位置(X方向)
Customizer::add( $section, 'body_bg_pos_x', [
	'classname' => '-bodybg-setting -radio-button',
	'label'     => '画像位置(X方向)',
	'type'      => 'radio',
	'choices'   => [
		'left'   => 'left',
		'center' => 'center',
		'right'  => 'right',
	],
] );

// 画像位置(Y方向)
Customizer::add( $section, 'body_bg_pos_y', [
	'classname' => '-bodybg-setting -radio-button',
	'label'     => '画像位置(Y方向)',
	'type'      => 'radio',
	'choices'   => [
		'top'    => 'top',
		'center' => 'center',
		'bottom' => 'bottom',
	],
] );

// その他の設定
Customizer::sub_title( $section, 'body_bg_others', [
	'classname' => '-bodybg-setting',
	'label'     => 'その他の設定',
] );

// 画像ループを無効にする
Customizer::add( $section, 'noloop_body_bg', [
	'classname' => '-bodybg-setting',
	'label'     => '画像ループを無効にする',
	'type'      => 'checkbox',
] );

// 固定表示する（スクロールで動かないようにする）
Customizer::add( $section, 'fix_body_bg', [
	'classname' => '-bodybg-setting',
	'label'     => '固定表示する（スクロールで動かないようにする）',
	'type'      => 'checkbox',
] );
