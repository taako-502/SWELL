<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_color';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => __( 'Basic Colors', 'swell' ),
		'priority' => 10,
		'panel'    => 'swell_panel_common',
	]
);

// メインカラー
Customizer::add(
	$section,
	'color_main',
	[
		'classname'   => '',
		'label'       => __( 'Main Color', 'swell' ),
		// 'description' => __( 'Dark color is recommended.', 'swell' ),
		'type'        => 'color',
	]
);

// テキストカラー
Customizer::add(
	$section,
	'color_text',
	[
		'classname'   => '',
		'label'       => __( 'Text Color', 'swell' ),
		'type'        => 'color',
	]
);

// リンクの色
Customizer::add(
	$section,
	'color_link',
	[
		'classname'   => '',
		'label'       => __( 'Link Color', 'swell' ),
		'type'        => 'color',
	]
);

// 背景色
Customizer::add(
	$section,
	'color_bg',
	[
		'classname'   => '',
		'label'       => __( 'Background Color', 'swell' ),
		'type'        => 'color',
	]
);
