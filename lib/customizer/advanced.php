<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * セクション追加
 */
$section = 'swell_section_advanced';

$wp_customize->add_section( $section, [
	'title'    => '高度な設定',
	'priority' => 10,
] );

Customizer::add( $section, 'head_code', [
	'label'       => 'headタグ終了直前に出力するコード',
	'description' => '&lt;/head&gt;直前',
	'type'        => 'textarea',
	'sanitize'    => '',
	'transport'   => 'postMessage',
] );

Customizer::add( $section, 'body_open_code', [
	'label'       => 'bodyタグ開始直後に出力するコード',
	'description' => '&lt;body&gt;直後',
	'type'        => 'textarea',
	'sanitize'    => '',
	'transport'   => 'postMessage',
] );

Customizer::add( $section, 'foot_code', [
	'label'       => 'bodyタグ終了直前に出力するコード',
	'description' => '&lt;/body&gt;直前',
	'type'        => 'textarea',
	'sanitize'    => '',
	'transport'   => 'postMessage',
] );
