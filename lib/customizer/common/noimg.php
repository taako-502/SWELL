<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_noimg';

/**
 * セクション追加
 */
$wp_customize->add_section( $section, [
	'title'    => 'NO IMAGE画像',
	'priority' => 10,
	'panel'    => 'swell_panel_common',
] );

// NO IMAGE画像
Customizer::add( $section, 'noimg_id', [
	'classname'   => '',
	'label'       => 'NO IMAGE画像',
	'description' => '記事アイキャッチ用の NO IMAGE画像を設定してください。（推奨：横幅1600px以上）',
	'type'        => 'media',
	'mime_type'   => 'image',
] );

// 古いデータだけ残っている場合
if ( ! \SWELL_Theme::get_setting( 'noimg_id' ) && \SWELL_Theme::get_setting( 'no_image' ) ) {
	Customizer::add( $section, 'no_image', [
		'label' => '【旧】NO IMAGE画像',
		'type'  => 'image',
	] );
}
