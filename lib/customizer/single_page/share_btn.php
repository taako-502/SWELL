<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_share_btn';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => __( 'SNS share buttons', 'swell' ),
	'priority' => 10,
	'panel'    => 'swell_panel_single_page',
] );

// ■ 表示設定
Customizer::big_title( $section, 'sns_share_btn', [
	'label' => __( 'Display settings', 'swell' ),
] );

// シェアボタンを表示する位置
Customizer::sub_title( $section, 'is_show_sahre_btn', [
	'label' => __( 'Position to display the share button', 'swell' ),
] );

// 記事上部に表示する
Customizer::add( $section, 'show_share_btn_top', [
	'label' => __( 'Display at the top of the article', 'swell' ),
	'type'  => 'checkbox',
] );

// 記事下部に表示する
Customizer::add( $section, 'show_share_btn_bottom', [
	'label' => __( 'Display at the bottom of the article', 'swell' ),
	'type'  => 'checkbox',
] );

// 画面端に固定表示する
Customizer::add( $section, 'show_share_btn_fix', [
	'label' => __( 'Fixed display at the edge of the screen', 'swell' ),
	'type'  => 'checkbox',
] );

// 表示するボタンの種類
Customizer::sub_title( $section, 'select_sns', [
	'label' => __( 'Types of buttons to display', 'swell' ),
] );

// Facebook
Customizer::add( $section, 'show_share_btn_fb', [
	'label' => 'Facebook',
	'type'  => 'checkbox',
] );

// Twitter
Customizer::add( $section, 'show_share_btn_tw', [
	'label' => 'Twitter',
	'type'  => 'checkbox',
] );

// はてブ
Customizer::add( $section, 'show_share_btn_hatebu', [
	'label' => 'はてブ',
	'type'  => 'checkbox',
] );

// Pocket
Customizer::add( $section, 'show_share_btn_pocket', [
	'label' => 'Pocket',
	'type'  => 'checkbox',
] );

// Pinterest
Customizer::add( $section, 'show_share_btn_pin', [
	'label' => 'Pinterest',
	'type'  => 'checkbox',
] );

// LINE
Customizer::add( $section, 'show_share_btn_line', [
	'label' => 'LINE',
	'type'  => 'checkbox',
] );

// シェアボタンのデザイン
Customizer::add( $section, 'share_btn_style', [
	'label'   => __( 'Share button design type', 'swell' ),
	'type'    => 'select',
	'choices' => [
		'block'     => __( 'Block', 'swell' ),
		'btn'       => __( 'Button', 'swell' ),
		'btn-small' => __( 'Button', 'swell' ) . '(' . __( 'small', 'swell' ) . ')',
		'icon'      => __( 'Icon', 'swell' ),
		'box'       => __( 'Box', 'swell' ),
	],
] );

// URLコピーボタン
Customizer::add( $section, 'urlcopy_btn_pos', [
	'label'   => __( 'URL copy button', 'swell' ),
	'type'    => 'select',
	'choices' => [
		'none'     => __( 'Don\'t show', 'swell' ),
		'in'       => __( 'Small display', 'swell' ),
		'out'      => __( 'Large display', 'swell' ),
	],
] );

// 「シェアしてね」
Customizer::add( $section, 'share_message', [
	'label' => __( 'Message to be displayed above "Share button at the bottom"', 'swell' ),
	'type'  => 'text',
] );

// ■ Twitter用の追加設定
Customizer::big_title( $section, 'sns_share_add_setting', [
	'label' => __( 'Additional settings for Twitter', 'swell' ),
] );

// シェアされた時のハッシュタグ
Customizer::add( $section, 'share_hashtags', [
	'label'       => __( 'Hashtag when shared', 'swell' ),
	'description' => __( 'Enter without including "#", and if there are multiple entries, enter them separated by ",".', 'swell' ),
	'type'        => 'text',
] );

// via設定（メンション先）
Customizer::add( $section, 'share_via', [
	'label'       => __( 'via setting (mention destination)', 'swell' ),
	'description' => __( 'You can add "From @ ◯◯". Please enter the ID name excluding @.', 'swell' ),
	'type'        => 'text',
] );
