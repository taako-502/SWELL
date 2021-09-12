<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_info_bar';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'お知らせバー',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );

// お知らせバーの表示位置
Customizer::add( $section, 'info_bar_pos', [
	'label'   => 'お知らせバーの表示位置',
	'type'    => 'radio',
	'choices' => [
		'none'        => __( 'Don\'t show', 'swell' ),
		'head_top'    => 'ヘッダー上部に表示',
		'head_bottom' => 'ヘッダー下部に表示',
	],
] );

// ■ 表示内容の設定
Customizer::big_title( $section, 'notice_bar_content', [
	'classname' => '-swell-info-bar',
	'label'     => '表示内容の設定',
] );

// お知らせバーの文字の大きさ
Customizer::add( $section, 'info_bar_size', [
	'classname' => '-swell-info-bar -radio-button',
	'label'     => 'お知らせバーの文字の大きさ',
	'type'      => 'radio',
	'choices'   => [
		'small'  => '小さく',
		'normal' => '普通',
		'big'    => '大きく',
	],
] );

// 表示タイプ
Customizer::add( $section, 'info_flowing', [
	'classname' => '-swell-info-bar',
	'label'     => '表示タイプ',
	'type'      => 'select',
	'choices'   => [
		'no_flow' => 'テキスト位置固定（バー全体がリンク）',
		'btn'     => 'テキスト位置固定（ボタンを設置）',
		'flow'    => 'テキストを横に流す',
	],
] );

// お知らせ内容
Customizer::add( $section, 'info_text', [
	'classname' => '-swell-info-bar',
	'label'     => 'お知らせ内容',
	'type'      => 'text',
] );

// リンク先のURL
Customizer::add( $section, 'info_url', [
	'classname' => '-swell-info-bar',
	'label'     => 'リンク先のURL',
	'type'      => 'text',
	'sanitize'  => 'wp_filter_nohtml_kses',
] );

// ボタンテキスト
Customizer::add( $section, 'info_btn_text', [
	'classname' => '-swell-info-bar -info-btn',
	'label'     => 'ボタンテキスト',
	'type'      => 'text',
] );


// ■ 背景効果
Customizer::big_title( $section, 'info_bar_bg', [
	'classname' => '-swell-info-bar',
	'label'     => '背景効果',
] );

// お知らせバーの背景効果
Customizer::add( $section, 'info_bar_effect', [
	'classname' => '-swell-info-bar',
	'label'     => 'お知らせバーの背景効果',
	'type'      => 'radio',
	'choices'   => [
		'no_effect' => 'なし',
		'gradation' => 'グラデーション',
		'stripe'    => '斜めストライプ',
	],
] );


// ■ カラー設定
Customizer::big_title( $section, 'notice_bar_colors', [
	'classname' => '-swell-info-bar',
	'label'     => 'カラー設定',
] );

// お知らせバー文字色
Customizer::add( $section, 'color_info_text', [
	'classname' => '-swell-info-bar',
	'label'     => 'お知らせバー文字色',
	'type'      => 'color',
] );

// ボタン背景色
Customizer::add( $section, 'color_info_btn', [
	'classname' => '-swell-info-bar -info-btn',
	'label'     => 'ボタン背景色',
	'type'      => 'color',
] );

// お知らせバー背景色
Customizer::add( $section, 'color_info_bg', [
	'classname' => '-swell-info-bar',
	'label'     => 'お知らせバー背景色',
	'type'      => 'color',
] );

// グラデーション用の追加背景色
Customizer::add( $section, 'color_info_bg2', [
	'classname' => '-swell-info-bar -info-col2',
	'label'     => 'グラデーション用の追加背景色',
	'type'      => 'color',
] );
