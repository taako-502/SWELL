<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_sp_menu';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => 'スマホ開閉メニュー',
		'priority' => 10,
		'panel'    => 'swell_panel_common',
	]
);

// ■ カラー設定
Customizer::big_title(
	$section,
	'sp_menu_colors',
	[
		'classname' => '',
		'label'     => 'カラー設定',
	]
);

// 文字色
Customizer::add(
	$section,
	'color_spmenu_text',
	[
		'classname'   => '',
		'label'       => '文字色',
		'type'        => 'color',
	]
);

// 背景色
Customizer::add(
	$section,
	'color_spmenu_bg',
	[
		'classname'   => '',
		'label'       => '背景色',
		'type'        => 'color',
	]
);


// 背景の不透明度
Customizer::add(
	$section,
	'spmenu_opacity',
	[
		'classname'   => '',
		'label'       => '背景の不透明度',
		'type'        => 'number',
		'input_attrs' => [
			'step' => '0.1',
			'min'  => '0',
			'max'  => '1',
		],
	]
);


// メニュー展開時のオーバーレイカラー
Customizer::add(
	$section,
	'color_menulayer_bg',
	[
		'classname'   => '',
		'label'       => 'メニュー展開時のオーバーレイカラー',
		'type'        => 'color',
	]
);


// メニュー展開時のオーバーレイカラーの不透明度
Customizer::add(
	$section,
	'menulayer_opacity',
	[
		'classname'   => '',
		'label'       => 'メニュー展開時のオーバーレイカラーの不透明度',
		'type'        => 'number',
		'input_attrs' => [
			'step' => '0.1',
			'min'  => '0',
			'max'  => '1',
		],
	]
);


// ■ 表示設定
Customizer::big_title(
	$section,
	'sp_menu_settings',
	[
		'classname' => '',
		'label'     => '表示設定',
	]
);

// メインメニュー上に表示するタイトル
Customizer::add(
	$section,
	'spmenu_main_title',
	[
		'classname'   => '',
		'label'       => 'メインメニュー上に表示するタイトル',
		'type'        => 'text',
	]
);


// サブメニューをアコーディオン化する
Customizer::sub_title(
	$section,
	'acc_sp_submenu',
	[
		'label'       => 'サブメニューをアコーディオン化',
		'classname'   => '',
		'description' => '「サイト全体設定」＞「基本デザイン」＞「■ サブメニューの表示形式」から、サブメニューを開閉式にすることができます。',
	]
);
