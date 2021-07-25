<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_sp_bottom_menu';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'    => '下部固定ボタン・メニュー',
		'priority' => 10,
		'panel'    => 'swell_panel_common',
	]
);

// ■ 右下固定ボタン
Customizer::big_title(
	$section,
	'foot_btns',
	[
		'classname' => '',
		'label'     => '右下固定ボタン',
	]
);

// ページトップボタンの表示設定
Customizer::add(
	$section,
	'pagetop_style',
	[
		'classname'   => '',
		'label'       => 'ページトップボタンの表示設定',
		'type'        => 'select',
		'choices'     => [
			'none'        => '非表示',
			'fix_square'  => '表示する（四角形）',
			'fix_circle'  => '表示する（円形）',
		],
	]
);

// 目次ボタンの表示設定
Customizer::add(
	$section,
	'index_btn_style',
	[
		'classname'   => '',
		'label'       => '目次ボタンの表示設定',
		'description' => '※ 目次を設置しているページにのみ表示されます。',
		'type'        => 'select',
		'choices'     => [
			'none'    => '非表示',
			'square'  => '表示する（四角形）',
			'circle'  => '表示する（円形）',
		],
	]
);


// ■ スマホ用固定フッターメニューの設定
Customizer::big_title(
	$section,
	'foot_fix_menu',
	[
		'classname'   => '',
		'label'       => 'スマホ用固定フッターメニューの設定',
		'description' => '「外観」>「メニュー」にて、「固定フッターメニュー」が設定されている場合に表示されます。',
	]
);

// 特殊メニューボタンの表示設定
Customizer::sub_title(
	$section,
	'foot_fix_menu_btns',
	[
		'classname' => '',
		'label'     => '特殊メニューボタンの表示設定',
	]
);

// メニュー開閉ボタンを表示する
Customizer::add(
	$section,
	'show_fbm_menu',
	[
		'classname'   => '',
		'label'       => 'メニュー開閉ボタンを表示する',
		'type'        => 'checkbox',
	]
);

// 検索ボタンを表示する
Customizer::add(
	$section,
	'show_fbm_search',
	[
		'classname'   => '',
		'label'       => '検索ボタンを表示する',
		'type'        => 'checkbox',
	]
);

// ページトップボタンを表示する
Customizer::add(
	$section,
	'show_fbm_pagetop',
	[
		'classname'   => '',
		'label'       => 'ページトップボタンを表示する',
		'type'        => 'checkbox',
	]
);

// 目次メニューを表示する
Customizer::add(
	$section,
	'show_fbm_index',
	[
		'classname'   => '',
		'label'       => '目次メニューを表示する',
		'type'        => 'checkbox',
	]
);

// 開閉メニューのラベルテキスト
Customizer::add(
	$section,
	'fbm_menu_label',
	[
		'classname'   => '',
		'label'       => '開閉メニューのラベルテキスト',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// 検索ボタンのラベルテキスト
Customizer::add(
	$section,
	'fbm_search_label',
	[
		'classname'   => '',
		'label'       => '検索ボタンのラベルテキスト',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// ページトップのラベルテキスト
Customizer::add(
	$section,
	'fbm_pagetop_label',
	[
		'classname'   => '',
		'label'       => 'ページトップのラベルテキスト',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// 目次メニューのラベルテキスト
Customizer::add(
	$section,
	'fbm_index_label',
	[
		'classname'   => '',
		'label'       => '目次メニューのラベルテキスト',
		'type'        => 'text',
		'sanitize'    => 'wp_filter_nohtml_kses',
	]
);

// 固定フッターメニューの背景色
Customizer::add(
	$section,
	'color_fbm_bg',
	[
		'classname'   => '',
		'label'       => '固定フッターメニューの背景色',
		'type'        => 'color',
	]
);

// 固定フッターメニューの文字色
Customizer::add(
	$section,
	'color_fbm_text',
	[
		'classname'   => '',
		'label'       => '固定フッターメニューの文字色',
		'type'        => 'color',
	]
);

// 固定フッターメニューの背景不透明度
Customizer::add(
	$section,
	'fbm_opacity',
	[
		'classname'   => '',
		'label'       => '固定フッターメニューの背景不透明度',
		'type'        => 'number',
		'input_attrs' => [
			'step' => '0.1',
			'min'  => '0',
			'max'  => '1',
		],
	]
);
