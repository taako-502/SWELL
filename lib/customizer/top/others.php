<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_other';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'その他',
	'priority' => 10,
	'panel'    => 'swell_panel_top',
] );


// コンテンツ上の余白量
Customizer::add( $section, 'top_content_mt', [
	'classname'   => '-radio-button',
	'label'       => 'コンテンツ上の余白量',
	'description' => 'メインビジュアル・記事スライダーの部分と、その下のコンテンツ部分との間の余白量を設定できます。',
	'type'        => 'radio',
	'choices'     => [
		'0'   => 'なし',
		'2em' => '狭め',
		'4em' => '標準',
		'6em' => '広め',
	],
] );
