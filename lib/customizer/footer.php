<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * セクション追加
 */
$section = 'swell_section_footer';

$wp_customize->add_section( $section, [
	'title'    => 'フッター',
	'priority' => 3,
] );


// ■ カラー設定
Customizer::big_title( $section, 'foot_color', [
	'label' => 'カラー設定',
] );

// フッター背景色
Customizer::add( $section, 'color_footer_bg', [
	'label' => 'フッター背景色',
	'type'  => 'color',
] );

// フッター文字色
Customizer::add( $section, 'color_footer_text', [
	'label' => 'フッター文字色',
	'type'  => 'color',
] );


// ウィジェットエリアの背景色
Customizer::add( $section, 'color_footwdgt_bg', [
	'label' => 'ウィジェットエリアの背景色',
	'type'  => 'color',
] );

// ウィジェットエリアの文字色
Customizer::add( $section, 'color_footwdgt_text', [
	'label' => 'ウィジェットエリアの文字色',
	'type'  => 'color',
] );


// ■ コピーライト設定
Customizer::big_title( $section, 'foot_copy', [
	'label' => 'コピーライト設定',
] );

// コピーライト
Customizer::add( $section, 'copyright', [
	'label' => 'コピーライトのテキスト',
	'type'  => 'text',
] );


// その他の設定
Customizer::big_title( $section, 'foot_other', [
	'label' => 'その他の設定',
] );

// フッターとフッター直前ウィジェットの間の余白をなく
Customizer::add( $section, 'footer_no_mt', [
	'label' => '「フッター」と「フッター直前ウィジェット」の間の余白をなくす',
	'type'  => 'checkbox',
] );

// フッターにSNSアイコンリストを表示する
Customizer::add( $section, 'show_foot_icon_list', [
	'label' => 'フッターにSNSアイコンリストを表示する',
	'type'  => 'checkbox',
] );
