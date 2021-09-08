<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セクション追加
 */
$section = 'swell_section_archive';

$wp_customize->add_section( $section, [
	'title'    => 'アーカイブページ',
	'priority' => 4,
] );


// ■ タイトル設定
Customizer::big_title( $section, 'archive_title', [
	'label' => 'タイトル設定',
] );

// 表示位置
Customizer::add( $section, 'term_title_pos', [
	'label'       => '表示位置',
	'description' => '※ タームアーカイブページでのみ有効です。',
	'type'        => 'select',
	'choices'     => [
		'top'    => __( 'Above the content', 'swell' ),
		'inner'  => __( 'Inside the content', 'swell' ),
	],
] );



// タイトルのデザイン
Customizer::add( $section, 'archive_title_style', [
	'label'       => 'コンテンツ内タイトルデザイン',
	'description' => 'タイトルが「コンテンツ内」に表示される場合のデザイン',
	'type'        => 'select',
	'choices'     => [
		''         => '装飾なし',
		'b_bottom' => '下線',
	],
] );


// タームナビゲーション
Customizer::sub_title( $section, 'term_navigation', [
	'label'       => 'タームナビゲーション',
	'description' => '親ターム・子タームへの導線を設置するかどうか。',
] );

// カテゴリーページに表示する
Customizer::add( $section, 'show_category_nav', [
	'label' => 'カテゴリーページに表示する',
	'type'  => 'checkbox',
] );
