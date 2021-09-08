<?php
use \SWELL_Theme\Customizer;
if ( ! defined( 'ABSPATH' ) ) exit;

$section = 'swell_section_content_header';

/**
 * セクション追加
 */
$wp_customize->add_section(
	$section,
	[
		'title'       => 'コンテンツヘッダー',
		'priority'    => 10,
		'panel'       => 'swell_panel_common',
		'description' => 'タイトルの表示位置が「コンテンツ上」の時に表示されるエリアの設定です。',
	]
);

// タイトル背景用デフォルト画像
Customizer::add(
	$section,
	'ttlbg_dflt_imgid',
	[
		'label'       => 'タイトル背景用デフォルト画像',
		'description' => '投稿ページの「タイトル背景画像」でアイキャッチ画像よりも優先させたい画像がある場合に設定してください。',
		'type'        => 'media',
		'mime_type'   => 'image',
	]
);
// 古いデータだけ残っている場合
if ( ! \SWELL_Theme::get_setting( 'ttlbg_dflt_imgid' ) && \SWELL_Theme::get_setting( 'ttlbg_default_img' ) ) {
	Customizer::add( $section, 'ttlbg_default_img', [
		'label' => '【旧】タイトル背景用デフォルト画像',
		'type'  => 'image',
	] );
}


// 画像フィルター
Customizer::add(
	$section,
	'title_bg_filter',
	[
		'classname'   => '',
		'label'       => '画像フィルター',
		'description' => 'タイトル表示位置が「コンテンツ上」の時の背景画像へのフィルター処理',
		'type'        => 'select',
		'choices'     => [
			'nofilter'        => 'なし',
			'filter-blur'     => 'ブラー',
			'filter-glay'     => 'グレースケール',
			'texture-dot'     => 'ドット',
			'texture-brushed' => 'ブラシ',
		],
	]
);

// カラーオーバーレイの設定
Customizer::add(
	$section,
	'ttlbg_overlay_color',
	[
		'classname'   => '',
		'label'       => 'カラーオーバーレイの設定',
		'description' => 'タイトル背景画像に被せるカラーレイヤーの色',
		'type'        => 'color',
	]
);

// オーバレイカラーの不透明度
Customizer::add(
	$section,
	'ttlbg_overlay_opacity',
	[
		'classname'   => '',
		// 'label'       => 'オーバレイカラーの不透明度',
		'description' => 'オーバレイカラーの不透明度<br>（CSSの opacity プロパティの値）',
		'type'        => 'number',
		'input_attrs' => [
			'step' => '0.1',
			'min'  => '0',
			'max'  => '1',
		],
	]
);
