<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_pager';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'ページャー',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );

// ページャーの形
Customizer::add( $section, 'pager_shape', [
	'classname' => '-radio-button',
	'label'     => 'ページャーの形',
	'type'      => 'radio',
	'choices'   => [
		'square' => '四角',
		'circle' => '丸',
	],
	'partial'   => [
		'selector' => '.pagination',
	],
] );

// ページャーのデザイン
Customizer::add( $section, 'pager_style', [
	'classname' => '',
	'label'     => 'ページャーのデザイン',
	'type'      => 'radio',
	'choices'   => [
		'border' => '枠線付き',
		'bg'     => '背景グレー',
	],
] );
