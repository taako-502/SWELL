<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_title_style';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'タイトルデザイン',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );

// サブコンテンツのタイトルデザイン
Customizer::add( $section, 'sec_title_style', [
	'label'       => 'サブコンテンツのタイトルデザイン',
	'description' => '記事下コンテンツやウィジェットで追加されたコンテンツ上に表示されるタイトルのデザインを選択してください。',
	'type'        => 'select',
	'choices'     => [
		''         => '装飾なし',
		'b_bottom' => '下線',
		'b_left'   => '左に縦線',
		'b_lr'     => '左右に横線',
	],
] );


// ■ ウィジェットタイトル
Customizer::big_title( $section, 'widget', [
	'label' => 'ウィジェットタイトル',
] );

// サイドバーのタイトルデザイン（PC）
Customizer::add( $section, 'sidettl_type', [
	'label'   => 'サイドバーのタイトルデザイン（PC）',
	'type'    => 'select',
	'choices' => [
		'b_bottom' => '下線',
		'b_left'   => '左に縦線',
		'b_lr'     => '左右に横線',
		'fill'     => '塗り',
	],
] );

// サイドバーのタイトルデザイン（SP）
Customizer::add( $section, 'sidettl_type_sp', [
	'label'   => 'サイドバーのタイトルデザイン（SP）',
	'type'    => 'select',
	'choices' => [
		''         => '- PC表示に合わせる -',
		'b_bottom' => '下線',
		'b_left'   => '左に縦線',
		'b_lr'     => '左右に横線',
		'fill'     => '塗り',
	],
] );


// フッターのタイトルデザイン
Customizer::add( $section, 'footer_title_type', [
	'label'   => 'フッターのタイトルデザイン',
	'type'    => 'select',
	'choices' => [
		''         => '装飾なし',
		'b_bottom' => '下線',
		'b_lr'     => '左右に線',
		// 'fill' => '塗り',
	],
] );


// スマホメニュー内のタイトルデザイン
Customizer::add( $section, 'spmenu_title_type', [
	'label'   => 'スマホメニュー内のタイトルデザイン',
	'type'    => 'select',
	'choices' => [
		'b_bottom' => '下線',
		'b_left'   => '左に縦線',
		'b_lr'     => '左右に横線',
		'fill'     => '塗り',
	],
] );
