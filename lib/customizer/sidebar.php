<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * セクション追加
 */
$section = 'swell_section_sidebar';

$wp_customize->add_section( $section, [
	'title'    => 'サイドバー',
	'priority' => 3,
] );


// サイドバーを表示するかどうか
Customizer::sub_title( $section, 'is_show_sidebar', [
	'label' => 'サイドバーを表示するかどうか',
] );

// トップページにサイドバーを表示する
Customizer::add( $section, 'show_sidebar_top', [
	'label'       => 'トップページにサイドバーを表示する',
	'type'        => 'checkbox',
] );

// 投稿ページにサイドバーを表示する
Customizer::add( $section, 'show_sidebar_post', [
	'label'       => '投稿ページにサイドバーを表示する',
	'type'        => 'checkbox',
] );

// 固定ページにサイドバーを表示する
Customizer::add( $section, 'show_sidebar_page', [
	'label'       => '固定ページにサイドバーを表示する',
	'type'        => 'checkbox',
] );

// アーカイブページにサイドバーを表示する
Customizer::add( $section, 'show_sidebar_archive', [
	'label'       => 'アーカイブページにサイドバーを表示する',
	'type'        => 'checkbox',
] );

// サイドバーの位置
Customizer::add( $section, 'sidebar_pos', [
	'classname'   => '-radio-button',
	'label'       => 'サイドバーの位置',
	'type'        => 'radio',
	'choices'     => [
		'left'  => '左',
		'right' => '右',
	],
] );
